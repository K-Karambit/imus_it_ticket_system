<?php
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\Permission;
use Models\Activity;
use Models\Role;

$activity = new Activity();

if ($_GET['action'] === 'all') {
    $permissions = Permission::all();
    echo json_encode($permissions);
}
if ($_GET['action'] === 'get') {
    $role_id = $_GET['role_id'];
    $permission = Permission::where('role_id', $role_id)->get();
    echo json_encode($permission);
}
