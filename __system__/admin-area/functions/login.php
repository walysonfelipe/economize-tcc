<?php
	use \Model\Admin;

	$sql = new Sql();

    if (Project::isXmlHttpRequest()) {
		$json = Admin::login(
			$_POST['funcionario_cpf'], $_POST['funcionario_senha'], $_POST["g-recaptcha-response"]
		);
	} else {
		require_once "__system__/404.php";
	}
