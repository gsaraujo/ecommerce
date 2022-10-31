<?php


use \Hcode\Page;
use \Hcode\Model\Category;
use Hcode\Model\Product;

//root folder
$app->get('/', function() {//root folder, no parameters to routes involved

	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index",[
		"products"=>Product::checkList($products)
	]);

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