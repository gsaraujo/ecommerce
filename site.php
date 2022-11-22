<?php


use \Hcode\Page;
use \Hcode\Model\Category;
use \Hcode\Model\Product;
use \Hcode\Model\Cart;
use \Hcode\Model\Address;
use Hcode\Model\User;

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

	$page->setTpl("cart", [
		'cart'=>$cart->getValues(),
		'products'=>$cart->getProducts(),
		'error'=>Cart::getMsgError()
	]);

});

//add product to cart
$app->get('/cart/:idproduct/add', function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);	

	$cart = Cart::getFromSession();

	$qtd = (isset($_GET['qtd'])) ? (int)$_GET['qtd']: 1;

	for ($i=0; $i < $qtd ; $i++) { 
		$cart->addProduct($product);
	}	

	header("Location: /cart");
	exit;

});

//remove product from cart
$app->get('/cart/:idproduct/minus', function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");
	exit;

});

//add product to cart
$app->get('/cart/:idproduct/remove', function($idproduct) {

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	header("Location: /cart");
	exit;

});

//calculating the freight
$app->post("/cart/freight", function() {

	$cart = Cart::getFromSession();

	$cart->setFreight($_POST['zipcode']);

	header("Location: /cart");
	exit;

});

//checking out the cart
$app->get("/checkout", function() {

	$user = User::getFromSession();

	//var_dump($user->getdesperson());

	//exit;

	User::verifyLogin(false);

	$cart = Cart::getFromSession();

	$address = new Address();

	$page = new Page();

	$page->setTpl("checkout", [
		'cart'=>$cart->getValues(),
		'address'=>$address->getValues()
	]);


});

//user login page
$app->get("/login", function() {

	$page = new Page();

	$page->setTpl("login", [
		'error'=>User::getMsgError()
	]);


});

//user login
$app->post("/login", function() {

	try {

		User::login($_POST['login'], $_POST['password']);

	} catch(Exception $e) {
		
		User::setMsgError($e->getMessage());

	}
	

	header("Location: /checkout");
	exit;


});

$app->get("/logout", function() {

	User::logout();

	header("Location: /login");
	exit;


});

?>