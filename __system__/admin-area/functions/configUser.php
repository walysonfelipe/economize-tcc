<?php
    if(isXmlHttpRequest()) {
        $json = array();
        if(isset($_POST["senha_atual"])) {
            $json["status"] = 1;
            $json["error_list"] = array();

            if(empty($_POST["senha_atual"])) {
                $json["error_list"]["#senha_atual"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Insira sua senha atual neste campo</p>";
            } else {
                $sel = $conn->prepare("SELECT funcionario_senha FROM funcionario WHERE funcionario_id=:id");
                $sel->bindValue(":id", "{$_SESSION['inf_func']['funcionario_id']}");
                $sel->execute();
                
                if($sel->rowCount() > 0) {
                    $rows = $sel->fetchAll();
                    foreach($rows as $row) {
                        $senha = $row["funcionario_senha"];
                    }
                    if(password_verify($_POST["senha_atual"], $senha)) {
                        if(empty($_POST["senha_nova"])) {
                            $json["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Insira uma nova senha neste campo</p>";
                        } else {
                            if(strpos($_POST["senha_nova"], " ") != FALSE) {
                                $json["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Não pode haver espaços, por favor!</p>";
                            } else {
                                if((strlen($_POST["senha_nova"]) < 6) || (strlen($_POST["senha_nova"]) > 14)) {
                                    $json["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Por favor, mínimo de 6 caracteres e máximo de 14!</p>";
                                } else {
                                    if (!preg_match("(^[a-zA-Z0-9]+([a-zA-Z\_0-9\.-]*))", $_POST["senha_nova"]) ) {
                                        $json["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Apenas letras e números</p>";
                                    } else {
                                        if($_POST["senha_nova"] != $_POST["senha_nova_confirme"]) {
                                            $json["error_list"]["#senha_nova"] = "";
                                            $json["error_list"]["#senha_nova_confirme"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Senhas não conferem!</p>";
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $json["error_list"]["#senha_atual"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Senha atual incorreta</p>";
                    }
                } else {
                    $json["error_list"]["#senha_atual"] = "<p style='width=100%;text-align:center;margin-bottom:-10px;margin-top:0;color#333;font-size:11pt;'>Senha atual incorreta</p>";
                }
            }

            if(!empty($json['error_list'])) {
                $json['status'] = 0;
            } else {
                $_POST["senha_nova"] = password_hash($_POST["senha_nova"], PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE funcionario SET funcionario_senha=:ns");
                $up->bindValue(":ns", "{$_POST["senha_nova"]}");
                if(!$up->execute()) {
                    $json["error_list"]["#btnSaveMudarSenha"] = "<p style='color: red;'>Erro ao mudar senha, tente novamente.</p>";
                }
            }
        }
        
        echo json_encode($json);
    }
?>