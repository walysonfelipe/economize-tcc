<?php
    use Model\User;
    User::checkLoginAndRedirect();

    $sql = new Sql();
    
    $compras_inf = $sql->select("SELECT COUNT(usu_id) AS qtd_compra, SUM(compra_total) AS total_compra FROM compra WHERE usu_id = :id", [
        ":id" => $_SESSION[User::SESSION]['usu_id']
    ]);
    $favoritos_inf = $sql->select("SELECT COUNT(usu_id) AS qtd_fav FROM produtos_favorito WHERE usu_id = :id", [
        ":id" => $_SESSION[User::SESSION]['usu_id']
    ]);

    $check = "";
    if ((int)$_SESSION[User::SESSION]['usu_mailmkt'] === 1) {
        $check = "checked";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8"/>
    <title>e.conomize | Configurar Perfil</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>style/css/minified-main.css"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css"/>
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
            <h2 align="center" class="tituloOfertas"><i class="fas fa-cog"></i> CONFIGURAÇÕES DO PERFIL</h2>
            <div class="leftContentConfigProfile">

            <div class="divConfigProfileFacts">
                <h4>FATOS DESSA CONTA</h4>
                <b class="titleDivConfigProfileFacts"><i class="fas fa-shopping-bag"></i> Total de compras:</b>
                <?php
                    if ($compras_inf[0]['qtd_compra'] > 0) {
                        echo '
                            <p class="textDivConfigProfileFacts">
                                <b>' . $compras_inf[0]['qtd_compra'] . '</b>
                            </p>

                            <!--
                            <b class="titleDivConfigProfileFacts"><i class="fas fa-shopping-bag"></i> Total de gastos:</b>
                            <p class="textDivConfigProfileFacts">
                                <b>R$' . Project::formatPriceToReal($compras_inf[0]['total_compra']) . '</b>
                            </p>-->
                        ';
                    } else {
                        echo '
                            <p class="textDivConfigProfileFacts">
                                <b>0</b>
                            </p>';
                    }
                ?>

                <b class="titleDivConfigProfileFacts"><i class="fas fa-heart"></i> Produtos favoritos:</b> 
                <p class="textDivConfigProfileFacts">
                <b>
                    <?php
                        if (count($favoritos_inf) > 0) {
                            echo $favoritos_inf[0]['qtd_fav'];
                        } else {
                            echo "0";
                        }
                    ?>
                </b>
                </p>
                </div>
            </div>
            <div class="showUsuario">
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage">
                        <h5><i class="fas fa-signature"></i> NOME:</h5>
                    </div>
                    <div class="bottomDivConfigProfilePage">
                        <span class="specialSpan"><?= $_SESSION[User::SESSION]['usu_first_name'] . " " . $_SESSION[User::SESSION]['usu_last_name']; ?></span>
                    </div>
                </div>
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage">
                        <h5><i class="fas fa-id-card"></i> CPF:</h5>
                    </div>
                    <div class="bottomDivConfigProfilePage">
                        <span class="specialSpan"><?= $_SESSION[User::SESSION]['usu_cpf']; ?></span>
                    </div>
                </div>
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage">
                        <h5><i class="far fa-envelope"></i> EMAIL:</h5>
                    </div>
                    <div class="bottomDivConfigProfilePage">
                        <span class="specialSpan"><?= $_SESSION[User::SESSION]['usu_email']; ?>
                            <?php
                                //     if ($_SESSION[User::SESSION]['usu_cstatus'] == 1) {
                                //         echo " (Conta verificada)";
                                //     } else {
                                //         echo " (Verifique sua conta)";
                                //     }
                            ?>
                        </span>
                    </div>
                </div>
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage">
                        <h5><i class="fas fa-unlock"></i> SENHA:</h5>
                    </div>
                    <div class="bottomDivConfigProfilePage">
                        <span></span>
                        <span class="editIcon">
                            <button class="mudarSenha">
                                <i class="fas fa-edit"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage">
                        <h5><i class="fas fa-map-marker-alt"></i> ENDEREÇO:</h5>
                    </div>
                    <div class="bottomDivConfigProfilePage divMostraEndereco">
                        <span class="specialSpanEnd">
                        <?= $_SESSION[User::SESSION]['usu_end'] . ", " . $_SESSION[User::SESSION]['usu_num']; ?>
                        <?= $_SESSION[User::SESSION]['usu_complemento']; ?>
                        </span><br/>
                        <span class="specialSpanEnd">
                            <?= $_SESSION[User::SESSION]['usu_cep']; ?>
                        </span><br/>
                        <span class="specialSpanEnd">
                        <?= $_SESSION[User::SESSION]['usu_bairro'] . ", " . $_SESSION[User::SESSION]['usu_cidade'] . " - " . $_SESSION[User::SESSION]['usu_uf']; ?>
                        </span>
                        <span class="editIconEnd">
                            <button class="mudarEndereco">
                                <i class="fas fa-edit"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage">
                        <h5><i class="fas fa-mobile-alt"></i> TELEFONE(S):</h5>
                    </div>
                    <div class="bottomDivConfigProfilePage divTelefones">
                        
                    </div>
                    <span class="editIconTel">
                        <button class="mudarTelefone">
                            <i class="fas fa-edit"></i>
                        </button><br/><br/>
                        <button class="addTelefone">
                            <i class="fas fa-plus"></i>
                        </button>
                    </span>
                </div>
                <div class="sectionConfigProfilePage">
                    <div class="topDivConfigProfilePage" style="width: 100%;color: #333;">
                        <h5>
                            <input type="checkbox" class="radioCad usuMailMkt" <?= $check; ?> id="usu_mailmkt" name="usu_mailmkt"/> 
                            <label for="usu_mailmkt">Desejo receber notificações do e.conomize no meu email</label>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="sectionAlterConfigProfilePage">
                <div class="divMudarSenha">
                    
                </div>

                <div class="divMudarTelefone">
                    
                </div>

                <div class="divMudarEndereco">
                    
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
	<script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
	<script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
	<script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/configurarPerfil.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
</body>
</html>