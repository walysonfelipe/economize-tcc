<?php
    use Model\{Product, Department};

    $sql = new Sql();

    if (isset($_SESSION[Department::SESSION]['tamQuery'])) {
        unset($_SESSION[Department::SESSION]['tamQuery']);
    }
    if (isset($_SESSION[Department::SESSION]['marcaQuery'])) {
        unset($_SESSION[Department::SESSION]['marcaQuery']);
    }
    if (isset($_SESSION[Department::SESSION]['precoQuery'])) {
        unset($_SESSION[Department::SESSION]['precoQuery']);
    }
    if (isset($_SESSION[Department::SESSION]['favQuery'])) {
        unset($_SESSION[Department::SESSION]['precoQuery']);
    }

    // PRODUTOS COM PROMOÇÕES COMUNS
    $listSimplePromotionalProducts = Product::listSimplePromotionalProducts();
    
    // PRODUTOS COM PROMOCÕES PERSONALIZADAS
    $listCustomPromotionalProducts = Product::listCustomPromotionalProducts();
    if (count($listCustomPromotionalProducts) > 0) {
        $promo_id = "";
        $c = 0;
        foreach ($listCustomPromotionalProducts as $row) {
            if ($promo_id != $row['promo_id']) {
                $products_top[$c] = '
                    <div class="l-prods">
                        <div class="loop owl-carousel">
                ';
                $promo_id = $row['promo_id'];
            } else {
                $products_top[$c] = '';
            }
            $produtos_promo[$c] = '
                <div class="divProdCarousel">
                    <div class="btnFavorito' . $row['produto_id'] . '">
                        <i class="far fa-heart addFavorito" id="' .  $row['produto_id'] . '"></i>
                    </div>
                    <a class="linksProdCarousel" id-produto="' .  $row['produto_id'] . '">
                        <img class="divProdImg" src="' .  Project::baseUrlAdm() . "img-produtos/" . $row['produto_img'] . '">
                        <div class="divisorFilterCar"></div>
                        <p class="divProdPromo">-' .  $row['promo_desconto'] . '%</p>
                        <h4 class="divProdTitle">
                            ' .  $row['produto_nome'] . " - " . $row['produto_tamanho'] . '
                        </h4>
                        <p class="divProdPrice">
                            <span class="divProdPrice1">R$ ' .  $row['produto_preco'] . '</span> R$ ' . $row['produto_desconto'] . '
                        </p>
                    </a>
            ';
            if ($row['empty']) {
                $produtos_promo[$c] .= '
                    <div>
                        <div class="quantity">
                            <span class="esgotQtd">ESGOTADO</span>
                        </div>
                        <form class="formBuy">
                            <button class="btnBuy" type="submit">ADICIONAR</button>
                        </form>
                    </div>
                ';
            } else {
                $produtos_promo[$c] .= '
                    <div>
                        <form class="formBuy">
                            <input type="hidden" value="' .  $row['produto_id'] . '" name="id_prod"/>
                            <div class="quantity">
                                <input type="number" min="0" max="20" value="' .  $row['carrinho'] . '" class="inputQtd inputBuy' .  $row['produto_id'] . '" name="qtd_prod"/>
                            </div>
                            <button class="btnBuy" type="submit">ADICIONAR</button>
                        </form>
                    </div>
                ';
            }
            $produtos_promo[$c] .= '
                </div>
            ';
            $c++;
        }
    }

    $banners = $sql->select("SELECT * FROM banner WHERE banner_status = 1");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Início</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>/style/css/minified-main.css"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css"/>
