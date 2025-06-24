<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\Role;
use Models\Activity;
use Models\Permission;


$activity = new Activity();

if ($_GET['action'] === 'all') {
    $roles = Role::all();
    echo json_encode($roles);
}

if ($_GET['action'] === 'add') {
    $check_role = Role::where('name', 'like', $_POST['role_name'])->count();
    $role_name = $_POST['role_name'];

    if ($check_role > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Role already exist.']);
        return;
    }

    $role = new Role();
    $role->name = filter_var($_POST['role_name'], FILTER_SANITIZE_STRING);
    $role->description = filter_var($_POST['role_description'], FILTER_SANITIZE_STRING);
    $role->save();

    $modules = $_POST['module'];

    foreach ($modules as $module) {
        $permission = new Permission();
        $permission->role_id = $role->id;
        $permission->module = $module;

        $module = strtolower($module);

        $permission->read_access = $_POST["read-$module"] ?? 0;
        $permission->write_access = $_POST["write-$module"] ?? 0;
        $permission->delete_access = $_POST["delete-$module"] ?? 0;
        $permission->save();
    }

    $activity->addActivityLog('role', "added $role_name role.");

    echo json_encode(['status' => 'success', 'message' => 'Role added successfully.']);
}


if ($_GET['action'] === 'delete') {
    $role = Role::where('id', '=', $_POST['role_id'])->first();

    if ($role == null) {
        echo json_encode(['status' => 'error', 'message' => 'Role does not exist.']);
        return;
    }

    $activity->addActivityLog('role', "removed $role->name role.");
    $role->delete();

    echo json_encode(['status' => 'success', 'message' => 'Role deleted successfully.']);
}





if ($_GET['action'] === 'update') {
    $role = Role::find($_POST['role_id']);
    $updates = [];

    if ($role->count() == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Role does not exist.']);
        return;
    }
    if ($_POST['role_name'] !== $role->name) {
        $updates[] = 'role name';
    }

    if ($_POST['role_description'] !== $role->description) {
        $updates[] = 'role description';
    }

    $role->name = filter_var($_POST['role_name'], FILTER_SANITIZE_STRING);
    $role->description = filter_var($_POST['role_description'], FILTER_SANITIZE_STRING);
    $role->save();

    foreach ($_POST['module'] as $module) {
        $permission = Permission::where('role_id', '=', $_POST['role_id'])->where('module', 'like', $module)->first();

        $loweCaseModule = base64_encode(strtolower($module));

        if ($permission == null) {
            $new_permission = new Permission();
            $new_permission->role_id = $_POST['role_id'];
            $new_permission->module = $module;
            $new_permission->read_access = $_POST["read-$loweCaseModule"] ?? 0;
            $new_permission->write_access = $_POST["write-$loweCaseModule"] ?? 0;
            $new_permission->delete_access = $_POST["delete-$loweCaseModule"] ?? 0;
            $new_permission->save();
        } else {
            $permission->read_access = $_POST["read-$loweCaseModule"] ?? 0;
            $permission->write_access = $_POST["write-$loweCaseModule"] ?? 0;
            $permission->delete_access = $_POST["delete-$loweCaseModule"] ?? 0;
            $permission->save();
        }
    }

    $updates = implode(', ', $updates);
    $activity->addActivityLog('role', "edit $role->name.");

    echo json_encode(['status' => 'success', 'message' => 'Role updated successfully.']);
}
