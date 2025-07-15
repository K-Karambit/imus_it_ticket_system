<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../constants.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

date_default_timezone_set('Asia/Manila');

$capsule = new Capsule;
$capsule->addConnection(CONFIG);

// Optional: Set the event dispatcher if your models use events
$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();
$capsule->bootEloquent();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
