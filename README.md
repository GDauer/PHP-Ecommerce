# Implementação E-Commerce #
Um pequeno projeto de implementação de um site de e-commerce para testar conhecimentos na linguagem php.
Esse projeto está sendo feito em conjunto com o "curso completo de php7" da hcode para o treinamento do novo time de estagiários da <strong>WebJump!</strong>

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
- <strong>Vendor -> Hcodebr -> php-classes -> src -> DB -> Sql.php</strong>
A classe SQL é responsável pela comunicação com o banco de dados, e, fazer o bind params de forma dinâmica. A diferença entre os métodos "query" e "select" está no retorno de dados, visto que o método "query" é um método void

- <strong>Vendor -> Hcodebr -> php-classes -> src -> Model.php</strong>
Essa é uma classe abstrata, responsável por conter os métodos setData que carrega seus filhos com um array de valores. Também é responsável por fazer os métodos set e get de forma dinâmica.

- <strong>Vendor -> Hcodebr -> php-classes -> src -> Mailer.php</strong>
Classe responsável pelo envio de e-mail da rota forgot

- <strong>Vendor -> Hcodebr -> php-classes -> src -> Page.php</strong>
Classe responsável por fazer o merge entre o views e views cache atráves da classe pai raintpl

- <strong>Vendor -> Hcodebr -> php-classes -> src -> PageAdmin.php</strong>
Mesma função da page, mudando apenas o tipo de template a ser carregado

------

------

# Telas: #

------

### Login Admin ###
![la](https://github.com/GDauer/ecommerce/blob/master/git/loginAdminn.png)

### Login Usuário ###
![lu](https://github.com/GDauer/ecommerce/blob/master/git/loginUser.png)

### Index ###
![i1](https://github.com/GDauer/ecommerce/blob/master/git/index01.png)
![i2](https://github.com/GDauer/ecommerce/blob/master/git/index02.png)

### Produtos ###
![p](https://github.com/GDauer/ecommerce/blob/master/git/products.png)

### Wishlist ###
![w](https://github.com/GDauer/ecommerce/blob/master/git/wishlist.png)

### Cart ###
![ca](https://github.com/GDauer/ecommerce/blob/master/git/cart.png)

### Checkout ###
![ch1](https://github.com/GDauer/ecommerce/blob/master/git/checkout01.png)
![ch2](https://github.com/GDauer/ecommerce/blob/master/git/checkout02.png)

### Painel Admin ###
![pa](https://github.com/GDauer/ecommerce/blob/master/git/pannelAdmin.png)

### Detalhe de Pedidos Admin ###
![dpa](https://github.com/GDauer/ecommerce/blob/master/git/detailsOrdersAdmin01.png)
![dpa2](https://github.com/GDauer/ecommerce/blob/master/git/detailsOrdersAdmin02.png)

### Detalhe de Pedidos Usuários ###
![dpu](https://github.com/GDauer/ecommerce/blob/master/git/detailsOrders.png)

### Detalhe de Produto ###
![dp](https://github.com/GDauer/ecommerce/blob/master/git/detailProduct.png)

### Forgot Password ###
![fp](https://github.com/GDauer/ecommerce/blob/master/git/forgotUser.png)
![fpa](https://github.com/GDauer/ecommerce/blob/master/git/forgotAdmin.png)
