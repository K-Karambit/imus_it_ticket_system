<?php

include __DIR__ . '/../config/config.php';
//include __DIR__ . '/../config/headers.php';

use Models\Session;

$session = Session::session_user();
$bool = false;

if ($session) {
  $bool = true;
}

echo json_encode(['session' => $bool]);
