<?php
    if (Project::isXmlHttpRequest()) {
        $sql = new Sql();

        $json = [];
        $json['status'] = 1;
        $json['error_list'] = [];

        if (empty($_POST["name_usu"])) {
            $json['error_list']['#name_usu'] = "<p style='width:100%; margin-bottom:-20px; padding-top:7px; text-align:center; color:#333; font-size:9pt;'>Por favor, insira seu nome neste campo</p>";
        } else {
            if (empty($_POST["email_usu"])) {
                $json['error_list']['#email_usu'] = "<p style='width:100%; margin-bottom:-20px; padding-top:7px; text-align:center; color:#333; font-size:9pt;'>Por favor, insira seu e-mail neste campo</p>";
            } else {
                if (!filter_var($_POST["email_usu"], FILTER_VALIDATE_EMAIL)) {
                    $json["error_list"]["#email_usu"] = "<p style='width:100%; margin-bottom:-20px; padding-top:7px; text-align:center; color:#333; font-size:9pt;'>Por favor, insira um e-mail válido neste campo</p>";
                } else {
                    if (empty($_POST["opt"]) || $_POST["opt"] === "*000*") {
                        $json['error_list']['#opt'] = "<p style='width:100%; margin-bottom:-20px; padding-top:7px; text-align:center; color:#333; font-size:9pt;'>Por favor, escolha o conteúdo da mensagem</p>";
                    } else {
                        if (empty($_POST["txt_usu"])) {
                            $json['error_list']['#txt_usu'] = "<p style='width:100%; margin-bottom:-20px; padding-top:7px; text-align:center; color:#333; font-size:9pt;'>Por favor, comente sua mensagem neste campo</p>";
                        }
                    }
                }
            }
        }
        
        if (!empty($json["error_list"])) {
            $json["status"] = 0;
        } else {
            $stmt = $sql->query("INSERT INTO atendimento(nome_usu, email_usu, tp_problema, desc_problema) VALUE(:name, :email, :opt, :message)", [
                ":name" => $_POST["name_usu"], ":email" => $_POST["email_usu"], ":opt" => $_POST["opt"],
                ":message" => $_POST["txt_usu"]
            ]);

            if (!$stmt) {
                $json['status'] = 0;
                $json['error_list']['#btnAtend'] = "<p style='width:100%; margin-bottom:-20px; padding-top:7px; text-align:center; color:#333; font-size:9pt;'>Um erro inesperado ocorreu! Estamos trabalhando para corrigí-lo</p>";
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
