<?php

include __DIR__ . '/../config/config.php';
//include __DIR__ . '/../config/headers.php';

use Models\CMS;
use Models\Helper;

$helper = new Helper();

// if ($_GET['action'] === 'update') {
//     $sys_name = $_GET['sys_name'];
//     $sys_logo = $_FILES['sys_logo'];

//     if ($sys_logo) {
//         $dir = $helper->public_path();
//     }
// }

//echo response()->json(['status' => false]);