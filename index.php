<?php
session_start();

define('ROOT',     __DIR__);
define('BASE_URL', '/hpc_renluyen');

require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Auth.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/core/Router.php';

$router = new Router();
$router->dispatch();
