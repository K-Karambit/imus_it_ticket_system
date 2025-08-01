<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\State;
use Models\Activity;
use Models\Helper;
use Models\Ticket;
use Models\Session;
use Models\User;
use Models\Notification;
use Illuminate\Support\Facades\DB;

$activity = new Activity();
$helper = new Helper();



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


if ($_GET['action'] === 'submit') {
    $ticket_id = $_POST['ticket_id'] ?? null;
    $details = $_POST['details'] ?? null;
    $status = $_POST['status'] ?? null;
    $reassigned_user = $_POST['reassigned_user'] ?? null;

    $session = new Session();
    $session = $session->session_user();
    $updated_by = $session->user_id;

    $ticket = Ticket::where('ticket_id', $ticket_id)->first();
    $assigned_user = $ticket->assigned_user ?? null;

    $reassigned_user_full_name = User::where('user_id', $reassigned_user)->first()->full_name ?? null;

    if (empty($ticket->user_id)) {
        $ticket->user_id = $updated_by ?? null;
    }

    if ($status === 'Reassign') {
        $status = 'In Progress';
        $assign_by = $_POST['assign_by'] ?? null;

        if ($assign_by === 'user') {
            $ticket->user_id = $reassigned_user;
            $details = "Reassign From {$assigned_user} to {$reassigned_user_full_name} <br/><br/> $details";
        } else if ($assign_by === 'group') {
            $current_ticket_group_id = $ticket->creator->group;
            $new_group_id = $_POST['assigned_group_id'] ?? null;
            $new_group_name = $_POST['assigned_group_name'] ?? null;
            $ticket->user_id = null;

            $ticket->group_id = $new_group_id;
            $details = "Reassign from <strong>{$ticket->creator->group->group_name}</strong> group to <strong>{$new_group_name}</strong> <br/><br/> $details";
        }
    }

    $ticket->status = $status;

    $state = new State();
    $state->ticket_id = $ticket_id;
    $state->note = $helper->detectURLString($details);
    $state->status = $status;
    $state->updated_by = $updated_by;

    $activity->addActivityLog('ticket', "update ticket #$ticket_id status to $status");

    try {
        $capsule::beginTransaction();

        $ticket->save();
        $state->save();


        if ($state->updated_by !== $session->user_id) {
            $notification = new Notification();
            $notification->notif_for = $ticket->user_id;
            $notification->notif_by = $state->updated_by;
            $notification->title = "{$state->ticket_id} ticket updated";
            $notification->ticket_id = $ticket->ticket_id;
            $notification->model = 'ticket';
            $notification->save();
        }


        $capsule::commit();

        echo json_encode(['status' => 'success', 'message' => 'Ticket status submitted successfully.']);
    } catch (PDOException $e) {
        $capsule::rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
