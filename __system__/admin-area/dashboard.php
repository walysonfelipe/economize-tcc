<?php
    use \Model\Admin;
    Admin::checkLoginAndRedirect();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Painel de Controle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link href="<?= Project::baseUrlAdm(); ?>style/admin.css" rel="stylesheet"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
</head>
<body>
    <div class="l-wrapper">
        <?php
            require 'functions/includes/menu.php';
        ?>
        <section id="conteudo" class="l-main">
            <div>
                <h3 class="dashTitle">Dashboard</h3>
            </div>
        </section>
    </div>

    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrlAdm(); ?>js/admin.js"></script>
</body>
</html>