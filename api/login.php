<?php

include __DIR__ . '/../config/config.php';

header("Access-Control-Allow-Origin:  *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
header("Content-Type: application/json;");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'none'; frame-ancestors 'none'; script-src 'self'; connect-src 'self'");
header("Referrer-Policy: no-referrer");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

use Models\User;
use Models\Session;
use Models\Activity;
use Illuminate\Support\Facades\Hash;

if (!isset($_POST['username'], $_POST['password'])) {
  http_response_code(404);
  die;
}

$attempt = 0;
$max_attempt = 5;

$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
$password = $_POST['password'];

$user = User::where('username', '=', $username)->orWhere('email', $username)->where('is_deleted', 0)->first();

if (!$user) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
  return;
}

if (!password_verify($password, $user->password)) {
  $user->login_attempt = $user->login_attempt + 1;
  $user->save();
  $current_attempt_left = $max_attempt - $user->login_attempt;

  echo json_encode(['status' => 'error', 'message' => "Invalid username or password."]);
  return;
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$generated_token = md5(random_bytes(10));
$session = new Session();
$session->user_id = $user->user_id;
$session->token = $generated_token;

$datetime = date('m/d/Y h:i A');
Activity::addActivityLog('login', "logged in $datetime", $user);

$session->save();

$_SESSION['SESSION_TOKEN'] = $generated_token;
$_SESSION['SESSION_KEY'] = base64_encode(random_bytes(1000));
echo json_encode(['status' => 'success', 'message' => 'Welcome back!', 'date' => now()->format('Y-m-d h:i A')]);


// if ($user->login_attempt >= $max_attempt) {
//   $expiration = Carbon::parse($user->updated_at)->addMinutes(5);

//   if (now() > $expiration) {
//     $user->login_attempt = 0;
//     $user->save();
//   }

//   $expiration = $expiration->format('h:i A');

//   echo json_encode([
//     'status' => 'error',
//     'message' => "Your account is temporarily locked. You can try again after $expiration."
//   ]);

//   return;
// }
