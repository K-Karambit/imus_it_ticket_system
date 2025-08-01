<?php
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\Ticket;
use Models\Activity;
use Shuchkin\SimpleXLSXGen;
use Models\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use Models\Department;
use Models\Session;
use Illuminate\Support\Carbon;
use Models\Notification;
use Models\State;
use Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

$activity = new Activity();
$helper = new Helper();
$session =  Session::session_user();
$action = $_GET['action'] ?? null;



if (!$action) {
    http_response_code(404);
    exit;
}



if ($action === 'all') {
    $tickets = Ticket::orderBy('id', 'desc');
    $user_id = $_POST['user_id'] ?? null;

    if ($user_id != null) {
        $tickets->where('user_id', $user_id);
    }

    if ($session->is_super_admin != 1) {
        $tickets->where('group_id', $session->group_id);
    }

    $limit = $_GET['limit'] ?? 10;
    $page = $_GET['page'] ?? 1;

    $result = $ticket->simplePaginate($limit, ['*'], 'page', $page);
    echo json_encode($result);
}



if ($action === 'dashboard') {
    $tickets = Ticket::orderBy('created_at', 'desc');

    if ($session->is_super_admin != 1) {
        $tickets->where('group_id', $session->group_id);
    }

    $results = $tickets->get();
    echo json_encode($tickets);
}



if ($action === 'new_tickets_count') {
    $tickets = Ticket::where('status', 'New');

    if ($session->is_super_admin != 1) {
        $tickets->where('group_id', $session->group_id);
    }

    $result = $tickets->count();
    echo json_encode($result);
}











if ($action === 'user_ticket') {
    $userid = ($_GET['userid']) ?? null;
    $tickets = Ticket::orderBy('created_at', 'desc')->where('user_id', '=', $userid)->get();
    echo json_encode($tickets);
}










