<?php
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Microsoft\Graph\Generated\Models\Location;
use Models\Notification;
use Models\Session;

$session_user =  Session::session_user();

$action = $_GET['action'] ?? '';


if ($action === 'index') {
    $notificatons = Notification::where('notif_for', $session_user->user_id);
    echo json_encode($notificatons->get());
}

if ($action === 'getUnread') {
    echo getUnread($session_user);
}






function getUnread($session_user)
{
    $notificatons = Notification::where('is_read', 0)->where('notif_for', $session_user->user_id)->get();
    return json_encode($notificatons);
}
