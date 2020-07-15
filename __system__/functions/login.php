<?php
	use Model\User;

	if (Project::isXmlHttpRequest()) {
		if (isset($_POST['usu_cookie_login'])) {
			$json = User::login($_POST["usu_email_login"], $_POST["usu_senha_login"], true);
		} else {
			$json = User::login($_POST["usu_email_login"], $_POST["usu_senha_login"]);
		}

		echo json_encode($json);
	} else {
		require_once '__system__/404.php';
	}
