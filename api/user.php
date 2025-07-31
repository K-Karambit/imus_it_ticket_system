<?php
include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use Illuminate\Contracts\Cache\Store;
use Models\Activity;
use Models\Helper;
use Models\Role;
use Models\Session;
use Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Shuchkin\SimpleXLSXGen;
use Illuminate\Support\Str;

$activity = new Activity();
$helper   = new Helper();
$session  = Session::session_user();

$action = $_GET['action'] ?? null;

if ($action === 'all') {
    $users = User::orderBy('id', 'desc')->orderBy('group_id')->where('is_deleted', 0);

    if ($session->is_super_admin != 1) {
        $users->where('group_id', $session->group_id);
    }

    $results = $users->get();
    echo json_encode($results);
}

if ($action === 'all_user_in_system') {
    $users = User::orderBy('id', 'desc')->where('is_deleted', 0);


    if ($session->is_super_admin != 1) {
        $users->where('group_id', $session->group_id);
    }


    $results = $users->get();
    echo json_encode($results);
}


if ($action === 'count') {
    $users = User::where('is_deleted', 0);


    if ($session->is_super_admin != 1) {
        $users->where('group_id', $session->group_id);
    }

    $counts = $users->count();

    echo $counts ?? 0;
}

if ($action === 'one') {
    $user = User::where('user_id', '=', $_GET['id'])->first();
    echo json_encode($user);
}

