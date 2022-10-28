<?php


use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

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

?>