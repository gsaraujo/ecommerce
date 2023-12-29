<?php

use \Hcode\Model\Product;
use \Hcode\Model\User;
use \Hcode\PageAdmin;


//List products
$app->get("/admin/products", function(){

    User::verifyLogin();

    $search = (isset($_GET['search'])) ? $_GET['search'] : "";
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

    if ($search != '') {

        $pagination = Product::getProductsPageSearch($search,$page);

    } else {
        $pagination = Product::getProductsPage($page);

    }

    $pages = [];

    for ($x = 0; $x < $pagination['pages']; $x++)
    {
        array_push($pages, [
            'href'=>'/admin/products?' . http_build_query([
                'page'=>$x+1,
                'search'=>$search
            ]),
            'text'=>$x+1
        ]);
    }    

    $page = new PageAdmin();

    $page->setTpl("products",[
        "products"=>$pagination['data'],
        "search"=>$search,
        "pages"=>$pages
    ]);

});

//open the page to create a new product
$app->get("/admin/products/create", function(){

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");

});

//saves the new product on the database
$app->post("/admin/products/create", function(){

    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);

    $product->save();

    header("Location: /admin/products");
    exit;
    
});

//Open a product for edit
$app->get("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproduct);

    $page = new PageAdmin();

    $page->setTpl("products-update",[
        'product'=>$product->getValues()
    ]);

});


//saves the edition on the product
$app->post("/admin/products/:idproduct", function($idproduct){

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproduct);

    $product->setData($_POST);

    $product->save();
    
    $product->setPhoto($_FILES["file"]);

    header('Location: /admin/products');
    exit;

});

//delete product
$app->get("/admin/products/:idproduct/delete", function($idproduct){

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproduct);

    $product->delete();

    header('Location: /admin/products');
    exit;

});



?>