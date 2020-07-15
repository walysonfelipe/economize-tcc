<?php
    use Model\Product;

    if (isset($URL[2])) {
        $product = Product::getProductByIdAndArm($URL[2]);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | <?= isset($product) ? $product['produto_nome'] . " - " . $product['produto_tamanho'] : "Produto inexistente"; ?></title>
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

        <div class="l-mainProduto">
            <div class="l-pageProd">
                <?php
                    if (isset($product)):?>
                        <div class="divProdutoLeft">
                            <img class="imgProduto" src="<?= Project::baseUrlAdm(); ?>img-produtos/<?= $product['produto_img']; ?>"/>
                        </div>

                        <div class="divProdutoRight">
                        <div class="infProduto">
                            <div class="btnFavorito<?= $product['produto_id']; ?> favoriteBtn">
                                <i class="far fa-heart addFavorito" id="<?= $product['produto_id']; ?>"></i>
                            </div>
                            <h2 class="categProduto"><?= $product['depart_nome']; ?> <span class="ponto">.</span> <?= $product['subcateg_nome']; ?> <span class="ponto">.</span> <?= $product['categ_nome']; ?></h2>
                            <span class="marcaProdutoModal"><?= $product['marca_nome']; ?></span>
                            <h2 class="nomeProdutoModal">
                                <?= $product['produto_nome']; ?><br/>
                                <span class="volProdutoModal"><?= $product['produto_tamanho']; ?></span>
                            </h2>
                        </div>
                        <div class="precoProduto">
                            <p class="precoProdutoModal">
                                <?php
                                    if ($product['produto_desconto_porcent'] || $product['promo_desconto']):?>
                                                <span class="antPreco">R$ <?= $product['produto_preco']; ?></span><br/>
                                                R$ <?= $product['produto_desconto']; ?>
                                            </p>
                                        </div>
                                        <?php
                                    else:?>
                                                R$ <?= $product['produto_preco']; ?>
                                            </p>
                                        </div>
                                        <?php
                                    endif;

                                    if (!$product['empty']):?>
                                        <div class="cartProdutoModal cartProd">
                                            <form class="formBuy">
                                                <input type="hidden" value="<?= $product['produto_id']; ?>" name="id_prod"/>
                                                <input type="number" min="0" max="20" value="<?= $product['carrinho']; ?>" class="inputQtdModal inputBuy<?= $product['produto_id']; ?>" name="qtd_prod"/>
                                                <button class="btnBuyModal" type="submit">ADICIONAR</button>
                                            </form>
                                        </div>
                                        <?php
                                    else:?>
                                        <div class="cartProdutoModal cartProd">
                                            <span class="esgotModal">ESGOTADO</span>
                                            <form class="formBuy">
                                                <button class="btnBuyModal" type="submit">ADICIONAR</button>
                                            </form>
                                        </div>
                                        <?php
                                    endif;
                                ?>
                            <div class="compProduto">
                                <p class="imgLust">Imagem meramente ilustrativa</p>
                                <p class="compartProduto">
                                    Compartilhar: &nbsp;&nbsp;
                                    <a class="linkShareProd" href="https://www.facebook.com/sharer.php?u=http://www.economize.top/produto/<?= $URL[2]; ?>" target="_blank" title="Compartilhar produto no Facebook">
                                        <button class="btnShareProd">
                                            <i class="fab fa-facebook-f"></i>
                                        </button>
                                    </a>
                                    <a class="linkShareProd" href="http://twitter.com/intent/tweet?text=<?= $product['produto_nome'] . " - " . $product['produto_tamanho']; ?>&url=http://www.economize.top/produto/<?= $URL[2]; ?>&via=economizebrazil" title="" target="_blank">
                                        <button class="btnShareProd">
                                            <i class="fab fa-twitter"></i>
                                        </button>
                                    </a>
                                    <a class="linkShareProd" href="https://web.whatsapp.com/send?text=<?= $product['produto_nome'] . " - " . $product['produto_tamanho']; ?> http://www.economize.top/produto/<?= $URL[2]; ?>" class="pc" target="_blank">
                                        <button class="btnShareProd">
                                            <i class="fab fa-whatsapp"></i> Web
                                        </button>
                                    </a>
                                    <a class="linkShareProd" href="whatsapp://send?text=<?= $product['produto_nome'] . " - " . $product['produto_tamanho']; ?> http://www.economize.top/produto/<?= $URL[2]; ?>" data-action="share/whatsapp/share">
                                        <button class="btnShareProd">
                                            <i class="fab fa-whatsapp"></i> App
                                        </button>
                                    </a>
                                </p>
                            </div>
                            <div class="descProduto">
                                <h4 class="descTitleProduto">Descrição:</h4>
                                <p>
                                    <?= $product['produto_descricao']; ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    else:?>
                        <div class="msgNoProds">
                            <h3>O produto procurado é inexistente ou deletado!</h3>
                        </div>
                        <?php
                    endif;
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
</body>
</html>