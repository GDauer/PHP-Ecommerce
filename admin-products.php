<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;

$app->get("/admin/products", function () {

    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", [

        "products"=>$products
    ]);

});

$app->get("/admin/products/create", function () {

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");

});

$app->post("/admin/products/create", function () {

    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);

    $product->save();
    
    header("Location: /admin/products");
    exit;
});

$app->get("/admin/products/:idproducts", function ($idproducts) {

    User::verifyLogin();

    $product = new Product();
    
    $product->get((int)$idproducts);
    
    $page = new PageAdmin();

    $page->setTpl("products-update", [
        "product"=>$product->getValues()
    ]);

});

$app->post("/admin/products/:idproducts", function ($idproducts) {

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproducts);

    $product->setData($_POST);

    $product->save();

    $product->setPhoto($_FILES["file"]);

    header("Location: /admin/products");
    exit;
});

$app->get("/admin/products/:idproducts/delete", function ($idproducts) {

    User::verifyLogin();

    $product = new Product();

    $product->get((int)$idproducts);

    $product->delete();

    header("Location: /admin/products");
    exit;

});

?>