<?php

declare(strict_types=1);

include __DIR__ . '/config/config.php';

use Models\Session;
use Models\CMS;
use Models\Permission;
use Models\Helper;

$api_key = base64_encode(password_hash('joshbakla', PASSWORD_DEFAULT));
$api = API;

$session_user = Session::session_user();
$page_info = CMS::where('id', 1)->first();
$helper = new Helper();


function permission(string $module, string $access, string $role): bool
{
  $accessMap = [
    'r' => 'read_access',
    'w' => 'write_access',
    'd' => 'delete_access'
  ];

  $access_type = $accessMap[$access] ?? null;

  if ($access_type === null) {
    return false;
  }

  $permission = Permission::where('role_id', $role)
    ->where('module', 'like', "%$module%")
    ->where($access_type, 1)
    ->first();

  return $permission != null;
}



$route = $_GET['route'] ?? null;

if ($session_user == null) {
  include __DIR__ . '/resources/views/login.php';
  exit;
}
if ($route == null) {
  include __DIR__ . '/resources/views/login.php';
  exit;
}




if (permission(str_replace('/', '', $route), 'r', $session_user->role) == false && $route !== '/home') {
  include __DIR__ . '/404.php';
  exit;
}




if ($route === '/dashboard') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/dashboard.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/tickets') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/tickets.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/departments') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/departments.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}



if ($route === '/roles') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/roles.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}



if ($route === '/users') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/users/users.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/profile') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/users/profile.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/logs') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/logs.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/settings') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/settings.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/home') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/users/home.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}




if ($route === '/category') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/category.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}



if ($route === '/groups') {
  include __DIR__ . '/resources/components/header.php';
  include __DIR__ . '/resources/views/groups.php';
  include __DIR__ . '/resources/components/footer.php';
  exit;
}


if ($route === '/logout') {
  header('Location: api/logout.php');
}












function route($route = null, $views = null)
{
  return __DIR__ . '/resources/views/' . $views;
}
