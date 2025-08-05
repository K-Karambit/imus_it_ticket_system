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
use Illuminate\Pagination\LengthAwarePaginator;
use Models\TicketAdditionalInfo;

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



if ($action === 'add') {
    $ticket = new Ticket();
    $added_by_user_id = Session::session_user()->user_id;
    $user_group_id = $_GET['user_group_id'] ?? $session->group_id;

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
    $ticket->group_id = $user_group_id;

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

        if (isset($_POST['additional_info']) && $_POST['additional_info']) {
            $ticketAdditionalInfo = new TicketAdditionalInfo();
            $ticketAdditionalInfo->ticket_id = $generated_ticket_id;
            $ticketAdditionalInfo->fill(json_decode($_POST['additional_info'], true));
            $ticketAdditionalInfo->save();
        }

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

    if (!$session->is_super_admin) {
        $groupId = $session->group_id;

        $ticketIdsArray = DB::table('tickets')
            ->select(DB::raw("ticket_id COLLATE utf8mb4_unicode_ci as ticket_id"))
            ->where('group_id', $groupId)
            ->union(
                DB::table('ticket_old_groups')
                    ->select(DB::raw("ticket_id COLLATE utf8mb4_unicode_ci as ticket_id"))
                    ->where('group_id', $groupId)
            )
            ->pluck('ticket_id')
            ->unique()
            ->toArray();

        $ticket->whereIn('ticket_id', $ticketIdsArray);
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
        'total_tickets' => $allTickets->count(),
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

    if (!$session->is_super_admin) {
        $groupId = $session->group_id;

        // Avoid UNION and run two simpler queries
        $ticketIds1 = DB::table('tickets')
            ->where('group_id', $groupId)
            ->pluck('ticket_id');

        $ticketIds2 = DB::table('ticket_old_groups')
            ->where('group_id', $groupId)
            ->pluck('ticket_id');

        // Merge results in PHP (faster than SQL union + collation casting)
        $ticketIdsArray = $ticketIds1->merge($ticketIds2)->unique()->values()->toArray();

        // Apply filter
        $ticket->whereIn('ticket_id', $ticketIdsArray);
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



if ($action === 'counts') {
    $baseQuery = Ticket::query();
    $now = Carbon::now(); // Get current time once

    if (!$session->is_super_admin) {
        $groupId = $session->group_id;

        // Avoid UNION and run two simpler queries
        $ticketIds1 = DB::table('tickets')
            ->where('group_id', $groupId)
            ->pluck('ticket_id');

        $ticketIds2 = DB::table('ticket_old_groups')
            ->where('group_id', $groupId)
            ->pluck('ticket_id');

        // Merge results in PHP (faster than SQL union + collation casting)
        $ticketIdsArray = $ticketIds1->merge($ticketIds2)->unique()->values()->toArray();

        // Apply filter
        $baseQuery->whereIn('ticket_id', $ticketIdsArray);
    }

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






if ($action === 'userTicketCounts') {
    $user_id = $_GET['id'] !== 'null' ? $_GET['id'] : $session->user_id;

    if (!$user_id) {
        http_response_code(404);
    }

    $totalTicketsToday = $totalInProgressTickets = $totalNewTickets = 0;

    $tickets = Ticket::query();
    $tickets->where('user_id', $user_id);

    if (!$session->is_super_admin) {
        $groupId = $session->group_id;

        $ticketIdsArray = DB::table('tickets')
            ->select(DB::raw("ticket_id COLLATE utf8mb4_unicode_ci as ticket_id"))
            ->where('group_id', $groupId)
            ->union(
                DB::table('ticket_old_groups')
                    ->select(DB::raw("ticket_id COLLATE utf8mb4_unicode_ci as ticket_id"))
                    ->where('group_id', $groupId)
            )
            ->pluck('ticket_id')
            ->unique()
            ->toArray();

        $tickets->whereIn('ticket_id', $ticketIdsArray);
    }

    $result = $tickets->get();

    foreach ($result as $row) {
        if ($row->status === 'New') {
            $totalNewTickets++;
        }
        if ($row->status === 'In Progress') {
            $totalInProgressTickets++;
        }
        if (now()->format('Y-m-d') == Carbon::parse($row->created_at)->format('Y-m-d')) {
            $totalTicketsToday++;
        }
    }

    $data = [
        'today' => $totalTicketsToday,
        'inProgress' => $totalInProgressTickets,
        'new' => $totalNewTickets,
    ];

    echo json_encode($data);
}
