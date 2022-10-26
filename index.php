<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

//root folder
$app->get('/', function() {//root folder, no parameters to routes involved

	$page = new Page();

	$page->setTpl("index");

});

//admin
$app->get('/admin', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

//admin login
$app->get('/admin/login', function() {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("login");

});

//post admin login
$app->post('/admin/login', function() {

	User::login($_POST["deslogin"],$_POST["despassword"]);

	header("Location: /admin");
	exit;

});

//admin logout
$app->get('/admin/logout', function() {

	User::logout();

	header("Location: /admin/login");
	exit;

});

//list users
$app->get('/admin/users', function() {

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));

});


//create users
$app->get('/admin/users/create', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

//save new users
$app->post('/admin/users/create', function() {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

//delete users
$app->get('/admin/users/:iduser/delete', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//update users
$app->get('/admin/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

//save users update
$app->post('/admin/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});

//forgot password
$app->get('/admin/forgot', function() {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot");
	

});

//forgot password sending form
$app->post('/admin/forgot', function() {

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;
});

//confirming the recovery e-mail was sent
$app->get('/admin/forgot/sent', function() {

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot-sent");
	

});


//recover page to reset the password
$app->get('/admin/forgot/reset', function() {

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));
	

});

//reset password sending form
$app->post('/admin/forgot/reset', function() {

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"],PASSWORD_DEFAULT,[
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header" => false,
		"footer" => false
	]);

	$page->setTpl("forgot-reset-success");



});


$app->run();

 ?>