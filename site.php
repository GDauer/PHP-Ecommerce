<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Cart;
use \Hcode\Model\User;
use \Hcode\Model\Wishlist;


$app->get('/', function() { //rota 1

    $products = Product::listAll();

    $page = new Page();

    $page->setTpl("index", [
        "products"=>Product::checkList($products)
    ]);


});

$app->post('/cancel', function () {

    Cart::removeFromSession();

    session_regenerate_id();

    header("Location: /");
    exit;

});

$app->get("/login", function () {

    $page = new Page();

    $page->setTpl("login", [
        'error'=>User::getError(),
        'errorRegister'=>User::getErrorRegister(),
        'registerValues'=>(isset($_SESSION['registerValues'])) ? $_SESSION['registerValues'] : [
            'name'=>'', 'email'=>'', 'phone'=>'']
        ]);

});

$app->post("/login", function () {

    try {

        User::login($_POST['login'], $_POST['password']);

    } catch (Exception $e) {

        User::setError($e->getMessage());

    }

    header("Location: /checkout");
    exit;

});

$app->get("/logout", function (){

    User::logout();

    Cart::removeFromSession();

    session_regenerate_id();

    header("Location: /");
    exit;

});

$app->post("/register", function (){

    $_SESSION['registerValues'] = $_POST;

    if(!isset($_POST['name']) || $_POST['name'] == '') {

        User::setErrorRegister("Preencha o seu nome");
        header("Location: /login");
        exit;

    }

    if(!isset($_POST['email']) || $_POST['email'] == '') {

        User::setErrorRegister("Preencha o seu e-mail");
        header("Location: /login");
        exit;

    }

    if(!isset($_POST['password']) || $_POST['password'] == '') {

        User::setErrorRegister("A senha não pode ficar em branco");
        header("Location: /login");
        exit;

    }

    if(User::checkLoginExists($_POST['email']) == true) {

        User::setErrorRegister("Este endereço de e-mail já está sendo usado por outro usuário");
        header("Location: /login");
        exit;

    }

    $user = new User();

    if($user->captcha() !== true){

        User::setErrorRegister("Confirme que você não é um robô!");
        header("Location: /login");
        exit;
    }


    $user->setData([
        'inadmin'=>0,
        'deslogin'=>$_POST['email'],
        'desperson'=>$_POST['name'],
        'desemail'=>$_POST['email'],
        'despassword'=>$_POST['password'],
        'nrphone'=>$_POST['phone']
    ]);

    $user->save();

    User::login($_POST['email'], $_POST['password']);

    header("Location: /checkout");
    exit;

});

$app->get('/forgot', function () {

    $page = new Page();

    $page->setTpl('forgot');

});

$app->post('/forgot', function () {

    $user = User::getForgot($_POST['email'], false);

    header("Location: /forgot/sent");
    exit;

});

$app->get('/forgot/sent', function () {

    $page = new Page();

    $page->setTpl('forgot-sent');

});

$app->get('/forgot/reset', function () {

    $user = User::validForgotDecrypt($_GET['code']);

    $page = new Page();

    $page->setTpl('forgot-reset', array(
        "name"=>$user['desperson'],
        "code"=>$_GET['code']
    ));

});

$app->post('/forgot/reset', function () {

    $forgot = User::validForgotDecrypt($_POST['code']);

    User::setForgotUsed($forgot['idrecovery']);

    $user = new User();

    $user->get((int)$forgot["iduser"]);

    $password = User::getPasswordHash($_POST['password']);

    $user->setPassword($password);

    $page = new Page();

    $page->setTpl('forgot-reset-success');

});

$app->get('/products', function () {

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

    $products = new Product();

    $pagination = $products->getPage($page);

    $pages = [];

    for ($i = 1; $i <= $pagination['pages']; $i++) {
        array_push($pages, [
            'link'=>'/products' . $products->getidproduct() . '?page=' . $i,
            'page'=>$i
        ]);

    }


    $page = new Page();

    $page->setTpl("products", [
        "products"=>$pagination["data"],
        "pages"=>$pages
    ]);
});

$app->get('/wishlist', function (){

    User::verifyLogin(false);

    $user = User::getFromSession();

    //$product = new Product();

    //$list = Wishlist::getFromUser($user->getiduser());

    $products = Product::getWishlist($user->getiduser());

//
//    $products = array();
//
//    $count = count($list);
//
//    for($i = 0; $i < $count; $i++)
//    {
//        $id = $list[$i]['idproducts'];
//
//        $product->get((int)$id);
//        $products[$i] =(array) $product->getValues();
//
//    }

    $page = new Page();

    $page->setTpl("wishlist", [
        'products'=>$products
    ]);

});

$app->get('/wishlist/:idproducts/:iduser/save', function ($idproducts, $iduser){

    User::verifyLogin(false);

    $list = new Wishlist();

    $product= new Product();

    $product->get((int)$idproducts);

    $list->setData([
       'idproducts'=>$idproducts,
       'iduser'=>$iduser
    ]);

    $list->save();

    header("Location: /products/" . $product->getdesurl());
    exit;

});

$app->get('/wishlist/:idproducts/:idlist/remove', function ($idproducts, $idlist){

    User::verifyLogin(false);

    $list = new Wishlist();

    $product= new Product();

    $product->get((int)$idproducts);

    $list->get((int)$idlist);

    $list->remove();

    header("Location: /products/" . $product->getdesurl());
    exit;

});

$app->get("/products/:desurl",function($desurl) {

    $product = new Product();

    $product->getFromURL($desurl);

    $user = User::getFromSession();

    //var_dump(Product::getAvail($desurl)); exit;

//    $list = new Wishlist();

    $list = Wishlist::getFromUserProducts($user->getiduser(), $product->getidproduct());

    $inlist = ($list['idlist'] !== '' && $list['idlist'] !== null) ? $inlist = true : $inlist = false;

    $page = new Page();

    $page->setTpl("product-detail", [
        "product"=>$product->getValues(),
        "categories"=>$product->getCategories(),
        "user"=>$user->getValues(),
        "list"=>$list['idlist'],
        "inlist"=>$inlist,
        "aval"=> Product::getAvail($product->getidproduct()),
        "error"=>User::getError()
    ]);

});

$app->post('/avail/:desurl', function ($desurl) {

    $product = new Product();

    $product->getFromURL($desurl);

  if(!isset($_POST['name']) || $_POST['name'] === '' || $_POST['name'] === null) {

      $_POST['name'] = 'Anônimous';

  }

    if(!isset($_POST['email']) || $_POST['email'] === '' || $_POST['email'] === null) {

        $_POST['email'] = 'Anônimous';

    }

    if(!isset($_POST['review']) || $_POST['review'] === '' || $_POST['review'] === null) {

        header("Location: /products/$desurl");
        User::setError("O campo do review não pode ficar em branco!");
        exit;

    }

    Product::setAvail($product->getidproduct());

    header("Location: /products/$desurl");
    exit;

});

