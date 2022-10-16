<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {//root folder, no parameters to routes involved

	$page = new Page();

	$page->setTpl("index");

});

$app->run();

 ?>