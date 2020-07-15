<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>e.conomize | Como comprar</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>/style/css/minified-main.css">
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css">
</head>
<body>
    <div class="l-wrapper_FiltroPesq">
        <div class="l-topNavFiltroPesq" id="topNav">
        <?php
            include('__system__/functions/includes/topNav.php');
        ?>    
        </div>

        <nav class="l-headerNav" id="headerNav">
        <?php
            include('__system__/functions/includes/header.php');
        ?>
        </nav>

        <div class="l-bottomNav" id="bottomNav">
        <?php
            include('__system__/functions/includes/bottom.php');
        ?>
        </div>

        <div class="l-mainFiltroPesq">
            <div class="divPageHowToBuy">
                <h2>Comprar na e.conomize? Sem K.O.</h2>
                <p class="textPageHowToBuy">Fazer compras aqui na <b>e.conomize</b> é muito fácil! Fique ligado nesses 3 simples passos para que seus produtos cheguem aonde você quiser, ou quase hahaha.</p>
                <hr class="HowToBuyLink">
            </div>
            <img class="cartImgPageHowToBuy" src="<?= Project::baseUrl(); ?>style/img/banner/cartPurpleBorder.png">
            <div class="Step0DivPageHowToBuy">
                <h2>1 - Faça o cadastro ou esteja logado na sua conta</h2>
                <p class="Step0TextPageHowToBuy">Caso seja novo entre nós, realize o seu cadastro, é bem rápido! Se já for da casa, certifique-se que está logado. Fazendo parte da nossa família é o único jeito de poder desfrutar de nossas imperdíveis vantagens.</p>
                <hr class="HowToBuyLink">
                <img class="purplearrow" src="<?= Project::baseUrl(); ?>style/img/banner/cutpurplearrow.png" alt="">
            </div>
            <img class="Step0ImgPageHowToBuy" src="<?= Project::baseUrl(); ?>style/img/banner/loginPurpleBorder.png">
            <div class="Step1DivPageHowToBuy">
                <h2>2 - Adicione os produtos de sua escolha no carrinho de compras</h2>
                <p class="Step1TextPageHowToBuy">Fique a vontade e explore toda a variedade que a <b>e.conomize</b> proporciona aos seu clientes. Sempre que encontrar o produto que procura, basta adiciona-lo ao seu carrinho de compras. Para visualizar os produtos já adicionados, acesse o carrinho clicando no botão na parte suprior direita no topo de toda página do site.</p>
                <hr class="HowToBuyLinkSpecial">
                <img class="opositepurplearrow" src="<?= Project::baseUrl(); ?>style/img/banner/cutpurplearrow.png" alt="">
            </div>
            <img class="Step1ImgPageHowToBuy" src="<?= Project::baseUrl(); ?>style/img/banner/addcart.png">
            <div class="Step0DivPageHowToBuy">
                <h2>3 - Complete os processos de compra e efetue o pagamento</h2>
                <p class="Step0TextPageHowToBuy">Este passo é onde você define os produtos a serem comprados, determina o endereço de entrega e agenda o horário que melhor se encaixa na sua rotina. Após efetuar o pagamento, basta esperar... sua compra já está sendo processada!</p>
                <hr class="HowToBuyLink">
            </div>
            <img class="Step0ImgPageHowToBuy" src="<?= Project::baseUrl(); ?>style/img/banner/pay.png">
            
        </div>
            
        <?php
            include('__system__/functions/includes/modal.php');
        ?>
        
        <div class="l-footerFiltroPesq" id="footer">
        <?php
            include('__system__/functions/includes/footer.php');
        ?>
        </div>
        <div class="l-footerBottomFiltroPesq" id="footerBottom">
        <?php
            include('__system__/functions/includes/bottomFooter.html');
        ?>
        </div>
    </div>

    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
</body>
</html>