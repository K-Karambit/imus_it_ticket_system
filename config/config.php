<?php

require __DIR__  . '/../vendor/autoload.php';  // Autoload Composer dependencies
require __DIR__ . '/../constants.php';


use Illuminate\Database\Capsule\Manager as Capsule;


date_default_timezone_set('Asia/Manila');


$capsule = new Capsule;
$capsule->addConnection([
    'driver' => DB_DRIVER,
    'host' => DB_HOST,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
