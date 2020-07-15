<?php
    use Model\User;
    User::checkLoginAndRedirect();

    $sql = new Sql();
    $compra = [];

    $results = $sql->select("SELECT COUNT(compra_id) AS qtd_compra, SUM(compra_total) AS soma_compra FROM compra WHERE usu_id = :id", [
        ":id" => $_SESSION[User::SESSION]['usu_id']
    ]);
    $inf_compra = $results[0];

    if (count($results) > 0) {
        $results = $sql->select("SELECT c.compra_id, c.compra_registro, c.compra_total, f.forma_nome FROM compra c JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id WHERE c.usu_id = :id ORDER BY c.compra_registro DESC", [
            ":id" => $_SESSION[User::SESSION]['usu_id']
        ]);

        foreach ($results as $row) {
            $row['compra_registro'] = Project::formatRegister($row['compra_registro']);
            $row['compra_total'] = Project::formatPriceToReal($row['compra_total']);
            array_push($compra, $row);
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <title>e.conomize | Minhas compras</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>style/css/minified-main.css">
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css">
</head>
<body>
	<div class="l-wrapper_cadastro">
		<div class="l-topNavCad" id="topNav">
		<?php
			include('__system__/functions/includes/topNav.php');
		?>    
		</div>
		<div class="l-headerNavMobile" id="headerNav">
		<?php
			include('__system__/functions/includes/header.php');
		?>
        </div>
        
        <div class="l-mainCad">
            <h2 align="center" class="tituloOfertas"><i class="fas fa-shopping-bag"></i> HISTÓRICO DE COMPRAS</h2>
            <div class="divCompraRight">
                <div class="answer-purch">
                    <h1>Clique em uma compra e ela aparecerá aqui!</h1>
                </div>
            </div>
            <div class="divCompraLeft">
                <div class="titleLeft">
                    <h2 class="menuTit">HISTÓRICO</h2>
                    
                    <p class="menuSubtit">
                        <b>Total de compras:</b> <?= $inf_compra['qtd_compra']; ?><br/><br>
                        <b>Total de gastos:</b> R$<?= number_format($inf_compra['soma_compra'], 2, ',', '.'); ?>
                    </p>
                        <?php
                            if (count($compra) > 0):?>
                                <div class="searchPurch">
                                    <input type="text" class="inputSearchDuvida" name="inputSearchPurch" id="inputSearchPurch" placeholder="Pesquise">
                                    <span class="help-block-purch"></span>
                                </div>
                                <div class="showCompras">
                                    <?php
                                    foreach($compra as $k => $v):?>
                                        <a href="#" class="viewPurchase" data-purch="<?= $v['compra_id'] ?>">
                                            <p class="p_showPurch">
                                                Data: <?= $v['compra_registro']; ?><br/>
                                                Total: R$<?= $v['compra_total'] ?><br/>
                                                Meio Pag.: <?= $v['forma_nome']; ?>
                                            </p>
                                        </a>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                                <?php
                            endif;
                        ?>
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
        <div class="l-footerBottomCad" id="footerBottom">
		<?php
            include('__system__/functions/includes/bottomFooter.html');
        ?>
		</div>
    </div>

	<script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
	<script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/historicoCompra.js"></script>
</body>
</html>