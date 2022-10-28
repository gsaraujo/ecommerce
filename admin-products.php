<?php

use \Hcode\Model\Product;
use \Hcode\Model\User;
use \Hcode\PageAdmin;


//List products
$app->get("/admin/products", function(){

    User::verifyLogin();

    $products =  Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products",[
        "products"=>$products
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