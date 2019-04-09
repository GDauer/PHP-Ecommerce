<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Cart;
use \Hcode\Model\Address;
use \Hcode\Model\User;
use \Hcode\Model\Order;
use \Hcode\Model\OrderStatus;

$app->get("/cart",function() {

    $cart = Cart::getFromSession();

    $page = new Page();

    $page->setTpl("cart", [
        'cart'=>$cart->getValues(),
        'products'=>$cart->getProducts(),
        'error'=>Cart::getMsgError()
    ]);

});

$app->get("/cart/:idproduct/add",function($idproduct) {

    $product = new Product();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();

    $cart->addProduct($product);

    header("Location: /cart");
    exit;

});

$app->get("/cart/:idproduct/minus",function($idproduct) {

    $product = new Product();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product);

    header("Location: /cart");
    exit;

});

$app->get("/cart/:idproduct/remove",function($idproduct) {

    $product = new Product();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product, true);

    header("Location: /cart");
    exit;

});

$app->post('/cart/freight', function (){

    $cart = Cart::getFromSession();

    $cart->setFreight($_POST['zipcode']);

    header("Location: /cart");
    exit;

});

$app->get("/checkout", function () {

    User::verifyLogin(false);

    $address = new Address();

    $cart = Cart::getFromSession();

    if(isset($_GET['zipcode'])){

        $_GET['zipcode'] = $cart->getdeszipcode();

    }

    if(isset($_GET['zipcode'])) {

        $address->loadFromCEP($_GET['zipcode']);

        $cart->setdeszipcode($_GET['zipcode']);

        $cart->save();

        $cart->getCalculateTotal();
    }

    if(!$address->getdesaddress()) {$address->setdesaddress('');}
    if(!$address->getdesnumber()) {$address->setdesnumber('');}
    if(!$address->getdescomplement()) {$address->setdescomplement('');}
    if(!$address->getdesdistrict()) {$address->setdesdistrict('');}
    if(!$address->getdescity()) {$address->setdescity('');}
    if(!$address->getdesstate()) {$address->setdesstate('');}
    if(!$address->getdescountry()) {$address->setdescountry('');}
    if(!$address->getdeszipcode()) {$address->setdeszipcode('');}

    $page = new Page();

    $page->setTpl("checkout", [
        'cart'=>$cart->getValues(),
        'address'=>$address->getValues(),
        'products'=>$cart->getProducts(),
        'error'=>Address::getMsgError()
    ]);

});

$app->post('/checkout', function () {

    User::verifyLogin(false);

    if(!isset($_POST['zipcode']) || $_POST['zipcode'] === '') {

        Address::setMsgError("Informe o CEP");
        header("Location: /checkout");
        exit;

    }

    if(!isset($_POST['desaddress']) || $_POST['desaddress'] === '') {

        Address::setMsgError("Informe o endereço");
        header("Location: /checkout");
        exit;

    }

    if(!isset($_POST['desdistrict']) || $_POST['desdistrict'] === '') {

        Address::setMsgError("Informe o bairro");
        header("Location: /checkout");
        exit;

    }

    if(!isset($_POST['descity']) || $_POST['descity'] === '') {

        Address::setMsgError("Informe a cidade");
        header("Location: /checkout");
        exit;

    }

    if(!isset($_POST['desstate']) || $_POST['desstate'] === '') {

        Address::setMsgError("Informe o estado p.ex: SP");
        header("Location: /checkout");
        exit;

    }

    if(!isset($_POST['descountry']) || $_POST['descountry'] === '') {

        Address::setMsgError("Informe o país");
        header("Location: /checkout");
        exit;

    }

    if(!isset($_POST['desnumber']) || $_POST['desnumber'] === '') {

        Address::setMsgError("Informe o número");
        header("Location: /checkout");
        exit;
    }


    $user = User::getFromSession();

    $address = new Address();

    $_POST['deszipcode'] = $_POST['zipcode'];
    $_POST['idperson'] = $user->getidperson();

    $address->setData($_POST);

    $address->save();

    $cart = Cart::getFromSession();

    $cart->getCalculateTotal();

    $order = new Order();

    $order->setData([
        'idcart'=>$cart->getidcart(),
        'idaddress'=>$address->getidaddress(),
        'iduser'=>$user->getiduser(),
        'idstatus'=>OrderStatus::EM_ABERTO,
        'vltotal'=>$cart->getvltotal()
    ]);

    $order->save();

    switch ((int)$_POST['payment-method']) {
        case 1:
            header("Location: /order/". $order->getidorder() . "/pagseguro");
            break;
        case 2:
            header("Location: /order/". $order->getidorder() . "/paypal");
            break;
        case 3:
            header("Location: /boleto/". $order->getidorder());
            break;
    }
    exit;



});

