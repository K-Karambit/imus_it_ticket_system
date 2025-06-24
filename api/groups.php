<?php
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\User;
use Models\Activity;
use Models\Helper;
use Models\Session;
use Models\Group;

$activity = new Activity();
$helper = new Helper();
$session = new Session();

$action = $_GET['action'] ?? null;


if ($action === 'all') {
    $groups = Group::orderBy('id', 'desc')->where('is_deleted', 0)->get();
    echo json_encode($groups);
}

if ($action === 'count') {
    $users = User::where('is_deleted', 0)->count();
    echo $users ?? 0;
}


if ($action === 'one') {
    $user = User::where('user_id', '=', $_GET['id'])->first();
    echo json_encode($user);
}





if ($action === 'store') {
    try {
        $group_name = $_POST['group_name'] ?? null;
        $group = Group::where('group_name', $group_name)->first();

        if ($group) {
            $group->is_deleted = 0;
            $group->save();
            echo json_encode(['status' => 'error', 'message' => 'Group restored.']);
            return;
        }

        $group = new Group();
        $group->group_id = md5($group_name);
        $group->group_name = $group_name;
        $group->save();


        $activity->addActivityLog('ticket', "added new group, $group_name");


        echo json_encode(['status' => 'success', 'message' => 'Group added successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}



if ($action === 'update') {
    try {
        $id = $_POST['id'] ?? null;
        $group_name = $_POST['group_name'] ?? null;

        $group = Group::find($id);

        if (!$group) {
            echo json_encode(['status' => 'error', 'message' => 'Group does not exists.']);
            return;
        }

        $activity->addActivityLog('ticket', "added update group, $group_name");

        $group->group_name = $group_name;
        $group->save();


        echo json_encode(['status' => 'success', 'message' => 'Group updated successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}







if ($action === 'delete') {
    try {
        $id = $_GET['id'] ?? null;
        $group = Group::find($id);

        if (!$group) {
            echo json_encode(['status' => 'error', 'message' => 'Group does not exists.']);
            return;
        }

        $activity->addActivityLog('ticket', "deleted group, $group->group_name");

        $group->is_deleted = 1;
        $group->save();
        echo json_encode(['status' => 'success', 'message' => 'Group deleted successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
