<?php
    use Model\{Cart, Product};

    if (isset($_GET['q'])) {
        $products = Product::getProductsBySearch($_GET['q']);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Busca de Produtos</title>
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
            include('functions/includes/topNav.php');
        ?>    
        </div>
        <nav class="l-headerNav" id="headerNav">
        <?php
            include('functions/includes/header.php');
        ?>
        </nav>

        <div class="l-bottomNav" id="bottomNav">
        <?php
            include('functions/includes/bottom.php');
        ?>
        </div>

		<!-- Title/Display Products -->

        <div class="l-mainFiltroPesq">
            <h2 class="defaultTitle">
                <?= (isset($_GET['q']) && trim($_GET['q']) != "") ? "Sua pesquisa sobre: " . $_GET['q'] : "Pesquise seu produto no campo acima"; ?>
            </h2>
            <div class="divShowProdFav">
                <?php
                    if (isset($products)) {
                        if (count($products) > 0) {
                            foreach ($products as $v):?>
                                <div class="prodFilter">
                                    <div class="btnFavoriteFilter btnFavorito<?= $v['produto_id']; ?>">
                                        <i class="far fa-heart addFavorito" id="<?= $v['produto_id']; ?>"></i>
                                    </div>
                                    <a class="linksProdCarousel" id-produto="<?= $v['produto_id']; ?>">
                                        <img src="<?= Project::baseUrlAdm(); ?>img-produtos/<?= $v["produto_img"]; ?>"/>
                                        <?= isset($v["produto_desconto"]) ? '<p class="divProdPromo">-' . $v['produto_desconto_porcent'] . $v['promo_desconto'] . '%</p>' : '' ; ?>
                                        <div class='divisorFilter'></div>
                                        <h5 class='titleProdFilter'><?= $v["produto_nome"]; ?> - <?= $v["produto_tamanho"]; ?></h5>
                                        <p class='priceProdFilter'>
                                            <?= isset($v["produto_desconto"]) ? '<span class="divProdPrice1">R$' . $v['produto_preco'] . '</span> R$' . $v['produto_desconto'] : 'R$ ' . $v["produto_preco"]; ?>
                                        </p>
                                    </a>
                                    <div>
                                        <?php 
                                            if ($v["produto_qtd"] > 0):?>
                                                <form class="formBuy">
                                                    <input type="hidden" value="<?= $v["produto_id"]; ?>" name="id_prod"/>
                                                    <input type="number" min="0" max="20" value="<?= isset($_SESSION[Cart::SESSION][$v['produto_id']]) ? $_SESSION[Cart::SESSION][$v['produto_id']] : 0 ; ?>" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                                <?php
                                            else:?>
                                                <span class="esgotQtdFilter">ESGOTADO</span>
                                                <form class="formBuy">
                                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                                <?php
                                            endif;
                                        ?>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        } else {
                            echo "
                                <p class='msgHelpSearch'>
                                    <h4>Não houve resposta para o que pesquisou!</h4>
                                    <b>Possíveis soluções:</b><br/>
                                    <b>1.</b> Tente ser bem específico ao que está procurando;<br/>
                                    <b>2.</b> Tente escrever pelo menos uma palavra inteira, por exemplo 'Refrigerante' ao invés de 'Refri';<br/>
                                    <b>3.</b> Não use palavras tão comuns;
                                </p>
                            ";
                        }
                    }
                ?>
			</div>
        </div>

        <!-- Display Products -->

		<?php
            include('functions/includes/modal.php');
        ?>

        <div class="l-footer" id="footer">
        <?php
            include('functions/includes/footer.php');
        ?>
        </div>
        <div class="l-footerBottom" id="footerBottom">
        <?php
            include('functions/includes/bottomFooter.html');
        ?>
        </div>
    </div>

    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/attCarrinho.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/favoritos.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listSearch.js"></script>
</body>
</html>