// --- Your Existing Add Ticket Logic (No changes needed here for link detection) ---
if ($action === 'add') {
    // ... (your existing ticket add logic) ...
    $ticket = new Ticket();
    $added_by_user_id = Session::session_user()->user_id;

    $ticket->fill($_POST);
    $ticket->subject = $helper->detectURLString($_POST['subject']);
    $ticket->description = $helper->detectURLString($_POST['description']);
    $ticket->added_by = $added_by_user_id;
    $department_id = Department::where('name', 'like', $_POST['department'])->first()->id;

    $ticket_count = $ticket->count() + 1;

    $generated_ticket_id = str_pad($ticket_count, 7, "0", STR_PAD_LEFT);

    $ticket->ticket_id = $generated_ticket_id;
    $ticket->status = 'New';
    $ticket->department = $department_id;
    $ticket->group_id = $session->group_id; // Assuming $session is available

    // Assuming $activity is available
    $activity->addActivityLog('ticket', "added new ticket #$generated_ticket_id");

    try {
        $capsule::beginTransaction();
        $ticket->save();

        // $state = new State();
        // $state->ticket_id = $generated_ticket_id;
        // $state->status = 'New';
        // $state->note = 'Ticket created';
        // $state->updated_by = $ticket->added_by;
        // $state->save();

        if ($ticket->user_id !== $session->user_id) {
            $notification = new Notification();
            $notification->notif_for = $ticket->user_id;
            $notification->notif_by = $ticket->added_by;
            $notification->title = "You’ve been assigned to Ticket #{$ticket->ticket_id}";
            $notification->ticket_id = $ticket->ticket_id;
            $notification->model = 'ticket';
            $notification->save();
        }

        echo json_encode(['status' => 'success', 'message' => 'Ticket added successfully.']);

        $capsule::commit();
    } catch (PDOException $e) {
        $capsule::rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
// --- End of Add Ticket Logic ---






















if ($action === 'filter') {
    $urgency = json_decode($_GET['urgency'], true) ?? [];
    $department = json_decode($_GET['department'], true) ?? [];
    $status = json_decode($_GET['status'], true) ?? [];
    $category = json_decode($_GET['category'], true) ?? [];
    $user = json_decode($_GET['user'], true) ?? [];

    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    $searchQuery = $_GET['searchQuery'] ?? null;

    $post_user_id = $_POST['user_id'] ?? null;
    $get_user_id = $_GET['user_id'] ?? null;
    $user_id = $post_user_id ? $post_user_id : $get_user_id;

    $ticket = Ticket::orderBy('id', 'desc');
    if ($session->is_super_admin != 1) {
        $groupId = $session->group_id;

        // Get user IDs in the same group
        $userIds = User::where('group_id', $groupId)->pluck('user_id');

        // Get ticket IDs from states updated by those users
        $ticketIds = State::whereIn('updated_by', $userIds)->pluck('ticket_id');

        // Get group IDs from those tickets
        $fetchedGroupIds = Ticket::whereIn('ticket_id', $ticketIds)->pluck('group_id');

        // Combine original group and fetched groups, removing duplicates
        $allGroupIds = collect([$groupId])->merge($fetchedGroupIds)->unique();

        // Apply group ID filter to the ticket query
        $ticket->whereIn('group_id', $allGroupIds);
    }

    if ($user_id) {
        $ticket->where('user_id',  $user_id);
    }
    if (count($department) > 0) {
        $ticket->whereIn('department',  $department);
    }
    if (count($urgency) > 0) {
        $ticket->whereIn('urgency', $urgency);
    }
    if (count($status) > 0) {
        $ticket->whereIn('status', $status);
    }
    if (count($category) > 0) {
        $ticket->whereIn('category', $category);
    }
    if (count($user) > 0) {
        $ticket->whereIn('user_id', $user);
    }
    if ($startDate && $endDate) {
        $ticket->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->addDay()]);
    }
    if ($searchQuery) {
        $userSearch = function ($q) use ($searchQuery) {
            $q->where('last_name', 'like', "%{$searchQuery}%")
                ->orWhere('first_name', 'like', "%{$searchQuery}%")
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$searchQuery}%")
                ->orWhere('email', 'like', "%{$searchQuery}%");
        };

        $departmentSearch = function ($q) use ($searchQuery) {
            $q->where('name', 'like', "%{$searchQuery}%");
        };

        $ticket->where(function ($query) use ($searchQuery, $userSearch, $departmentSearch) {
            $query->where('ticket_id', 'like', "%{$searchQuery}%")
                ->orWhere('subject', 'like', "%{$searchQuery}%")
                ->orWhere('description', 'like', "%{$searchQuery}%")
                ->orWhere('urgency', 'like', "%{$searchQuery}%")
                ->orWhere('status', 'like', "%{$searchQuery}%")
                ->orWhere('claimant_name', 'like', "%{$searchQuery}%")
                ->orWhere('client_name', 'like', "%{$searchQuery}%")
                ->orWhere('amount', 'like', "%{$searchQuery}%")
                ->orWhereHas('creator', $userSearch)
                ->orWhereHas('assigned', $userSearch)
                ->orWhereHas('department', $departmentSearch);
        });
    }

    $limit = $_GET['limit'] ?? 10;
    $page = $_GET['page'] ?? 1;

    // $paginate = $ticket->simplePaginate($limit, ['*'], 'page', $page);
    // $totalPage = $ticket->count();
    // echo json_encode(['data' => $paginate, 'totalPage' => $totalPage]);



    // Get full filtered collection first
    $allTickets = $ticket->get();

    // Manual pagination setup
    $limit = (int) ($_GET['limit'] ?? 10);
    $page = (int) ($_GET['page'] ?? 1);
    $offset = ($page - 1) * $limit;

    // Slice the data manually
    $items = $allTickets->slice($offset, $limit)->values(); // `values()` resets keys

    // Create paginator instance
    $paginate = new LengthAwarePaginator(
        $items,
        $allTickets->count(),
        $limit,
        $page,
        ['path' => $_SERVER['REQUEST_URI']]
    );

    // Return as JSON
    echo json_encode([
        'data' => $paginate->items(),
        'current_page' => $paginate->currentPage(),
        'total' => $paginate->total(),
        'total_page' => $paginate->lastPage(),
    ]);
}

































