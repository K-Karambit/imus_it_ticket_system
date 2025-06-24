<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';


use Models\Activity;
use Models\Session;
$session =  Session::session_user();

if ($_GET['action'] === 'all') {
    $logs = Activity::orderBy('id', 'desc');

    $logs->where('group_id', $session->group_id);

    if (isset($_GET['userid'])) {
        $logs->where('user_id', 'like', $_GET['userid']);
    }

    echo json_encode($logs->get());
}