</head>
<body>
    <div class="l-wrapper">
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

        <?php
            if (count($banners) > 0):?>
                <!-- -------------------- -->
                <!-- Carousel -->
                <div class="l-carousel">
                    <div id="owl-demo" class="owl-carousel">
                        <?php
                        foreach ($banners as $row):?>
                            <div class="item">
                                <img src="<?= Project::baseUrl(); ?>style/img/banner/<?= $row['banner_path']; ?>" alt="<?= $row['banner_nome']; ?>" title="<?= $row['banner_nome']; ?>"/>
                            </div>
                            <?php
                        endforeach;
                        ?>
                    </div>
                </div>
                <?php
            endif;
        ?>
        
        <!-- Title/Display Products -->
        <div class="l-main">
            <center>
                <img class="bannerOfertasImperdivies" width="100%" src="<?= Project::baseUrl(); ?>style/img/banner/bannerofreta.png" alt="Banner Ofertas Imperdíveis">
            </center>
            <div class="l-prods">
                <?php
                    if (count($listSimplePromotionalProducts) > 0):?>
                        <div class="loop owl-carousel">
                            <?php
                            foreach ($listSimplePromotionalProducts as $v):?>
                                <div class="divProdCarousel">
                                    <div class="btnFavorito<?= $v['produto_id']; ?>">
                                        <i class="far fa-heart addFavorito" id="<?= $v['produto_id']; ?>"></i>
                                    </div>
                                    <a class="linksProdCarousel" id-produto="<?= $v['produto_id']; ?>">
                                        <img class="divProdImg" src="<?= Project::baseUrlAdm() . "img-produtos/" . $v['produto_img']; ?>">
                                        <div class='divisorFilterCar'></div>
                                        <p class="divProdPromo">-<?= $v['produto_desconto_porcent']; ?>%</p>
                                        <h4 class="divProdTitle">
                                            <?= $v['produto_nome'] . " - " . $v['produto_tamanho']; ?>
                                        </h4>
                                        <p class="divProdPrice">
                                            <span class="divProdPrice1">R$ <?= $v['produto_preco']; ?></span> R$ <?= $v['produto_desconto']; ?>
                                        </p>
                                    </a>
                                    <?php 
                                        if ($v['empty']):?>
                                            <div>
                                                <div class="quantity">
                                                    <span class="esgotQtd">ESGOTADO</span>
                                                </div>
                                                <form class="formBuy">
                                                    <button class="btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                            </div>
                                            <?php
                                        else:?>
                                            <div>
                                                <form class="formBuy">
                                                    <input type="hidden" value="<?= $v['produto_id']; ?>" name="id_prod"/>
                                                    <div class="quantity">
                                                        <input type="number" min="0" max="20" value="<?= $v['carrinho']; ?>" class="inputQtd inputBuy<?= $v['produto_id']; ?>" name="qtd_prod"/>
                                                    </div>
                                                    <button class="btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                            </div>
                                            <?php
                                        endif;
                                    ?>
                                </div>
                                <?php
                            endforeach;?>
                        </div>
                        <?php
                    else:?>
                        <h2 class="sem_promo">Sem promoções hoje. Aproveite a barra de pesquisa</h2>
                        <?php
                    endif;
                ?>
            </div> 
                <center>
                    <img class="bannerDiaDosPais" width="100%"  src="<?= Project::baseUrl(); ?>style/img/banner/bannerDiaDosPaisRoxo.png" alt="Banner Dia dos Pais">
                </center>
                <?php
                if (count($listCustomPromotionalProducts) > 0):
                    foreach ($produtos_promo as $k => $v):
                        echo $products_top[$k];
                        echo $v;

                        $c = $k + 1;
                        if (isset($products_top[$c])) {
                            if ($products_top[$c] != '') {
                                echo '
                                        </div>
                                    </div>
                                ';
                            }
                        } else {
                            echo '
                                    </div>
                                </div>
                            ';
                        }
                    endforeach;
                endif;
            ?>
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
    <script src="<?= Project::baseUrl(); ?>js/favoritos.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/attCarrinho.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <?php
        if (isset($_SESSION['msg'])):?>
            <script>
                Swal.fire({
                    title: "e.conomize informa:",
                    text: "<?= $_SESSION['msg']; ?>",
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