<?php


//$allowedDomain = "";
//header("Access-Control-Allow-Origin: $allowedDomain");

use Illuminate\Support\Facades\Hash;

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
header("Content-Type: application/json;");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'none'; frame-ancestors 'none'; script-src 'self'; connect-src 'self'");
header("Referrer-Policy: no-referrer");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$get_api_key = $_GET['X-API-Key'] ?? null;
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $get_api_key;
$apiKey = base64_decode($apiKey, true);

if (!password_verify(API_KEY, $apiKey)) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
