<?php


use \Hcode\Page;
use \Hcode\Model\Category;

//root folder
$app->get('/', function() {//root folder, no parameters to routes involved

	$page = new Page();

	$page->setTpl("index");

});

//list itens on a category
$app->get('/categories/:idcategory', function($idcategory) {
	
	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		'products'=>[]
	));

});

?>