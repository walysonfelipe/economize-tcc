<?php
    $sql = new Sql();

    $empty = true;
    $duvidas = [];
    $results = $sql->select("SELECT duvida_id, duvida_pergunta FROM duvida_frequente ORDER BY duvida_pergunta");
    if (count($results) > 0) {
        $empty = false;
        foreach ($results as $row) {
            array_push($duvidas, $row);
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Dúvidas frequentes</title>
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
            <h2 align="center" class="tituloOfertas"><i class="fas fa-question"></i> DÚVIDAS FREQUENTES</h2>
            <div class="divSearchDuvida">
                <label for="search_duvida">Digite o que está procurando: </label><input type="text" class="inputSearchDuvida" id="search_duvida" name="search_duvida"/>
                <span class="cleanSearch"></span>
            </div>
            <div class="l-duvida">
                <?php
                    if (!$empty):
                        foreach ($duvidas as $k => $v):
                            $c = $k + 1;?>
                            <div>
                                <div class="divDuvida" id-duvida="<?= $v['duvida_id'] ?>">
                                    <h4 class="perguntaDuvida"><?= $c . " - " . $v['duvida_pergunta']; ?></h4>
                                </div>
                                <div class="respostaDuvida"></div>
                            </div>
                            <?php
                        endforeach;
                    else:?>
                        <div class="msgNoProds">
                            <h3>Não há dúvidas disponíveis, no momento!</h3>
                        </div>
                        <?php
                    endif;
                ?>
            </div>
            <p class="linkDuvida"><a class="llinkDuvida" href="<?= Project::baseUrlPhp(); ?>suporte/atendimento">Não encontrou a resposta que estava procurando? Contate-nos!</a></p>
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
    <script src="<?= Project::baseUrl(); ?>js/duvida.js"></script>
</body>
</html>