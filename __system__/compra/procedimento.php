<?php
    use Model\Cart;

    // $_SESSION['cupom_compra'] = [
    //     "cupom_id" => 1,
    //     "cupom_codigo" => "AKPLD750",
    //     "cupom_desconto_porcent" => 50
    // ];

    // print_r($_SESSION['cupom_compra']);
    // exit;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Procedimento de Compra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>/style/css/minified-main.css"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css"/>
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/libraries/progress-tracker-master/app/styles/progress-tracker.css"/>
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
        
        <div class="l-mainFiltroPesq carrega_pagina">

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
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/favoritos.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/cupom.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/attCarrinho.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listCarrinho.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/etapasCompra.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <?php
        if (!isset($_SESSION['carrinho'])):?>
            <script>buscaCarrinho();</script>
            <?php
        else:
            if (!isset($_SESSION['end_agend'])):?>
                <script>buscaCarrinho();</script>
                <?php
            else:
                if (!isset($_SESSION['agend_horario'])):?>
                    <script>buscaEndereco();</script>
                    <?php
                else:
                    if (!isset($_SESSION['pagamento'])):?>
                        <script>buscaAgendamento();</script>
                        <?php
                    else:?>
                        <script>buscaPagamento();</script>
                        <?php
                    endif;
                endif;
            endif;
        endif;

        if (isset($_SESSION['msg'])):?>
            <script>
                Swal.fire({
                    title: "e.conomize informa:",
                    text: "<?= $_SESSION['msg']['text']; ?>",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#A94442",
                    confirmButtonText: "Ok"
                });
            </script>
            <?php
            unset($_SESSION['msg']);
        endif;
    ?>
</body>
</html>