if ($action === 'update') {
    try {

        $imageExtensions = [
            'jpg',  // JPEG image
            'jpeg', // JPEG image
            'png',  // Portable Network Graphics
            'gif',  // Graphics Interchange Format
            'bmp',  // Bitmap image
            'webp', // WebP image
            'svg',  // Scalable Vector Graphics
            'tiff', // Tagged Image File Format
            'tif',  // Tagged Image File Format (alternative extension)
            'ico',  // Icon file
            'heic', // High Efficiency Image File Format
            'raw',  // Raw image format
            'psd',  // Photoshop Document
            'eps',  // Encapsulated PostScript
        ];

        $user   = User::where('user_id', '=', $_POST['user_id'])->first();
        $role   = $_POST['role'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($user->email !== $_POST['email']) {
            $emailExists = User::where('email', $_POST['email'] ?? null)->where('user_id', '!=', $_POST['user_id'])->where('is_deleted', 0)->exists();

            if ($emailExists) {
                echo json_encode(['status' => 'error', 'message' => 'Email already taken.']);
                return;
            }
        }

        if ($user->username !== $_POST['username']) {
            $check_username = User::where('username', 'like', $_POST['username'])->where('user_id', '!=', $_POST['user_id'])->where('is_deleted', 0);

            if ($check_username->count() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Username already taken.']);
                return;
            }
        }

        $role = Role::find($role)->name ?? 'No role found.';

        if ($status !== $user->status) {
            $activity->addActivityLog('user', "set user $user->full_name status to $user->status.");
        }

        if ($role !== $user->role) {
            $activity->addActivityLog('user', "set user $user->full_name role as $role.");
        }

        if (isset($_FILES['profile']) && $_FILES['profile']['name']) {
            $file      = $_FILES['profile'];
            $file_ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = Str::uuid() . '.' . $file_ext;
            $file_name = strtolower($file_name);
            $storage_path    = ROOT . "/api/storage";
            $current_profile = "$storage_path/$user->profile";


            if (!in_array(strtolower($file_ext), $imageExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format.']);
                return;
            }

            if (file_exists($current_profile)) {
                unlink($current_profile);
            }

            $move = move_uploaded_file($file['tmp_name'], "$storage_path/$file_name");

            if ($move) {
                $user->profile = $file_name;
                $activity->addActivityLog('user', "set new profle for $user->full_name");
            }
        }

        $user->fill($_POST);
        $user->save();

        $activity->addActivityLog('user', "edited user, $user->full_name.");

        echo json_encode(['status' => 'success', 'message' => 'User profile information updated successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

if ($action === 'update_password') {
    try {
        $user = User::where('user_id', '=', $_POST['user_id'])->first();

        if (isset($_POST['portal']) && $_POST['portal']) {
            if (! password_verify($_POST['current_password'], $user->password)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid current password. Please try again.']);
                return;
            }
        }

        if (isset($_POST['current_password'])) {
            if (! password_verify($_POST['current_password'], $user->password)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid current password.']);
                return;
            }
        }

        $user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $user->save();

        $activity->addActivityLog('user', "changed the password of user, $user->full_name.");

        echo json_encode(['status' => 'success', 'message' => 'User password credentials updated successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

if ($action === 'add') {
    try {

        $imageExtensions = [
            'jpg',  // JPEG image
            'jpeg', // JPEG image
            'png',  // Portable Network Graphics
            'gif',  // Graphics Interchange Format
            'bmp',  // Bitmap image
            'webp', // WebP image
            'svg',  // Scalable Vector Graphics
            'tiff', // Tagged Image File Format
            'tif',  // Tagged Image File Format (alternative extension)
            'ico',  // Icon file
            'heic', // High Efficiency Image File Format
            'raw',  // Raw image format
            'psd',  // Photoshop Document
            'eps',  // Encapsulated PostScript
        ];

        $emailExists       = User::where('email', $_POST['email'] ?? null)->where('is_deleted', 0)->exists();
        $user              = User::where('username', '=', $_POST['username'])->where('is_deleted', 0)->first();
        $generated_user_id = Str::uuid();

        if ($user) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid. Username already exist.']);
            return;
        }

        if ($emailExists) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid. Email already exist.']);
            return;
        }

        $user = new User();

        if (isset($_FILES['profile']) && $_FILES['profile']['name']) {
            $file         = $_FILES['profile'];
            $file_ext     = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name    = date('YmdHis') . time() . '.' . $file_ext;
            $storage_path    = ROOT . "/api/storage";
            $move         = move_uploaded_file($file['tmp_name'], "$storage_path/$file_name");

            if (!in_array(strtolower($file_ext), $imageExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format.']);
                return;
            }

            if ($move) {
                $user->profile = $file_name;
            }
        }

        $user->fill($_POST);
        $user->group_id = $session->group_id;
        $user->user_id  = $generated_user_id;
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->save();

        $activity->addActivityLog('user', "added user, $user->full_name.");

        echo json_encode(['status' => 'success', 'message' => 'User added successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

if ($action === 'delete') {
    try {
        $user = User::where('user_id', '=', $_POST['user_id'])->first();

        $activity->addActivityLog('user', "deleted user, $user->full_name.");

        $user->is_deleted = 1;
        $user->save();

        echo json_encode(['status' => 'success', 'message' => 'User removed successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

if ($action === 'excel_import') {

    // Check if file was uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $fileTmpPath   = $_FILES['excel_file']['tmp_name'];
        $fileName      = $_FILES['excel_file']['name'];
        $fileType      = mime_content_type($fileTmpPath); // Get MIME type
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedTypes = [
            'application/vnd.ms-excel',                                          // .xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'text/csv',                                                          // .csv
        ];
        $allowedExtensions = ['xls', 'xlsx', 'csv'];
        $exists            = [];

        if (in_array($fileType, $allowedTypes) && in_array($fileExtension, $allowedExtensions)) {

            if ($fileExtension == 'csv') {

                $file = fopen($fileTmpPath, 'r');
                $rows = [];
                while (($data = fgetcsv($file, 1000, ",")) !== false) {
                    $rows[] = $data;
                }
                fclose($file);
            } else {

                $spreadsheet = IOFactory::load($fileTmpPath);
                $worksheet   = $spreadsheet->getActiveSheet();
                $rows        = $worksheet->toArray();
            }

            // Skip first row if it contains headers
            foreach ($rows as $index => $row) {
                if ($index == 0) {
                    continue;
                }

                $first_name  = $row[0];
                $middle_name = $row[1];
                $last_name   = $row[2];
                $email       = $row[3];
                $phone       = $row[4];
                $status      = $row[5];
                $username    = $row[6];
                $password    = $row[7];

                $check_username = User::where('username', 'like', $username)->first();

                if ($check_username != null) {
                    $exists[] = $username;
                    continue;
                }

                $user              = new User();
                $user->user_id     = sha1(time());
                $user->first_name  = $first_name;
                $user->middle_name = $middle_name;
                $user->last_name   = $last_name;
                $user->status      = $status;
                $user->email       = $email;
                $user->role        = 2;
                $user->phone       = $phone;
                $user->username    = $username;
                $user->password    = password_hash($password, PASSWORD_DEFAULT);
                $user->save();
            }

            echo json_encode([
                'status'  => 'success',
                'message' => "Data imported successfully. Except " . implode(', ', $exists) . ", username(s) already exist.",
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Please upload an Excel (.xls, .xlsx) or CSV (.csv) file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File upload error!']);
    }
}

if (isset($_GET['export'])) {
    $users = User::where('is_deleted', 0);

    if ($session->role != 123) {
        $users->where('group_id', $session->group_id);
    }

    $users = $users->get();

    $data = [];
    $data = [['<b>Name</b>', '<b>Username</b>', '<b>Email</b>', '<b>Phone</b>', '<b>Role</b>', '<b>Status</b>', '<b>Date Created</b>']]; // Headers

    foreach ($users as $row) {
        $data[] = [
            $row->full_name,
            $row->username,
            $row->email,
            $row->phone,
            $row->user_role,
            ucwords($row->status),
            $row->date_added,
        ];
    }

    // Generate and download the Excel file
    $xlsx = SimpleXLSXGen::fromArray($data);

    // Define file name
    $fileName = "Users-" . now() . ".xlsx";

    // Send headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $xlsx->download();
    exit;
}