if ($action === 'export') {
    $urgency = json_decode($_GET['urgency'], true) ?? [];
    $department = json_decode($_GET['department'], true) ?? [];
    $status = json_decode($_GET['status'], true) ?? [];
    $category = json_decode($_GET['category'], true) ?? [];
    $user = json_decode($_GET['user'], true) ?? [];

    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    $searchQuery = $_GET['searchQuery'] ?? null;

    $post_user_id = $_POST['user_id'] ?? null;
    $get_user_id = $_GET['user_id'] ?? null;

    $individual = $_GET['individual'] ?? null;

    $ticket = Ticket::query();


    if ($session->is_super_admin != 1) {
        $groupId = $session->group_id;

        // Get user IDs in the same group
        $userIds = User::where('group_id', $groupId)->pluck('user_id');

        // Get ticket IDs from states updated by those users
        $ticketIds = State::whereIn('updated_by', $userIds)->pluck('ticket_id');

        // Get group IDs from those tickets
        $fetchedGroupIds = Ticket::whereIn('ticket_id', $ticketIds)->pluck('group_id');

        // Combine original group and fetched groups, removing duplicates
        $allGroupIds = collect([$groupId])->merge($fetchedGroupIds)->unique();

        // Apply group ID filter to the ticket query
        $ticket->whereIn('group_id', $allGroupIds);
    }
    if ($post_user_id != null) {
        $ticket->where('user_id', '=', $post_user_id);
    }
    if ($get_user_id != null) {
        $ticket->where('user_id', '=', $get_user_id);
    }
    if (!empty($individual)) {
        $ticket->where('user_id', '=', $individual);
    }
    if (count($department) > 0) {
        $ticket->whereIn('department',  $department);
    }
    if (count($urgency) > 0) {
        $ticket->whereIn('urgency', $urgency);
    }
    if (count($status) > 0) {
        $ticket->whereIn('status', $status);
    }
    if (count($category) > 0) {
        $ticket->whereIn('category', $category);
    }
    if (count($user) > 0) {
        $ticket->whereIn('user_id', $user);
    }
    if ($startDate != null && $endDate) {
        $ticket->whereBetween('created_at', [$startDate, $endDate]);
    }
    if ($searchQuery) {
        $userSearch = function ($q) use ($searchQuery) {
            $q->where('last_name', 'like', "%{$searchQuery}%")
                ->orWhere('first_name', 'like', "%{$searchQuery}%")
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$searchQuery}%")
                ->orWhere('email', 'like', "%{$searchQuery}%");
        };

        $departmentSearch = function ($q) use ($searchQuery) {
            $q->where('name', 'like', "%{$searchQuery}%");
        };

        $ticket->where(function ($query) use ($searchQuery, $userSearch, $departmentSearch) {
            $query->where('ticket_id', 'like', "%{$searchQuery}%")
                ->orWhere('subject', 'like', "%{$searchQuery}%")
                ->orWhere('description', 'like', "%{$searchQuery}%")
                ->orWhere('urgency', 'like', "%{$searchQuery}%")
                ->orWhereHas('creator', $userSearch)
                ->orWhereHas('assigned', $userSearch)
                ->orWhereHas('department', $departmentSearch);
        });
    }

    if ($session->is_super_admin != 1) {
        $ticket->where('group_id', $session->group_id);
    }

    $results = $ticket->get();

    // Prepare data for Excel
    $data = [['ID', 'Created By', 'Assigned To', 'Category', 'Urgency', 'Short Description', 'Description', 'Department', 'Status', 'History']]; // Headers
    foreach ($results as $ticket) {
        $data[] = [
            $ticket->ticket_id,
            $ticket->added_by_name,
            $ticket->assigned_user,
            $ticket->category_name,
            $ticket->urgency,
            $ticket->short_description,
            $ticket->description,
            $ticket->department_name,
            $ticket->status,
            $ticket->ticket_states,
        ];
    }

    // Generate and download the Excel file
    $xlsx = SimpleXLSXGen::fromArray($data);

    // Define file name
    $fileName = 'tickets_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Send headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $xlsx->download($fileName);
    exit;
}










if ($action === 'print') {
    $urgency = $_GET['urgency'] ?? null;
    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    // Initialize the query with a join
    $tickets = Ticket::query();

    // Filter by urgency if provided
    if (!is_null($urgency)) {
        $tickets->where('urgency', $urgency); // Exact match filter
    }

    // Filter by date range if both start and end dates are provided
    if (!is_null($startDate) && !is_null($endDate)) {
        $tickets->whereBetween('created_at', [$startDate, $endDate]);
    }

    if ($session->is_super_admin != 1) {
        $tickets->where('group_id', $session->group_id);
    }

    // Fetch results
    $results = $tickets->get();

    if ($results->isEmpty()) {
        echo json_encode(['status' => 'error', 'message' => 'No data found for the given filters.']);
        exit;
    }

    // Prepare data for Excel
    $data = [['ID', 'Assigned To', 'Short Description', 'Description', 'Department', 'States']]; // Headers
    foreach ($results as $ticket) {
        $data[] = [
            $ticket->ticket_id,
            $ticket->assigned_user,
            $ticket->short_description,
            $ticket->description,
            $ticket->department_name,
            $ticket->ticket_states,
        ];
    }

    // Generate and download the Excel file
    $xlsx = SimpleXLSXGen::fromArray($data);

    // Define file name
    $fileName = 'tickets_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Send headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $xlsx->download($fileName);
    exit;
}










