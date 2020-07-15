<?php 
    use Model\User;
    use Mailer\Message;

    if (Project::isXmlHttpRequest()) {
        if (isset($_POST['usu_email'])) {
            $json = User::getForgot($_POST['usu_email']);

            if ($json['status']) {
                $subject = "Mercado Digital e.conomize - Recupere sua senha";
                $template = "template-recovery";

                $mail = new Message(
                    Mailer::EMAIL_FROM, Mailer::NAME_FROM, $json['user']['usu_email'], 
                    $json['user']['usu_first_name'], $subject, $template, [
                        "**LINK**" => $json['recovery']['link'], "**NOME**" => $json['user']['usu_first_name'], 
                        "**HORARIO**" => date("d/m/Y às H:i:s")
                    ]
                );

                if ($mail->send()) {
                    $_SESSION[User::MSG_SESSION][0] = $json['user']['usu_first_name'];
                    $_SESSION[User::MSG_SESSION][1] = $json['user']["usu_sexo"];
                } else {
                    $json["status"] = 0;
                }
            }
        } elseif (isset($_POST['usu_senha_new'])) {
            $json['status'] = 1;
            $json["error"] = null;

            if (strpos($_POST["usu_senha_new"], " ") !== false) {
                $json["error"] = "Não pode haver espaços, por favor.";
            } else {
                if ((strlen($_POST["usu_senha_new"]) < 6) || (strlen($_POST["usu_senha_new"]) > 14)) {
                    $json["error"] = "Mínimo de 6 caracteres e máximo de 14, por favor.";
                } else {
                    if ($_POST["usu_senha_new"] !== $_POST["usu_senha_confirm"]) {
                        $json["error"] = "As senhas não coincidem.";
                    }
                }
            }

            if (!empty($json["error"])) {
                $json['status'] = 0;
            } else {
                $_POST['usu_senha_new'] = Project::hashPasswordGenerator($_POST['usu_senha_new']);
                $stmt = User::setForgot($_POST['code'], $_POST['usu_senha_new'], $_POST['usu_id']);

                if ($stmt === false) {
                    $json['status'] = 0;
                    $json["error"] = "Ocorreu um erro ao alterar a senha! Tente novamente.";
                } else {
                    if (User::checkLogin()) {
                        $_SESSION[User::SESSION]['usu_senha'] = $_POST['usu_senha_new'];
                    }
                }
            }
        } else {
            $json = User::createForgot();

            if ($json['status']) {
                $subject = "Mercado Digital e.conomize - Recupere sua senha";
                $template = "template-recovery";

                $mail = new Message(
                    Mailer::EMAIL_FROM, Mailer::NAME_FROM, $json['user']['usu_email'], 
                    $json['user']['usu_first_name'], $subject, $template, [
                        "**LINK**" => $json['recovery']['link'], "**NOME**" => $json['user']['usu_first_name'], 
                        "**HORARIO**" => date("d/m/Y H:i:s")
                    ]
                );

                if ($mail->send()) {
                    $_SESSION[User::MSG_SESSION][0] = $json['user']['usu_first_name'];
                    $_SESSION[User::MSG_SESSION][1] = $json['user']["usu_sexo"];
                } else {
                    $json["status"] = 0;
                }
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
