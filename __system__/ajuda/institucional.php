<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>e.conomize | Institucional</title>
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
            <h2 class="defaultTitle">INSTITUCIONAL</h2>
            <div class="obj I">
                <h3>QUEM SOMOS</h3>
                <img src="<?= Project::baseUrl(); ?>style/img/banner/startup-593296_640.jpg" alt="Equipe e.conomize">
            </div>
            <p class="parag elementParag1"> A <span style="color:#9C45EB;"><b>e.conomize</b></span> é uma das empresas mais visionárias da atualidade. Somos um <b>mercado 100% digital</b> que inova na maneira de vender produtos pela internet e no jeito de se relacionar com seus clientes. Somos uma empresa jovem com o espírito de um jovem, criativa, destemida e sonhadora, ao mesmo passo que, somos compromissados com as responsabilidades envolvidas em oferecer um serviço de qualidade e excelência aos nossos tão amados clientes. Gostamos muito de lidar com pessoas, por isso consideramos que nossos clientes são nossa maior conquista e maior prioridade. Sendo assim, <b>NÓS</b>, somos eu, você, e todos aqueles que todos os dias trabalham para que este projeto mantenha-se como uma realidade positiva na vida de muitas pessoas.
            </p>
            <div class="obj II">
                <h3>NOSSO OBJETIVO</h3>
                <img src="<?= Project::baseUrl(); ?>style/img/banner/desk-3139127_640.jpg" alt="Equipe e.conomize">
            </div>
            <p class="parag elementParag2">Nosso maior objetivo é possibilitar que as pessoas possam aproveitar mais a vida, sim a vida, porque nós não apenas vendemos produtos, nós vendemos tempo. Através desse pensamento, criamos essa plataforma onde nossos clientes podem, por meio de alguns poucos 'clicks', fazer compras e agenda-las, para que sejam entregues quando e aonde quiserem, esse é o nosso propósito. O grande desafio foi garantir que a tecnologia tornasse o processo simples, seguro e rápido. E conseguimos! Esqueça as antigas necessidades de ter que se deslocar sempre que precisar comprar alguma coisinha no mercado. Agora é o futuro, que já começou. Estamos aqui, prontos para te atender, seja dia ou seja noite, <span style="color:#9C45EB;"><b>e.conomize</b></span> em tudo, menos em viver.
            </p>
            <div class="obj III">
                <h3>A FAMÍLIA E.CONOMIZE!</h3>
                <img src="<?= Project::baseUrl(); ?>style/img/banner/adult-2449725_640.jpg" alt="Equipe e.conomize">
            </div>
            <p class="parag elementParag3">Este projeto não seria possível sem a colaboração de muitas pessoas. Nós agradecemos, profundamente, nossas famílias por todo o apoio dado, nossos amigos por toda admiração recebida, e sobretudo, todos os professores que fizeram parte desta nossa empreitada. Vocês <b>SÃO</b> a equipe <span style="color:#9C45EB;"><b>e.conomize</b></span>. Cada conselho dado, cada incentivo dirijido, cada palavra dissertada, cada momento vivido. Sempre levaremos conosco. Somos gratos pela <b>ETEC DE LINS</b>, por proporcionar, a nós jovens, um espaço para a expansão de conhecimento, que despertou, em cada um de nós, a fome do crescimento pessoal. Não foi fácil, nem disseram que seria. Mas aqui está o resultado.
            </p>
            <div class="fotoDaTurma">
                <h4>"Somos eternos aprendizes e esse é só o nosso primeiro passo"</h4>
                <h6>Turma WEB, ETEC de Lins - 2019</h6>
                <img src="<?= Project::baseUrl(); ?>style/img/banner/fotodaturma.jpeg" alt="">
            </div>
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