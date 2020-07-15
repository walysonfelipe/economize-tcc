<?php
	use Model\User;

	if (isset($_SESSION[User::MSG_SESSION])) {
		$message = 1;
		$firstName = $_SESSION[User::MSG_SESSION][0];
		$sexo = $_SESSION[User::MSG_SESSION][1];

		if (isset($_SESSION[User::MSG_SESSION][2])) {
			$title = "Email enviado novamente";
		}
		
		unset($_SESSION[User::MSG_SESSION]);
	} elseif (isset($_GET['code'])) {
		$message = 2;
		$validation = User::validConfirmMailDecrypt($_GET['code']);

		if ($validation === 0) {
			$message = 3;
		} else {
			if (isset($validation['solicita'])) {
				$message = 4;
			} elseif (isset($validation['verificado'])) {
				$message = 5;
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <title>e.conomize | Confirme seu email</title>
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
			<?php
			if (isset($message)):
				if ($message == 1):
					if ($sexo === "M") {
						$sujeito[0] = "cadastrado";
						$sujeito[1] = "vindo";
					} elseif ($sexo === "F") {
						$sujeito[0] = "cadastrada";
						$sujeito[1] = "vinda";
					} else {
						$sujeito[0] = "cadastrado(a)";
						$sujeito[1] = "vindo(a)";
					}
					?>
					<div class="msgSuccess">
						<h3>
							<?= (isset($title)) ? $title : "Você foi {$sujeito[0]} com sucesso"; ?>
							, <?= $firstName; ?>!</h3>
						<p>
							Agora falta pouco, nós te enviamos um email com um link para que você possa confirmar o seu endereço de email seguramente. Depois disso é só aproveitar! Seja muito bem <?= $sujeito[1]; ?>!
						</p>
						<p>
							<small>* Você tem até uma hora para confirmar seu email. Se caso tenha passado o tempo, utilize o mesmo link do email para solicitar um outro.</small>
						</p>
					</div>
					<?php
				elseif ($message == 2):?>
					<div class="divAgend" style="margin: 3rem auto;padding: .5rem 0;">
                        <h2 class="defaultTitle">CONFIRME TEU EMAIL, <?= strtoupper($validation['usu_first_name']); ?>!</h2>
                        <form id="form-confemail">
                            <div class="outsideSecInputCad">
                                <input type="hidden" name="code" value="<?= $_GET['code']; ?>">
                                <div class="field -md">
                                    <input type="password" class="placeholder-shown" name="usu_senha" id="usu_senha" placeholder=" ">
                                    <label class="labelFieldCad" for="usu_senha"><b>SENHA</b></label>
                                </div>
                            </div>

                            <button type="submit" class="btnPag" id="btn-confmail">Pronto</button>
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
					<div class="Errordiv">
						<h4 class="ErrorTitle">
							Oops. O código expirou, <?= $validation['usu_first_name']; ?>!<br/>
							Deseja que envie-mos um novo codigo de confirmação pra você?
						</h4>
						<br/><br/>
						<button type="button" class="btnPag" id="btn-solicita" data-code="<?= $_GET['code']; ?>">Solicite aqui</button>
						<div class="help-block"></div>
					</div>
					<?php
				elseif ($message == 5):?>
					<div class="msgSuccess">
						<h3>O teu email foi verificado com sucesso, <?= $validation['usu_first_name']; ?>!</h3>
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
	<script src="<?= Project::baseUrl(); ?>js/confirmaEmail.js"></script>
</body>
</html>