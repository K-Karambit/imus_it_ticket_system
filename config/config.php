<?php

require __DIR__ . '/../vendor/autoload.php';  // Autoload Composer dependencies
require __DIR__ . '/../constants.php'; // Assumed to contain DB_DRIVER, DB_HOST, DB_NAME, DB_USER, DB_PASS

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher; // Needed if you use event dispatcher
use Illuminate\Container\Container; // Needed if you use event dispatcher

date_default_timezone_set('Asia/Manila');

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => DB_DRIVER,
    'host'      => DB_HOST,
    'database'  => DB_NAME,
    'username'  => DB_USER,
    'password'  => DB_PASS,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => DB_PORT,
    'sslmode'   => SSL_MODE,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => MYSQL_ATTR_SSL_CA,
    ]) : [],
]);

// Optional: Set the event dispatcher if your models use events
$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();
$capsule->bootEloquent();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Rest of your application code...
