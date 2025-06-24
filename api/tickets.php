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

$activity = new Activity();
$helper = new Helper();
$session =  Session::session_user();

if ($_GET['action'] === 'all') {
    $tickets = Ticket::orderBy('id', 'desc');
    $user_id = $_POST['user_id'] ?? null;

    if ($user_id != null) {
        $tickets->where('user_id', $user_id);
    }

    $tickets->where('group_id', $session->group_id);

    $result = $tickets->get();
    echo json_encode($result);
}



if ($_GET['action'] === 'dashboard') {
    $tickets = Ticket::orderBy('created_at', 'desc');

    $tickets->where('group_id', $session->group_id);

    $results = $tickets->get();
    echo json_encode($tickets);
}



if ($_GET['action'] === 'new_tickets_count') {
    $tickets = Ticket::where('status', 'New');

    $tickets->where('group_id', $session->group_id);

    $result = $tickets->count();
    echo json_encode($result);
}


if ($_GET['action'] === 'user_ticket') {
    $userid = filter_var($_GET['userid'], FILTER_SANITIZE_STRING);
    $tickets = Ticket::orderBy('created_at', 'desc')->where('user_id', '=', $userid)->get();
    echo json_encode($tickets);
}

function autoLinkText(string $text, int $length_limit = 50): string
{
    if (empty($text)) {
        return '';
    }

    // A more concise and efficient regex for URLs
    $url_regex = '/\b((https?:\/\/|www\.)[^\s()<>]+(?:\([\w\d]+\)|(?:[^`!()\[\]{};:\'".,<>?«»“”‘’\s]|\((?:[^`!()\[\]{};:\'".,<>?«»“”‘’\s]|\(.+\))+\)))*)/i';

    $linked_text = preg_replace_callback(
        $url_regex,
        function (array $matches) use ($length_limit): string {
            $url = $matches[1];
            // Prepend 'http://' only if the URL doesn't start with http(s)://
            $href = (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) ? $url : 'http://' . $url;

            // Determine display text: full URL if short enough, otherwise 'Link'
            $display_text = (strlen($url) <= $length_limit) ? htmlspecialchars($url) : 'Link';

            return '<a href="' . htmlspecialchars($href) . '" target="_blank" rel="noopener noreferrer">' . $display_text . '</a>';
        },
        $text
    );

    return $linked_text;
}

// --- Your Existing Add Ticket Logic (No changes needed here for link detection) ---
if ($_GET['action'] === 'add') {
    // ... (your existing ticket add logic) ...
    $ticket = new Ticket();
    $added_by_user_id = Session::session_user()->user_id;

    $ticket->fill($_POST);
    $ticket->subject = autoLinkText($_POST['subject']);
    $ticket->description = autoLinkText($_POST['description']);
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
        $ticket->save();
        echo json_encode(['status' => 'success', 'message' => 'Ticket added successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
// --- End of Add Ticket Logic ---


if ($_GET['action'] === 'filter') {
    $urgency = json_decode($_GET['urgency'], true) ?? [];
    $department = json_decode($_GET['department'], true) ?? [];
    $status = json_decode($_GET['status'], true) ?? [];
    $category = json_decode($_GET['category'], true) ?? [];
    $user = json_decode($_GET['user'], true) ?? [];

    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    $post_user_id = $_POST['user_id'] ?? null;
    $get_user_id = $_GET['user_id'] ?? null;

    $ticket = Ticket::query();

    if ($post_user_id != null) {
        $ticket->where('user_id', '=', $post_user_id);
    }
    if ($get_user_id != null) {
        $ticket->where('user_id', '=', $get_user_id);
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
        $ticket->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->addDay()]);
    }

    $ticket->where('group_id', $session->group_id);

    $result = $ticket->get();
    echo json_encode($result);
    //echo json_encode($ticket->orderBy('id', 'desc')->get());
}


if ($_GET['action'] === 'export') {
    $urgency = json_decode($_GET['urgency'], true) ?? [];
    $department = json_decode($_GET['department'], true) ?? [];
    $status = json_decode($_GET['status'], true) ?? [];
    $category = json_decode($_GET['category'], true) ?? [];
    $user = json_decode($_GET['user'], true) ?? [];

    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;

    $post_user_id = $_POST['user_id'] ?? null;
    $get_user_id = $_GET['user_id'] ?? null;

    $individual = $_GET['individual'] ?? null;

    $ticket = Ticket::query();

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

    $ticket->where('group_id', $session->group_id);


    $results = $ticket->get();

    // Prepare data for Excel
    $data = [['ID', 'Created By', 'Assigned To', 'Category', 'Urgency', 'Short Description', 'Description', 'Department', 'History']]; // Headers
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










if ($_GET['action'] === 'print') {
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

    $tickets->where('group_id', $session->group_id);


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










if ($_GET['action'] === 'monthly_report') {
    $countsPerMonth = DB::table('tickets')->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count');

    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;

    if (isset($_GET['user_id'])) {
        $countsPerMonth->where('user_id', 'like', $_GET['user_id']);
    }

    if (!empty($startDate) && !empty($endDate)) {
        $countsPerMonth->whereBetween('created_at', [$startDate, $endDate]);
    }

    $countsPerMonth->where('group_id', $session->group_id);


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














if ($_GET['action'] === 'department_report') {
    $countsPerMonth = DB::table('tickets')->join('departments', 'departments.id', '=', 'tickets.department')->selectRaw('departments.name, COUNT(*) as count')->groupBy('department')->get();
    echo json_encode($countsPerMonth);
}











if ($_GET['action'] === 'update_urgency') {
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










if ($_GET['action'] === 'update_category') {
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






if ($_GET['action'] === 'update_department') {
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



if ($_GET['action'] === 'overview') {
    $id = $_GET['id'] != 'null' ? $_GET['id'] : $session->user_id;
    $tickets = Ticket::where('user_id', $id);

    if ($session && $session->role == 1) {
        $tickets->where('user_id', $session->user_id);
    }

    $tickets->where('group_id', $session->group_id);

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
