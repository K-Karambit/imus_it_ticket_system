<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\User;
use Models\Session;

$session_user = Session::where('token', '=', $_SESSION['SESSION_TOKEN'] ?? null)->first();

if ($session_user != null) {
    $user = User::where('user_id', '=', $session_user->user_id);
    echo json_encode($user->first());
}