$app->get('/order/:idorder/pagseguro', function ($idorder) {

    User::verifyLogin(false);

    $order = new Order();

    $order->get((int)$idorder);

    $cart = $order->getCart();

    $page = new Page([
        'header'=>false,
        'footer'=>false
    ]);

    //var_dump($order->getValues()); exit;

    $page->setTpl('payment-pagseguro', [
        'order'=>$order->getValues(),
        'cart'=>$cart->getValues(),
        'products'=>$cart->getProducts(),
        'phone'=>[
            'areaCode'=>substr($order->getnrphone(), 0, 2),
            'number'=>substr($order->getnrphone(), 2, strlen($order->getnrphone()))
        ]
    ]);

});

$app->get('/order/:idorder/paypal', function ($idorder) {

    User::verifyLogin(false);

    $order = new Order();

    $order->get((int)$idorder);

    $cart = $order->getCart();

    $page = new Page([
        'header'=>false,
        'footer'=>false
    ]);

    //var_dump($order->getValues()); exit;

    $page->setTpl('payment-paypal', [
        'order'=>$order->getValues(),
        'cart'=>$cart->getValues(),
        'products'=>$cart->getProducts()
    ]);

});

$app->get('/order/:idorder', function ($idorder) {

    User::verifyLogin(false);

    $order = new Order();

    $order->get((int)$idorder);

    $page = new Page();

    $page->setTpl("payment", [
        'order'=>$order->getValues()
    ]);
});

$app->get('/boleto/:idorder', function ($idorder) {

    User::verifyLogin(false);

    $order = new Order();

    $order->get((int)$idorder);


    // DADOS DO BOLETO PARA O SEU CLIENTE
    $dias_de_prazo_para_pagamento = 10;
    $taxa_boleto = 5.00;
    $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
    $valor_cobrado =$order->getvltotal(); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
    $valor_cobrado = str_replace(",", ".",$valor_cobrado);
    $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

    $dadosboleto["nosso_numero"] = $order->getidorder();  // Nosso numero - REGRA: Máximo de 8 caracteres!
    $dadosboleto["numero_documento"] = $order->getidorder();	// Num do pedido ou nosso numero
    $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
    $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
    $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
    $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
    $dadosboleto["sacado"] = utf8_encode($order->getdesperson());
    $dadosboleto["endereco1"] = $order->getdesaddress() . " " . $order->getdesdistrict();
    $dadosboleto["endereco2"] = utf8_encode($order->getdescity()) . " - " . $order->getdesstate() . " - " . $order->getdescountry() ." - CEP: " . $order->getdeszipcode();

// INFORMACOES PARA O CLIENTE
    $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Webjump! E-commerce";
    $dadosboleto["demonstrativo2"] = "Taxa bancária - R$ 0,00";
    $dadosboleto["demonstrativo3"] = "";
    $dadosboleto["instrucoes1"] = "- Sr(a). Caixa, cobrar multa de 2% após o vencimento";
    $dadosboleto["instrucoes2"] = "- Receber até 15 dias após o vencimento";
    $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: suporte@webjump.com.br";
    $dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto Loja Hcode e Webjump! E-commerce - www.hcode.com.br e www.webjump.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
    $dadosboleto["quantidade"] = "";
    $dadosboleto["valor_unitario"] = "";
    $dadosboleto["aceite"] = "";
    $dadosboleto["especie"] = "R$";
    $dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - ITAÚ
    $dadosboleto["agencia"] = "1690"; // Num da agencia, sem digito
    $dadosboleto["conta"] = "48781";	// Num da conta, sem digito
    $dadosboleto["conta_dv"] = "2"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - ITAÚ
    $dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

// SEUS DADOS
    $dadosboleto["identificacao"] = "Webjump! informática ltda";
    $dadosboleto["cpf_cnpj"] = "09.053.395/0001-65";
    $dadosboleto["endereco"] = "Av. Pres. Juscelino Kubitschek, 50 - 7º andar - Vila Nova Conceição, São Paulo - SP, 04543-000";
    $dadosboleto["cidade_uf"] = "São Paulo - SP";
    $dadosboleto["cedente"] = "WEBJUMP DESIGNER E INFORMÁTICA LTDA - SP";

// NÃO ALTERAR!
    $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "boletophp" . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR;

    require_once ($path . "funcoes_itau.php");
    require_once ($path . "layout_itau.php");

});

?>