if ($action === 'monthly_report') {
    $countsPerMonth = DB::table('tickets')->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count');

    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;

    if (isset($_GET['user_id'])) {
        $countsPerMonth->where('user_id', 'like', $_GET['user_id']);
    }

    if (!empty($startDate) && !empty($endDate)) {
        $countsPerMonth->whereBetween('created_at', [$startDate, $endDate]);
    }

    if ($session->role != 123) {
        $countsPerMonth->where('group_id', $session->group_id);
    }

    // if (!empty($_GET['year_filter'])) {
    //     $countsPerMonth->whereYear('created_at', $_GET['year_filter']);
    // }

    // if (!empty($_GET['user_filter'])) {
    //     $countsPerMonth->where('user_id', '=', $_GET['user_filter']);
    // }

    // if (!empty($_GET['department_filter'])) {
    //     $countsPerMonth->where('department', '=', $_GET['department_filter']);
    // }

    $results =  $countsPerMonth->groupBy('month')->get();

    $months = [];
    $years = [];
    $counts = [];

    foreach ($results as $row) {
        $months[] = date('F', strtotime("$row->year-$row->month"));
        $years[] =  $row->year;
        $counts[] = $row->count;
    }

    $unresolved_tickets = Ticket::where('status', '!=', 'Resolved')->where('status', '!=', 'Cancelled');

    $unresolved_tickets = $unresolved_tickets->where('group_id', $session->group_id);

    $unresolved_tickets = $unresolved_tickets->get();

    $three_days_unresolved = [];
    $one_week_unresolved = [];

    $three_days_ago = Carbon::now()->subDays(3)->startOfDay()->timestamp;
    $one_week_ago = Carbon::now()->subDays(7)->startOfDay()->timestamp;

    foreach ($unresolved_tickets as $row) {
        $date = date('Y-m-d', strtotime($row['created_at']));
        $ticket_time = strtotime($date);

        if ($ticket_time <= $three_days_ago) {
            $three_days_unresolved[] = $row;
        }
        if ($ticket_time <= $one_week_ago) {
            $one_week_unresolved[] = $row;
        }
    }

    echo json_encode(
        [
            'year' => $years,
            'month' => $months,
            'count' => $counts,
            'three_days_unresolved' => $three_days_unresolved,
            'one_week_unresolved' => $one_week_unresolved,
        ]
    );
}














if ($action === 'department_report') {
    $countsPerMonth = DB::table('tickets')->join('departments', 'departments.id', '=', 'tickets.department')->selectRaw('departments.name, COUNT(*) as count')->groupBy('department')->get();
    echo json_encode($countsPerMonth);
}











if ($action === 'update_urgency') {
    $ticket = Ticket::find($_GET['id']);

    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Ticket not found or already deleted.']);
        return;
    }

    $ticket->urgency = $_GET['urgency'];
    $ticket->save();

    Activity::addActivityLog('Ticket', "changed urgency of ticket $ticket->ticket_id");

    echo json_encode(['status' => 'success', 'message' => 'Ticket urgency updated successfully.']);
}










if ($action === 'update_category') {
    $ticket = Ticket::find($_GET['id']);

    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Ticket not found or already deleted.']);
        return;
    }

    $ticket->category = $_GET['category'];
    $ticket->save();

    Activity::addActivityLog('Ticket', "changed category of ticket $ticket->ticket_id");

    echo json_encode(['status' => 'success', 'message' => 'Ticket category updated successfully.']);
}






if ($action === 'update_department') {
    $ticket = Ticket::find($_GET['id']);

    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Ticket not found or already deleted.']);
        return;
    }

    $ticket->department = $_GET['department'];
    $ticket->save();

    Activity::addActivityLog('Ticket', "changed department of ticket $ticket->ticket_id");

    echo json_encode(['status' => 'success', 'message' => 'Ticket department updated successfully.']);
}



