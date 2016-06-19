<?php
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
ini_set('magic_quotes_gpc', 0);

require '../core/App.php';
require '../core/helpers.php';
$app = core\App::getInstance();
$app->run();