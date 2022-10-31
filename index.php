<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once("site.php");//including site related routes
require_once("functions.php");//including functions to be used all over
require_once("admin.php");//including admin related routes
require_once("admin-users.php");//including admin users related routes
require_once("admin-categories.php");//including admin categories related routes
require_once("admin-products.php");//including admin products related routes


$app->run();

 ?>