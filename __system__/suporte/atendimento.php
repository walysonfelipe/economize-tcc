<?php
    use Model\User;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Atendimento online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>/style/css/minified-main.css"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css"/>
</head>
<body>
    <div class="l-wrapper_FiltroPesq">

        <div class="l-topNav" id="topNav">
        <?php
            include('__system__/functions/includes/topNav.php');
        ?>    
        </div>
        <nav class="l-headerNav" id="headerNav">
        <?php
            include('__system__/functions/includes/header.php');
        ?>
        </nav>
        
        <div class="l-main">
            <h2 align="center" class="tituloOfertas"><i class="fas fa-headset"></i> CENTRAL DE ATENDIMENTO</h2>
            <div class="form_atd divFormContactAttendance">
            <h3 class="titleDivContactPage">ENVIE UM FEEDBACK</h3>
                <form id="form-atd">
                    <div class="input-form">
                        <label>NOME:</label><br>
                        <input type="text" value="<?= isset($_SESSION[User::SESSION]) ? $_SESSION[User::SESSION]['usu_first_name'] . " " . $_SESSION[User::SESSION]['usu_last_name'] : "" ; ?>" id="name_usu" name="name_usu"/>
                        <div class="help-block"></div>
                    </div>
                    <div class="input-form">
                        <label>EMAIL:</label><br>
                        <input type="text" value="<?= isset($_SESSION[User::SESSION]) ? $_SESSION[User::SESSION]['usu_email'] : "" ; ?>" id="email_usu" name="email_usu"/>
                        <div class="help-block"></div>
                    </div>
                    <div class="input-form">
                        <label>CONTEÚDO DA MENSAGEM:</label><br>
                        <select name="opt" id="opt">
                            <option value="*000*"> -- Selecione um --</option>
                            <option value="compra">Compra</option>
                            <option value="carrinho de compra">Carrinho de Compra</option>
                            <option value="armazem">Armazéns</option>
                            <option value="entrega">Entrega</option>
                            <option value="cidade indisponivel">Cidade Indisponivel</option>
                            <option value="cadastro">Cadastro</option>
                            <option value="login">Login</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                    <div class="input-form">
                        <label>MENSAGEM:</label><br>
                        <textarea id="txt_usu" name="txt_usu"></textarea>
                        <div class="help-block"></div>
                    </div>
                    <div class="input-form">
                        <button class="btnDivFormContactAttendance" id="btnAtend" type="submit">ENVIAR</button>
                        <div class="help-block"></div>
                    </div>
                </form>
            </div>
            <div class="divFormContactInformation">
                <h3 class="titleDivContactPage">INFORMAÇÕES DE CONTATO</h3>
                <div class="textDivFormContactInformation">
                    <p>Qualquer dúvida ou pergunta que queira fazer fique a vontade. Temos uma equipe pronta para atendê-lo toda vez que achar necessário.</p>
                </div>
                <div class="infoDivFormContactInformation">
                    <p class="paragLineContactPage"><i class="fas fa-mobile-alt"></i> &nbsp;(14) 99999-9999</p>
                    <p class="paragLineContactPage"><i class="far fa-envelope"></i> &nbsp;economize.contato@gmail.com</p>
                    <p>
                        <a class="linkSocialMediaContactPage" href="#"><i class="fab fa-facebook-square"></i></a> 
                        &nbsp; 
                        <a class="linkSocialMediaContactPage" href="#"><i class="fab fa-twitter-square"></i></a>
                        &nbsp;
                        <a class="linkSocialMediaContactPage" href="#"><i class="fab fa-instagram"></i></a>
                        &nbsp;
                        <a class="linkSocialMediaContactPage" href="#"><i class="fab fa-youtube-square"></i></a>
                        &nbsp;
                    </p>
                </div>
            </div>
        </div>

        <?php
            include('__system__/functions/includes/modal.php');
        ?>

        <div class="l-footer" id="footer">
        <?php
            include('__system__/functions/includes/footer.php');
        ?>
        </div>
        <div class="l-footerBottom" id="footerBottom">
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
    <script src="<?= Project::baseUrl(); ?>js/enviaAtendimento.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
</body>
</html>