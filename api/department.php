<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\Department;
use Models\Activity;
use Shuchkin\SimpleXLSXGen;


if ($_GET['action'] === 'all') {
    $departments = Department::orderBy('name')->where('is_deleted', 0)->get();
    echo json_encode($departments);
}



if ($_GET['action'] === 'store') {
    $department = Department::where('name', 'like', $_POST['name'])->where('is_deleted', 0)->first();

    if ($department != null) {
        echo json_encode(['status' => 'error', 'message' => 'Department already exist.']);
        return;
    }

    $department = Department::where('name', 'like', $_POST['name'])->where('is_deleted', 1)->first();

    if ($department != null) {
        $department->is_deleted = 0;
        $department->save();
        echo json_encode(['status' => 'success', 'message' => 'Department restored.']);

        Activity::addActivityLog('departments', "restored " . $_POST['name'] . ' department');

        return;
    }

    $department = new Department();
    $department->fill($_POST);

    try {
        Activity::addActivityLog('departments', "added " . $_POST['name'] . ' department');
        $department->save();
        echo json_encode(['status' => 'success', 'message' => 'Department added successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}



if ($_GET['action'] === 'update') {
    $department = Department::where('id', $_POST['department_id'])->first();
    $department->fill($_POST);
    try {
        Activity::addActivityLog('departments', 'edited ' . $department->name . ' department');
        $department->save();
        echo json_encode(['status' => 'success', 'message' => 'Department updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}






if ($_GET['action'] === 'delete') {
    $department = Department::where('id', $_GET['id'])->where('is_deleted', 0)->first();
    $department->is_deleted = 1;

    try {
        Activity::addActivityLog('departments', 'removed ' . $department->name . ' department');
        $department->save();
        echo json_encode(['status' => 'success', 'message' => 'Department removed successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}


if (isset($_GET['export'])) {
    $departments = Department::where('is_deleted', 0)->get();

    $data = [];
    $data = [['<b>Department</b>', '<b>Date Created</b>']]; // Headers

    foreach ($departments as $row) {
        $data[] = [
            $row['name'],
            $row['date_added']
        ];
    }

    // Generate and download the Excel file
    $xlsx = SimpleXLSXGen::fromArray($data);

    // Define file name
    $fileName = "Departments-" . now() . ".xlsx";

    // Send headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $xlsx->download();
    exit;
}
