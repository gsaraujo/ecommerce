<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);

//root folder
$app->get('/', function() {//root folder, no parameters to routes involved

	$page = new Page();

	$page->setTpl("index");

});

//admin
$app->get('/admin', function() {//root folder, no parameters to routes involved

	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->run();

 ?>