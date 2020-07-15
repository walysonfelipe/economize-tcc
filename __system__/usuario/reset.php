<?php
	use Model\User;

	if (isset($_SESSION[User::MSG_SESSION])) {
		$message = 1;
		$firstName = $_SESSION[User::MSG_SESSION][0];
		$sexo = $_SESSION[User::MSG_SESSION][1];
		unset($_SESSION[User::MSG_SESSION]);
	} elseif (isset($_GET['code'])) {
		$message = 2;
        $validation = User::validForgotDecrypt($_GET['code']);

		if ($validation === false) {
			$message = 3;
		} else {
			if (($validation['rec_data'] !== "") && ($validation['rec_data'] !== null)) {
				$message = 4;
			} else {
                $register = new DateTime($validation['rec_registro']);
                $now = new DateTime();
                $diff = $register->diff($now);
				
				// var_dump($diff); DEBUG

                if (($diff->h > 0) || ($diff->d > 0) || ($diff->m > 0) || ($diff->y > 0)) {
                    $message = 5;
                }
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <title>e.conomize | Recupere sua senha</title>
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
			<?php
			if (isset($message)):
				if ($message == 1):?>
					<div class="msgSuccess">
						<h3>
							Email foi enviado com sucesso, <?= $firstName; ?>!</h3>
						<p>
							Agora falta pouco, nós enviamos-lhe um email com um link para que você possa recuperar sua senha seguramente.
						</p>
						<p>
							<small>* Você tem uma hora até a expiração do link.</small>
						</p>
					</div>
					<?php
				elseif ($message == 2):?>
					<div class="divAgend" style="margin: 3rem auto;padding: .5rem 0;">
                        <h2 class="defaultTitle">DIGITE SUA NOVA SENHA, <?= strtoupper($validation['usu_first_name']); ?>!</h2>
                        <form id="form-resetsenha">
                            <div class="outsideSecInputCad">
                                <input type="hidden" name="code" value="<?= $_GET['code']; ?>">
                                <input type="hidden" name="usu_id" value="<?= $validation['usu_id']; ?>">
                                <div class="field -md">
                                    <input type="password" class="placeholder-shown" name="usu_senha_new" id="usu_senha_new" placeholder=" ">
                                    <label class="labelFieldCad" for="usu_senha_new"><b>SENHA</b></label>
                                </div>
                                <div class="field -md" style="margin-top: 30px;">
                                    <input type="password" class="placeholder-shown" name="usu_senha_confirm" id="usu_senha_confirm" placeholder=" ">
                                    <label class="labelFieldCad" for="usu_senha_confirm"><b>CONFIRME A SENHA</b></label>
                                </div>
                            </div>
							<div style="height: 40px;"></div>

                            <button type="submit" class="btnPag" id="btn-resetsenha">Pronto</button>
                            <div class="help-block"></div>
                        </form>
                    </div>
					<?php
				elseif ($message == 3):?>
					<div class="msgNoProds">
						<h3>Este código é inexistente!</h3>
					</div>
					<?php
				elseif ($message == 4):?>
                    <div class="msgSuccess">
						<h3>Senha foi recuperada com sucesso, <?= $validation['usu_first_name']; ?>!</h3>
					</div>
					<?php
				elseif ($message == 5):?>
					<div class="msgNoProds">
						<h3>Este código já expirou, <?= $validation['usu_first_name']; ?>!</h3>
					</div>
					<?php
				endif;
			else:?>
				<div class="Errordiv">
					<h3 class="ErrorTitle">
						Erro 404<br/>
						Página não encontrada!
					</h3>
					<p class="Errop">
						A página solicitada em sua URL é inexistente ou foi deletada.<br/>
						Caso tenha encontrado alguma instabilidade no sistema, por favor, contate o <a href="<?= Project::baseUrlPhp(); ?>suporte/atendimento">atendimento online</a> o mais rápido possível.
						<br/><br/>
						<a href="<?= Project::baseUrlPhp(); ?>">Clique aqui</a> para voltar à página principal.
					</p>
				</div>
				<?php
			endif;
			?>
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
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
	<script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
	<script src="<?= Project::baseUrl(); ?>js/esqueceuSenha.js"></script>
</body>
</html>