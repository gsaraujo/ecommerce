<?php


use \Hcode\Page;
use \Hcode\Model\Category;
use \Hcode\Model\Product;
use \Hcode\Model\Cart;


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

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
	
	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);

	$pages = array();

	for ($i=1; $i <= $pagination['pages']; $i++) { 
		array_push($pages, [
			'link'=>'/categories/' . $category->getidcategory() . '?page=' . $i,
			'page'=>$i
		]);
	}

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		'products'=>$pagination["data"],
		'pages'=>$pages
	));

});

//products details
$app->get('/products/:desurl', function($desurl) {

	$product = new Product();

	$product->getFromURL($desurl);

	$page = new Page();

	$page->setTpl("product-detail",[
		'product'=>$product->getValues(),
		'categories'=>$product->getCategories()
	]);

});


//view cart
$app->get('/cart', function() {

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart");

});

?>