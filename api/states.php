<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\State;
use Models\Activity;
use Models\Ticket;
use Models\Session;
use Models\User;

$activity = new Activity();


if ($_GET['action'] === 'get') {
    $ticket_id = $_GET['id'];

    // This line would now result in a plain PHP array,
    // perhaps from an earlier step or hardcoded data.
    // Example: $states = [['created_at' => '2025-06-12 10:30:00', ...], ...];
    $states = State::orderBy('created_at')->where('ticket_id', $ticket_id)->get()->toArray();

    // --- SORT THE ARRAY HERE ---
    // Example: Sort by 'created_at' in ascending order
    usort($states, function ($a, $b) {
        $timeA = strtotime($a['created_at']);
        $timeB = strtotime($b['created_at']);
        return $timeA - $timeB; // Ascending
        // For descending: return $timeB - $timeA;
    });
    // --- END SORTING ---

    echo json_encode($states);
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

if ($_GET['action'] === 'submit') {
    $ticket_id = $_POST['ticket_id'] ?? null;
    $details = $_POST['details'] ?? null;
    $status = $_POST['status'] ?? null;
    $reassigned_user = $_POST['reassigned_user'] ?? null;

    $session = new Session();
    $updated_by = $session->session_user()->user_id;

    $ticket = Ticket::where('ticket_id', '=', $ticket_id)->first();

    $reassigned_user_full_name = User::where('user_id', $reassigned_user)->first()->full_name ?? null;



    if ($status === 'Reassign') {
        $details = "Reassign From $ticket->assigned_user to $reassigned_user_full_name <br/><br/> $details";
		$ticket->user_id = $reassigned_user;
        $status = 'In Progress';
    }

    $ticket->status = $status;

    $state = new State();
    $state->ticket_id = $ticket_id;
    $state->note = autoLinkText($details);
    $state->status = $status;
    $state->updated_by = $updated_by;

    $activity->addActivityLog('ticket', "update ticket #$ticket_id status to $status");

    try {
        $ticket->save();
        $state->save();
        echo json_encode(['status' => 'success', 'message' => 'Ticket status submitted successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