if ($action === 'overview') {
    $id = $_GET['id'] != 'null' ? $_GET['id'] : $session->user_id;
    $tickets = Ticket::where('user_id', $id);

    if ($session && $session->role == 1) {
        $tickets->where('user_id', $session->user_id);
    }

    if ($session->is_super_admin != 1) {
        $tickets->where('group_id', $session->group_id);
    }

    $tickets = $tickets->get();

    $inProgress = 0;
    $new = 0;
    $today = 0;

    foreach ($tickets as $ticket) {
        if ($ticket['status'] === 'New') {
            $new++;
        }
        if ($ticket['status'] === 'In Progress') {
            $inProgress++;
        }
        if (Carbon::parse($ticket['created_at'])->format('Y-m-d') === now()->format('Y-m-d')) {
            $today++;
        }
    }

    echo json_encode([
        'inProgress' => $inProgress,
        'new' => $new,
        'today' => $today,
    ]);
}








// Assuming $action and $session are defined somewhere above this snippet

if ($action === 'counts') {
    $baseQuery = Ticket::query();
    $now = Carbon::now(); // Get current time once

    // --- Authorization / Filtering Logic ---
    // Assuming if super_admin OR role 1, they see ALL tickets.
    // Otherwise, they only see tickets updated by users in their group.
    if (!($session->is_super_admin == 1 || $session->role == 1)) {
        // $userIds = User::where('group_id', $session->group_id)->pluck('user_id')->toArray();

        // $logsTicketIds = State::whereIn('updated_by', array_unique($userIds))
        //     ->pluck('ticket_id')
        //     ->toArray();

        // $baseQuery->whereIn('ticket_id', array_unique($logsTicketIds));

        $baseQuery->where('user_id', $session->user_id ?? null);
    }

    // --- Efficient Counting using Database Queries ---

    $newTickets = $baseQuery->clone()->where('status', 'New')->count();
    $inProgress = $baseQuery->clone()->where('status', 'In Progress')->count();
    $onHold     = $baseQuery->clone()->where('status', 'on hold')->count();
    $resolved   = $baseQuery->clone()->where('status', 'Resolved')->count();
    $cancelled  = $baseQuery->clone()->where('status', 'Cancelled')->count();
    $totalTickets = $baseQuery->clone()->where('status', '!=', 'Cancelled')->count();

    // For unresolved tickets: Filter by status (not resolved/cancelled)
    // You might want to adjust these statuses based on your definition of "unresolved"
    $unresolvedBaseQuery = $baseQuery->clone()
        ->whereNotIn('status', ['Resolved', 'Cancelled']);

    // Unresolved tickets older than 3 days
    $unresolved3Days = $unresolvedBaseQuery->clone()
        ->where('created_at', '<', $now->copy()->subDays(3))
        ->count();

    // Unresolved tickets older than 7 days
    // This will naturally include tickets that are also older than 3 days.
    $unresolved7Days = $unresolvedBaseQuery->clone()
        ->where('created_at', '<', $now->copy()->subDays(7))
        ->count();

    // If you explicitly wanted tickets *only* between 3 and 7 days (exclusive of 7 days old and older),
    // you would calculate it as:
    // $onlyBetween3And7Days = $unresolvedBaseQuery->clone()
    //                                              ->where('created_at', '<', $now->copy()->subDays(3))
    //                                              ->where('created_at', '>=', $now->copy()->subDays(7)) // Greater than or equal to 7 days *ago*
    //                                              ->count();
    // This would give you tickets that passed the 3-day mark but not yet the 7-day mark.
    // However, your original request was "not include the 3 days data in 7 days unresolved",
    // which typically implies standard age buckets. The current `unresolved7Days` already
    // correctly counts tickets that are strictly older than 7 days.

    $data = [
        'new' => $newTickets,
        'in_progress' => $inProgress,
        'on_hold' => $onHold,
        'resolved' => $resolved,
        'cancelled' => $cancelled,
        'unresolved3Days' => $unresolved3Days,
        'unresolved7Days' => $unresolved7Days, // This now contains ONLY tickets older than 7 days
        'totalTickets' => $totalTickets,
    ];

    echo json_encode($data);
}
