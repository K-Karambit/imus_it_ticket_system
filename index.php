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

if ($session_user == null || $route == null) {
  include __DIR__ . '/resources/views/login.php';
  exit;
}

$routes = [
  '/dashboard'   => 'dashboard.php',
  '/tickets'     => 'tickets.php',
  '/departments' => 'departments.php',
  '/roles'       => 'roles.php',
  '/users'       => 'users/users.php',
  '/profile'     => 'users/profile.php',
  '/logs'        => 'logs.php',
  '/settings'    => 'settings.php',
  '/home'        => 'users/home.php',
  '/category'    => 'category.php',
  '/groups'      => 'groups.php'
];

if ($route === '/logout') {
  header('Location: api/logout.php');
  exit;
}

if (!array_key_exists($route, $routes)) {
  include __DIR__ . '/404.php';
  exit;
}

// Permission check (except for /home)
if ($route !== '/home' && !permission(str_replace('/', '', $route), 'r', $session_user->role)) {
  include __DIR__ . '/404.php';
  exit;
}

// Render page with header/footer
include __DIR__ . '/resources/components/header.php';
include __DIR__ . '/resources/views/' . $routes[$route];
include __DIR__ . '/resources/components/footer.php';

function route($route = null, $views = null)
{
  return __DIR__ . '/resources/views/' . $views;
}
