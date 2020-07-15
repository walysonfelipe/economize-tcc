<?php
	use Mailer\Message;
	use Model\User;

	$sql = new Sql();

	if (Project::isXmlHttpRequest()) {
		$json = [];
		$json["status"] = 1;
		
		if (isset($_POST['solicita'])) {
			$get = User::getByCodeConfirmMail($_POST['solicita']);

			if ($get !== false) {
				$user = User::createConfirmEmail($get['usu_id']);

				if ($user !== false) {
					$link = Project::baseUrlPhp() . "usuario/confirmar-email?code=" . Project::opensslCrypt($user["cf_id"]);

					if ($user["usu_sexo"] === "M") $sujeito = "vindo";
					elseif ($user["usu_sexo"] === "F") $sujeito = "vinda";
					else $sujeito = "vindo(a)";
					
					$subject = "Bem {$sujeito} ao e.conomize - Confirmação de Cadastro";
					$template = "template-cadastro";

					$mail = new Message(
						Mailer::EMAIL_FROM, Mailer::NAME_FROM, $user['usu_email'], $user['usu_first_name'],
						$subject, $template, [
							"**LINK**" => $link, "**NOME**" => $user['usu_first_name'],
							"**HORARIO**" => Project::formatRegister($user['usu_registro'])
						]
					);

					if ($mail->send()) {
						$_SESSION[User::MSG_SESSION][0] = $user['usu_first_name'];
						$_SESSION[User::MSG_SESSION][1] = $user["usu_sexo"];
						$_SESSION[User::MSG_SESSION][2] = true;
					} else {
						$json["status"] = 0;
					}
				} else {
					$json['status'] = 0;
				}
			} else {
				$json['status'] = 0;
			}
		} elseif (isset($_POST['code'])) {
			$get = User::getByCodeConfirmMail($_POST['code']);

			if (password_verify($_POST['usu_senha'], $get["usu_senha"])) {
				$setConfirm = User::setValidateConfirmMail($get['cf_id']);
				
				if ($setConfirm === false) {
					$json['status'] = 0;
					$json['error'] = "<p style='color:#A94442;'><b>Erro inesperado ocorreu. Tente novamente!</b></p>";
				} else {
					$login = User::login($get["usu_email"], $_POST["usu_senha"]);
					$json["nome_usuario"] = $login['nome_usuario'];
				}
			} else {
				$json['status'] = 0;
				$json['error'] = "<p style='color:#A94442;'><b>Senha inválida</b></p>";
			}
		}
		
		echo json_encode($json);
	} else {
        require_once '__system__/404.php';
    }
