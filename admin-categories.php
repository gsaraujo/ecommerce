<?php

use \Hcode\Model\Category;
use \Hcode\Model\User;
use \Hcode\PageAdmin;

//list categories
$app->get('/admin/categories', function() {

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTpl("categories",[
		'categories'=>$categories
	]);
	

});

//create new category
$app->get('/admin/categories/create', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");
	

});

//save new category
$app->post('/admin/categories/create', function() {

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;
	

});

//delete category
$app->get('/admin/categories/:idcategory/delete', function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");
	exit;

});


//update category
$app->get('/admin/categories/:idcategory', function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", array(
		"category"=>$category->getValues()
	));

});

//save category update
$app->post('/admin/categories/:idcategory', function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();//since this method rely on the stored procedure that is fitted for both update and create category, we can use it

	header("Location: /admin/categories");
	exit;

});

?>