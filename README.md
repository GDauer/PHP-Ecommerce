# Implementação E-Commerce #
Um pequeno projeto de implementação de um site de e-commerce para testar conhecimentos na linguagem php.
Esse projeto está sendo feito em conjunto com o "curso completo de php7" da hcodebr para o treinamento do novo time de estagiários da <strong>WebJump!</strong>

## Site da empresa:
http://www.webjump.com.br/

## Instalação
> É recomendado que você tenha um ambiente de testes para validar alterações e atualizações antes de atualizar sua loja em produção.

> Existem algumas lib e recursos que ainda não foram adicionadas ao composer.json, elas são: cURL, openssl e a GD library.

> A instalação do módulo é feita utilizando o Composer. Para baixar e instalar o Composer no seu ambiente acesse https://getcomposer.org/download/
 

### Pré-requisitos ###
* Php 7.x
* Virtual host (apache ou nginx)
* Banco MySQL
* OS Unix (de preferencia, alguns recursos podem não funcionar corretamente no windows)
* Composer


### Estágio Webjump! ###
Este projeto está sendo desenvolvido para o treinamento do time de estagiários da <strong>WebJump!</strong> Mas, sinta-se livre para o usar o código fonte e sugerir melhorias :)

-------

### Linkedin ###
https://www.linkedin.com/in/gustavo-vicente-dauer/


------

## Classes ##
Este projeto utiliza a forma DAO para organização das classes e seus respectivos métodos. As classes são:

* <strong>Vendor -> Hcodebr -> php-classes -> src -> DB -> Sql.php</strong>

A classe SQL é responsável pela comunicação com o banco de dados, e, fazer o bind params de forma dinâmica. A diferença entre os métodos "query" e "select" está no retorno de dados, visto que o método "query" é um método void

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model.php</strong>

Essa é uma classe abstrata, responsável por conter os métodos setData que carrega seus filhos com um array de valores. Também é responsável por fazer os métodos set e get de forma dinâmica.

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Mailer.php</strong>

Classe responsável pelo envio de e-mail da rota forgot

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Page.php</strong>

Classe responsável por fazer o merge entre o views e views cache atráves da classe pai raintpl

* <strong>Vendor -> Hcodebr -> php-classes -> src -> PageAdmin.php</strong>

Mesma função da page, mudando apenas o tipo de template a ser carregado. Extende da classe pai <strong>Page.php</strong>

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> Address.php </strong>

Essa classe é responsável por se comunicar com o webservice <strong>"Via CEP"</strong>, fazer o carregamento do cep informado aplicando o frete com o <strong>webservice da Sedex</strong> <i>(a comunicação com o webservice da sedex é feita pela classe Cart.php)</i>

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> Category.php </strong>

Classe responsável por gerenciar os CRUD da categoria, e, atribuir <strong>n</strong> produtos a mesma.
Possui métodos para paginação.

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> Order.php </strong>

Classe responsável por gerenciar o carrinho e pedidos dos usuários cadastrados.
Possui métodos para paginação.

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> OrderStatus.php </strong>

Possui apenas um método, é uma classe final que serve apenas para gerenciar os status dos pedidos (EM ABERTO, AGUARDANDO PAGAMENTO, PAGO, ENTREGUE)

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> Product.php </strong>

Classe responsável pelo CRUD de produtos, traser a wishlist atrelado ao usuário, categoria e o carrinho. 
Possui métodos para paginação,
<strong>OBSERVAÇÃO:</strong> Essa classe necessita de uma tabela contendo as relações entre <strong>REVIEW n-1 PRODUTOS.</strong>

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> User.php </strong>

Classe responsável pelo CRUD de usuários e validação de login (admin ou não). 
Possui método para paginação, forgot password via classe Mailer.php, get Orders e o captch do google.

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> Wishlist.php </strong>

Classe responsável pelo CRUD da lista de desejos 
<strong>OBSERVAÇÃO:</strong> Essa classe necessita de uma tabela contendo as relações entre <strong>USUÁRIO 1-n PRODUTOS.</strong> 

* <strong>Vendor -> Hcodebr -> php-classes -> src -> Model -> Cart.php </strong>

É a classe responsável por manipular o cart do usuário, seja um usuário cadastrado ou não (via $_SESSION) e por se comunicar com o webservice da sedex através do 

```
"http_build_query"
```
E

```ext
"simplexml_load_file"
```
------

# Telas: #

------

### Login Admin ###
![la](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/loginAdminn.png)

### Login Usuário ###
![lu](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/loginUser.png)

### Index ###
![i1](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/index01.png)
![i2](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/index02.png)

### Produtos ###
![p](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/products.png)

### Wishlist ###
![w](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/wishlist.png)

### Cart ###
![ca](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/cart.png)

### Checkout ###
![ch1](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/checkout01.png)
![ch2](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/checkout02.png)

### Painel Admin ###
![pa](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/pannelAdmin.png)

### Detalhe de Pedidos Admin ###
![dpa](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/detailsOrdersAdmin01.png)
![dpa2](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/detailsOrdersAdmin02.png)

### Detalhe de Pedidos Usuários ###
![dpu](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/detailsOrders.png)

### Detalhe de Produto ###
![dp](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/detailProduct.png)

### Forgot Password ###
![fp](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/forgotUser.png)
![fpa](https://github.com/GDauer/PHP-Ecommerce/blob/master/git/forgotAdmin.png)
