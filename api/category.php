<?php
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Models\Category;
use Models\Activity;
use Models\Helper;
use Models\Session;
use Shuchkin\SimpleXLSXGen;

$activity = new Activity();
$helper = new Helper();
$session =  Session::session_user();


if ($_GET['action'] === 'all') {
    $categories = Category::orderBy('category_name', 'asc');

    if ($session->is_super_admin != 1) {
        $categories->where('group_id', $session->group_id);
    }

    $results = $categories->get();
    echo json_encode($results);
}

if ($_GET['action'] === 'delete') {
    $category = Category::find($_GET['id']);


    Activity::addActivityLog('Category', 'removed category ' . $category->category_name);


    $category->delete();
    echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully.']);
}





if ($_GET['action'] === 'store') {
    $category_value = htmlspecialchars($_POST['category_name']);

    $check = Category::where('category_name', '=', $category_value)->first();

    if ($check) {
        echo json_encode(['status' => 'error', 'message' => 'Category already exist.']);
        return;
    }

    $category = new Category();
    $category->category_name = $category_value;
    $category->group_id = $session->group_id;
    $category->save();

    Activity::addActivityLog('Category', "added new category, $category_value");

    echo json_encode(['status' => 'success', 'message' => 'Category added successfully.']);
}


if ($_GET['action'] === 'update') {
    $id = htmlspecialchars($_POST['id']);
    $category_value = htmlspecialchars($_POST['category_name']);

    $check = Category::where('category_name', '=', $category_value)->where('id', '!=', $category_value)->first();

    if ($check) {
        echo json_encode(['status' => 'error', 'message' => 'Category already exist.']);
        return;
    }

    $category = Category::find($id);
    $category->category_name = $category_value;
    $category->save();

    Activity::addActivityLog('Category', "update category, $category_value");

    echo json_encode(['status' => 'success', 'message' => 'Category added successfully.']);
}


if (isset($_GET['export'])) {
    $departments = Category::all();

    $data = [];
    $data = [['<b>Category</b>', '<b>Date Created</b>']]; // Headers

    foreach ($departments as $row) {
        $data[] = [
            $row['category_name'],
            $row['date_added']
        ];
    }

    // Generate and download the Excel file
    $xlsx = SimpleXLSXGen::fromArray($data);

    // Define file name
    $fileName = "Categories-" . now() . ".xlsx";

    // Send headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $xlsx->download($fileName);
    exit;
}
