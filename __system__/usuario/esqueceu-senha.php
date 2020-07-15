<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Esqueceu sua senha?</title>
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

        <div class="l-bottomNav" id="bottomNav">
        <?php
            include('__system__/functions/includes/bottom.php');
        ?>
        </div>

        <div class="l-mainFiltroPesq">
            <div class="divAgend">
                <h2 align="center" class="tituloOfertas"><i class="fas fa-question"></i> RECUPERE SUA SENHA</h2>
                <form id="form-esqsenha">
                    <div class="outsideSecInputCad">
                        <div class="field -md">
                            <input type="text" class="placeholder-shown" name="usu_email" id="usu_emailSenha" placeholder=" ">
                            <label class="labelFieldCad" for="usu_emailSenha"><b>EMAIL</b></label>
                        </div>
                    </div>

                    <button type="submit" class="btnPag" id="btn-esq">Recuperar senha</button>
                    <div class="help-block"></div>
                </form>
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
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/esqueceuSenha.js"></script>
</body>
</html>