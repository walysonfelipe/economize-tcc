<?php
	use Mailer\Message;
	use Model\User;

	$sql = new Sql();

	if (Project::isXmlHttpRequest()) {
		$json = [];
		$json["status"] = 1;

		if (isset($_POST["usu_nome"])) {
			$json["error_list"] = [];
			$json["error_tel"] = "";

			$_POST["usu_nome"] = Project::formatFirstName($_POST["usu_nome"]);
			$_POST["usu_sobrenome"] = Project::formatLastName($_POST["usu_sobrenome"]);

			if (empty($_POST["usu_nome"])) {
				$json["error_list"]["#usu_nome"] = "<p class='msgErrorCad'>Por favor, insira seu nome neste campo</p>";
			} else {
				if (substr_count($_POST["usu_nome"], " ") > 1) {
					$json["error_list"]["#usu_nome"] = "<p class='msgErrorCad'>Por favor, somente nomes simples ou compostos neste campo</p>";
				}
			}

			if (empty($_POST["usu_sobrenome"])) {
				$json["error_list"]["#usu_sobrenome"] = "<p class='msgErrorCad'>Por favor, insira seu sobrenome neste campo</p>";
			}

			if (empty($_POST["usu_cpf"])) {
				$json["error_list"]["#usu_cpf"] = "<p class='msgErrorCad'>Por favor, insira seu CPF neste campo</p>";
			} else{
				if (Project::validarCPF($_POST["usu_cpf"]) == true) {
					$results = $sql->select("SELECT usu_cpf FROM usuario WHERE usu_cpf = :cpf", [
						":cpf" => $_POST["usu_cpf"]
					]);
					if (count($results) > 0) {
						$json["error_list"]["#usu_cpf"] = "<p class='msgErrorCad'>Esse CPF já foi cadastrado anteriormente</p>";
					}
				} else {
					$json["error_list"]["#usu_cpf"] = "<p class='msgErrorCad'>Por favor, insira um CPF válido</p>";
				}
			}

			if (empty($_POST["usu_email"])) {
				$json["error_list"]["#usu_email"] = "<p class='msgErrorCad'>Por favor, insira seu e-mail neste campo</p>";
			} else {
				if (!filter_var($_POST["usu_email"], FILTER_VALIDATE_EMAIL)) {
					$json["error_list"]["#usu_email"] = "<p class='msgErrorCad'>Por favor, insira um e-mail válido neste campo</p>";
				} else {
					$results = $sql->select("SELECT usu_email FROM usuario WHERE usu_email = :email", [
						":email" => $_POST["usu_email"]
					]);
					if (count($results) > 0) {
						$json["error_list"]["#usu_email"] = "<p class='msgErrorCad'>Esse email já foi cadastrado anteriormente</p>";
					}
				}
			}

			if (empty($_POST["usu_senha"])) {
				$json["error_list"]["#usu_senha"] = "<p class='msgErrorCad'>Por favor, insira sua senha neste campo</p>";
			} else {
				if (strpos($_POST["usu_senha"], " ") != false) {
					$json["error_list"]["#usu_senha"] = "<p class='msgErrorCad'>Não pode haver espaços, por favor!</p>";
				} else {
					if ((strlen($_POST["usu_senha"]) < 6) || (strlen($_POST["usu_senha"]) > 14)) {
						$json["error_list"]["#usu_senha"] = "<p class='msgErrorCad'>Por favor, mínimo de 6 caracteres e máximo de 14!</p>";
					} else {
						if ($_POST["usu_senha"] != $_POST["usu_senha2"]) {
							$json["error_list"]["#usu_senha"] = "";
							$json["error_list"]["#usu_senha2"] = "<p class='msgErrorCad'>Senhas não coincidem!</p>";
						}
					}
				}
			}

			foreach ($_POST["tel_num"] as $k => $v) {
				$key = $k + 1;

				if (empty($v)) {
					$json["error_tel"] = "<p class='msgErrorCadTel'>Por favor, insira o {$key}º telefone</p>";
				} else {
					if (strlen($v) < 14) {
						$json["error_tel"] = "<p class='msgErrorCadTel'>Por favor, insira o {$key}º telefone <b>corretamente</b></p>";
					}
				}

				if ($json["error_tel"] !== "") break;
			}

			if (empty($_POST["usu_cep"])) {
				$json["error_list"]["#usu_cep"] = "<p class='msgErrorCad'>Por favor, insira o CEP do seu logradouro ou da sua cidade neste campo</p>";
			} else {
				if (strlen($_POST["usu_cep"]) < 9) {
					$json["error_list"]["#usu_cep"] = "<p class='msgErrorCad'>Por favor, insira seu CEP corretamente neste campo</p>";
				} else {
					if (empty($_POST["usu_uf"])) {
						$json["error_list"]["#usu_uf"] = "<p class='msgErrorCad'>Por favor, insira um <b>CEP</b> válido para que o endereço seja preenchido automaticamente</p>";
					} else {
						if (empty($_POST["usu_end"])) {
							$json["error_list"]["#usu_end"] = "<p class='msgErrorCad'>Por favor, insira um <b>CEP</b> válido para que o endereço seja preenchido automaticamente</p>";
						} else {
							if (empty($_POST["usu_bairro"])) {
								$json["error_list"]["#usu_bairro"] = "<p class='msgErrorCad'>Por favor, insira seu bairro neste campo</p>";
							} else {
								if (empty($_POST["usu_num"])) {
									$json["error_list"]["#usu_num"] = "<p class='msgErrorCad'>Por favor, insira o <b>número</b> de sua casa neste campo</p>";
								} else {
									if (!is_numeric($_POST["usu_num"])) {
										$json["error_list"]["#usu_num"] = "<p class='msgErrorCad'>Somente números neste campo</p>";
									} else {
										if (empty($_POST["usu_cidade"])) {
											$json["error_list"]["#usu_cidade"] = "<p class='msgErrorCad'>Por favor, insira a sua cidade neste campo</p>";
										} else {
											$cid = $_POST["usu_cidade"];
											$results = $sql->select("SELECT * FROM cidade WHERE cid_nome = :cid", [
												":cid" => $cid
											]);
											if (count($results) == 0) {
												$_SESSION["msg"]["title"] = "E.conomize informa:";
												$_SESSION["msg"]["text"] = "Não há nenhum armazém em sua cidade ainda, desculpe-nos!";
											}
										}
									}
								}
							}
						}
					}
				}
			}

			if ((!empty($json["error_list"])) || (!empty($json["error_tel"]))) {
				$json["status"] = 0;
			} else {
				$user = User::create($_POST);
				
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

					if (!$mail->send()) {
						$json["status"] = 0;
						$json["error_list"]["#btn-cad"] = "<p style='color:red;'><b>Erro ao enviar email. Tente novamente!</b></p>";
					} else {
						$_SESSION[User::MSG_SESSION][0] = $user['usu_first_name'];
						$_SESSION[User::MSG_SESSION][1] = $user["usu_sexo"];
					}
				} else {
					$json["status"] = 0;
					$json["error_list"]["#btn-cad"] = "<p style='color:red;'><b>Erro inesperado. Tente novamente mais tarde!</b></p>";
				}
			}
		}
		
		echo json_encode($json);
	} else {
        require_once '__system__/404.php';
    }
