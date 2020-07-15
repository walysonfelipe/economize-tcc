<?php
use Model\{Product, Department, Cart, Storage};

if (!isset($url_depart) && !isset($url_subcateg) && !isset($url_categ)):
    require_once '__system__/404.php';
else:
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
        unset($_SESSION[Department::SESSION]['favQuery']);
    }

    $empty = false;
    $desc = [];
    $filtros = [0 => [], 1 => [], 2 => []];
    $emptyDesc = null;
    $filtroTitle = "CATEGORIA";
    $title = "Departamentos";
    $maxLinks = 3;

    if (isset($url_depart)) {
        $title = Project::formatFirstName($url_depart[0]['depart_nome']);
    }
    if (isset($url_subcateg)) {
        $title .= " - " . Project::formatFirstName($url_subcateg[0]['subcateg_nome']);
    }
    if (isset($url_categ)) {
        $title .= " - " . Project::formatFirstName($url_categ[0]['categ_nome']);
    }

    if (!isset($URL[2])) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $desc = $url_depart[0];

        if (
            !isset($_SESSION[Department::SESSION]['tamQuery']) &&
            !isset($_SESSION[Department::SESSION]['marcaQuery']) &&
            !isset($_SESSION[Department::SESSION]['precoQuery']) &&
            !isset($_SESSION[Department::SESSION]['favQuery'])
        ) {
            $_SESSION[Department::SESSION]['rawQuery'] = "SELECT SQL_CALC_FOUND_ROWS p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE s.depart_id = {$desc['depart_id']} AND d.armazem_id = {$_SESSION[Storage::SESSION]['arm_id']} ORDER BY p.produto_nome ";
        }

        if (isset($_GET['page']) && isset($_SESSION[Department::SESSION]['rawQuery'])) {
            $pagination = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
        } else {
            $pagination = Product::getPageDepart($url_depart[0]['depart_id'], $page);
        }
        
        $pages = [];
		for ($x = 0; $x < $pagination['pages']; $x++):
			array_push($pages, ($x + 1));
        endfor;

        if (count($pagination['data']) === 0) {
            $emptyDesc = "Este departamento ou página está vazio";
            $empty = true;
        }
        
        if (!$empty) {
            $filtroTitle = "CATEGORIA";
            $filtros[0] = Department::getAllSubcategByDepart($desc['depart_id']);
            $filtros[1] = Department::getDistinctSubcategProductTamByDepart($desc['depart_id']);
            $filtros[2] = Department::getDistinctSubcategProductMarcaByDepart($desc['depart_id']);
        }
    } else {
        if (!isset($URL[3])) {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $desc = [
                "subcateg_id" => $url_subcateg[0]['subcateg_id'],
                "depart_nome" => $url_subcateg[0]['subcateg_nome'],
                "depart_desc" => $url_depart[0]['depart_desc'],
                "depart_icon" => $url_depart[0]['depart_icon']
            ];

            if (isset($_GET['page']) && isset($_SESSION[Department::SESSION]['rawQuery'])) {
                $pagination = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            } else {
                $pagination = Product::getPageSubcateg($url_subcateg[0]['subcateg_id'], $page);
                $_SESSION[Department::SESSION]['rawQuery'] = "SELECT p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE c.subcateg_id = {$desc['subcateg_id']} AND d.armazem_id = {$_SESSION[Storage::SESSION]['arm_id']} ORDER BY p.produto_nome ";
            }
            
            $pages = [];
            for ($x = 0; $x < $pagination['pages']; $x++):
                array_push($pages, ($x + 1));
            endfor;

            if (count($pagination['data']) === 0) {
                $emptyDesc = "Esta categoria ou página está vazia";
                $empty = true;
            }
            
            if (!$empty) {
                $filtroTitle = "CATEGORIA";
                $filtros[0] = Department::getAllSubcategBySubcateg($desc['subcateg_id']);
                $filtros[1] = Department::getDistinctSubcategProductTamBySubcateg($desc['subcateg_id']);
                $filtros[2] = Department::getDistinctSubcategProductMarcaBySubcateg($desc['subcateg_id']);

                Project::arrayReplaceKey($filtros[0], "categ_nome", "subcateg_nome");
                Project::arrayReplaceKey($filtros[0], "categ_id", "subcateg_id");
                Project::arrayReplaceKey($filtros[0], "categ_url", "subcateg_url");
            }
        } else {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $desc = [
                "categ_id" => $url_categ[0]['categ_id'],
                "depart_nome" => $url_categ[0]['categ_nome'],
                "depart_desc" => $url_depart[0]['depart_desc'],
                "depart_icon" => $url_depart[0]['depart_icon']
            ];

            if (isset($_GET['page']) && isset($_SESSION[Department::SESSION]['rawQuery'])) {
                $pagination = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            } else {
                $pagination = Product::getPageCateg($url_categ[0]['categ_id'], $page);
                $_SESSION[Department::SESSION]['rawQuery'] = "SELECT p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE p.produto_categ = {$desc['categ_id']} AND d.armazem_id = {$_SESSION[Storage::SESSION]['arm_id']} ORDER BY p.produto_nome ";
            }
            
            $pages = [];
            for ($x = 0; $x < $pagination['pages']; $x++):
                array_push($pages, ($x + 1));
            endfor;

            if (count($pagination['data']) === 0) {
                $emptyDesc = "Esta subcategoria ou página está vazia";
                $empty = true;
            }
            
            if (!$empty) {
                $filtros[1] = Department::getDistinctSubcategProductTamByCateg($desc['categ_id']);
                $filtros[2] = Department::getDistinctSubcategProductMarcaByCateg($desc['categ_id']);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <title>e.conomize | <?= $title; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
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
        <div class="l-topNavFiltroPesq" id="topNav">
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

        <div class="l-mainFiltroPesq">
            <div class="center_header">
                <div class="tilteFilterProd">
                    <h4><i class="<?= $desc["depart_icon"]; ?>"></i> <?= mb_strtoupper($title); ?></h4>
                </div>
                <h5><?= $desc["depart_desc"]; ?></h5>
                <?php
                if (count($pagination['data']) === 0):?>
                    <div class="msgNoProds">
                        <h3><?= $emptyDesc; ?>, por enquanto!</h3>
                    </div>
                    <?php
                endif;
                ?>
            </div>
            
            <?php if (!empty($filtros[1])):
                $volRemove = isset($_SESSION[Department::SESSION]['tamQuery']) ? '&nbsp;&nbsp;&nbsp;<span class="limpaVol limpaBusca"><i class="fas fa-minus-square"></i></span>' : '';?>
                <div class="filtro_pesquisaMobile">
                    <h5 class="titleFilter"><i class="fas fa-sliders-h"></i> FILTROS DE PESQUISA</h5>
                    <?php if (isset($filtros[0]) && !empty($filtros[0])): ?>
                        <div class="divFilter">
                            <label class="titleConfigFilter"><i class="fas fa-font"></i> <?= $filtroTitle; ?></label>
                            <select class="selectFilter categ">
                                <option selected disabled> Filtrar </option>
                                <?php
                                foreach ($filtros[0] as $v):?>
                                    <option value="<?= $v['subcateg_url']; ?>"><?= $v['subcateg_nome']; ?></option>
                                    <?php
                                endforeach;?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="divFilter">
                        <label class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME <?= $volRemove; ?></label>
                        <select class="selectFilter produto_tamanho">
                            <option selected disabled value="*000*"> Filtrar </option>
                            <?php
                            foreach ($filtros[1] as $v):?>
                                <option value="<?= $v['tam']; ?>"><?= $v['tam']; ?></option>
                                <?php
                            endforeach;?>
                        </select>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label> 
                        <select class="selectFilter prod_marca">
                            <option selected disabled value="*000*"> Filtrar </option>
                            <?php
                            foreach ($filtros[2] as $v):?>
                                <option value="<?= $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></option>
                                <?php
                            endforeach;?>
                        </select>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterPreco">&nbsp;<i class="fas fa-dollar-sign"></i> &nbsp;PREÇO</label>
                        <select class="selectFilter prod_preco">
                            <option selected disabled value="*000*"> Filtrar </option>
                            <option value="DESC">Maior Preço</option>
                            <option value="ASC">Menor Preço</option>
                        </select>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterFav" for="fav_radio"><i class="fas fa-heart"></i> FAVORITOS</label>
                        <input type="radio" name="fav_radio" class="fav_radio prod_fav" id="fav_radio"/>
                    </div>
                </div>

                <!-- FILTROS PARA TELAS GRANDES -->

                <div class="filtro_pesquisa">
                    <div class="divTitleFilter">
                        <h5 class="titleFilter">FILTROS DE PESQUISA</h5>
                    </div>
                    <?php if (isset($filtros[0]) && !empty($filtros[0])): ?>
                        <div class="divFilter">
                            <label class="titleConfigFilter"><i class="fas fa-font"></i> <?= $filtroTitle; ?></label>
                            <ul class="listFilterOptions">
                            <?php
                            foreach ($filtros[0] as $v):?>
                                <li class="celulaListFilterOpt" value="<?= $v['subcateg_nome']; ?>">
                                    <input id="<?= $v['subcateg_nome'].$v['subcateg_id']; ?>" class="categ" type="radio" value="<?= $v['subcateg_url']; ?>"> 
                                    <label for="<?= $v['subcateg_nome'].$v['subcateg_id']; ?>"><?= $v['subcateg_nome']; ?></label>
                                </li>
                                <?php
                            endforeach;?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="divFilter">
                        <label class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME <?= $volRemove; ?></label>
                        <ul class="listFilterOptions">
                        <?php
                        foreach ($filtros[1] as $k => $v):?>
                            <li class="celulaListFilterOpt"><input type="radio" name="prod_tam" id="<?= $k; ?>" class="produto_tamanho" value="<?= $v['tam']; ?>"/> <label for="<?= $k; ?>"><?= $v['tam']; ?></label></li>
                            <?php
                        endforeach;?>
                        </ul>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label>
                        <ul class="listFilterOptions">
                        <?php
                        foreach ($filtros[2] as $k => $v):?>
                            <li class="celulaListFilterOpt"><input type="radio" name="produto_marca" id="<?= $k . $v['marca_nome']; ?>" class="prod_marca" value="<?= $v['marca_nome']; ?>"/> <label for="<?= $k . $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></label></li>
                            <?php
                        endforeach;?>
                        </ul>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                        <ul class="listFilterOptions">
                            <li class="celulaListFilterOpt">
                                <input type="radio" name="produto_preco" class="prod_preco" id="me_p" value="ASC"> <label for="me_p">Menor preço</label>
                            </li>
                            <li class="celulaListFilterOpt">
                                <input type="radio" name="produto_preco" class="prod_preco" id="ma_p" value="DESC"> <label for="ma_p">Maior preço</label>
                            </li>
                        </ul>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterFav"><i class="fas fa-heart"></i> FAVORITOS</label>
                        <ul class="listFilterOptions">
                            <li class="celulaListFilterOpt">
                                <input type="radio" name="produto_fav" class="prod_fav" id="fav_rad"> <label for="fav_rad">Favoritos</label>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="registShow">
                    Mostrando <?= count($pagination['data']); ?> de <?= $pagination['total']; ?> produtos
                </div>

                <div class="divShowProdFilter">
                    <?php foreach ($pagination['data'] as $v):?>
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
                                    if (!$v["empty"]):?>
                                        <form class="formBuy">
                                            <input type="hidden" value="<?= $v["produto_id"]; ?>" name="id_prod"/>
                                            <input type="number" min="0" max="20" value="<?= $v['carrinho']; ?>" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
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
                    <?php endforeach;?>
                </div>

                <div class="paginacao">
                    <a onclick="dataDeparts(<?= $pages[0]; ?>)" href="#" class="btnPaginacao" style="font-size:8pt;"><i class="fas fa-chevron-left"></i></a>
                
                    <?php
                        for ($pag_ant = ($page - $maxLinks); $pag_ant <= ($page - 1); $pag_ant++):
                            if ($pag_ant >= 1):?>
                                <a onclick="dataDeparts(<?= $pages[$pag_ant - 1]; ?>)" href="#" class="btnPaginacao"><?= $pag_ant; ?></a>
                                <?php
                            endif;
                        endfor;
                    ?>

                    <a href="#" class="btnPaginacaoPage"><b><?= $page; ?></b></a>
                    
                    <?php
                        for ($pag_dep = ($page + 1); $pag_dep <= ($page + $maxLinks); $pag_dep++):
                            if ($pag_dep <= $pagination['pages']):?>
                                <a onclick="dataDeparts(<?= $pages[$pag_dep - 1]; ?>)" href="#" class="btnPaginacao"><?= $pag_dep; ?></a>
                                <?php
                            endif;
                        endfor;
                    ?>
                    
                    <a onclick="dataDeparts(<?= $pages[$pagination['pages'] - 1]; ?>)" href="#" class="btnPaginacao" style="font-size:8pt;"><i class="fas fa-chevron-right"></i></a>
                </div>
            <?php endif; ?>
            <?php // require_once 'functions/includes/filtroPesquisa.php'; ?>
        </div>

        <?php
            include('functions/includes/modal.php');
        ?>
        
        <div class="l-footerFiltroPesq" id="footer">
        <?php
            include('functions/includes/footer.php');
        ?>
        </div>
        <div class="l-footerBottomFiltroPesq" id="footerBottom">
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
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/filtros.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
</body>
</html>
<?php endif; ?>