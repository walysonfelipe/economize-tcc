<?php
    namespace Model;
    
    class User extends \Model
    {
        const SESSION = "EconomizeUserSession";
        const MSG_SESSION = "EconomizeCadUserSession";
        const COOKIE = "EconomizeUserCookie";

        public static function getFromSession()
        {
            $user = new User();

            if (User::checkLogin()) {
                $user->setData($_SESSION[User::SESSION]);
            }

            return $user;
        }

        public static function getFromCookie()
        {
            $sql = new \Sql();

            if (isset($_COOKIE[User::COOKIE]) && !isset($_SESSION[User::SESSION])) {
                $results = $sql->select("SELECT * FROM usuario u JOIN tipousu t ON u.usu_tipo = t.tpu_id WHERE usu_id = :id", [
                    ":id" => $_COOKIE[User::COOKIE]
                ]);

                session_regenerate_id();
                $results[0]['session_id'] = session_id();
                
                $user = new User();
                $user->setData($results[0]);

                $user->setdatanasc(\Project::formatDate($user->getusu_datanasc()));
                $user->setusu_registro(\Project::formatDate($user->getusu_registro()));
                $user->setusu_registro_hora(\Project::formatRegister($user->getusu_registro()));
                $user->setgenero(\Project::formatGenero($user->getgenero()));
                
                $_SESSION[User::SESSION] = $user->getValues();
            }
        }

        public static function createCookie($usu_id)
        {
            User::destroyCookie();
            setcookie(User::COOKIE, $usu_id, time() + (86400 * 1825), User::COOKIE_PATH);
        }

        public static function destroyCookie()
        {
            if (isset($_COOKIE[User::COOKIE])) {
                setcookie(User::COOKIE, "", 0, User::COOKIE_PATH);
            }
        }

        public static function login($email, $password, $cookie = false)
        {
            $sql = new \Sql();
            
            $data = [];
		    $data["status"] = 1;
            $data["error"] = null;
            
            $results = $sql->select("SELECT * FROM usuario u JOIN tipousu t ON u.usu_tipo = t.tpu_id WHERE usu_email = :email", [
                ":email" => $email
            ]);
            
            if (count($results) > 0) {
                if (password_verify($password, $results[0]["usu_senha"])) {
                    if ((int)$results[0]["usu_cstatus"] === 1) {
                        session_regenerate_id();
                        $results[0]['session_id'] = session_id();
                        
                        $user = new User();
                        $user->setData($results[0]);

                        $user->setdatanasc(\Project::formatDate($user->getusu_datanasc()));
                        $user->setusu_registro(\Project::formatDate($user->getusu_registro()));
                        $user->setusu_registro_hora(\Project::formatRegister($user->getusu_registro()));
                        $user->setgenero(\Project::formatGenero($user->getgenero()));
                        
                        $_SESSION[User::SESSION] = $user->getValues();

                        $first_name = explode(" ", $user->getusu_first_name());
                        $data["nome_usuario"] = $first_name[0];
                        
                        if ($cookie === true) {
                            User::createCookie($user->getusu_id());
                        }
                    } else {
                        $data["status"] = 0;
                        $data["error"] = "<p style='color:#A94442;'><b>Acesso negado, conta desativada!</b></p>";
                    }
                } else {
                    $data["status"] = 0;
                    $data["error"] = "<p style='color:#A94442;'><b>E-mail e/ou senha inválido(s)!</b></p>";
                }
            } else {
                $data["status"] = 0;
                $data["error"] = "<p style='color:#A94442;'><b>E-mail e/ou senha inválido(s)!</b></p>";
            }

            return $data;
        }

        public static function checkLogin()
        {
            if (
                !isset($_SESSION[User::SESSION]) || 
                !$_SESSION[User::SESSION] || 
                !(int)$_SESSION[User::SESSION]["usu_id"] > 0
            ) {
                return false;
            } else {
                return true;
            }
        }

        public static function checkLoginAndRedirect($msg = true)
        {
            if (
                !isset($_SESSION[User::SESSION]) || 
                !$_SESSION[User::SESSION] || 
                !(int)$_SESSION[User::SESSION]["usu_id"] > 0
            ) {
                if ($msg) $_SESSION['msg'] = "Você precisa estar logado para visualizar aquele conteúdo.";
                header("Location: " . \Project::baseUrlPhp());
                exit;
            } else {
                return true;
            }
        }

        public static function checkAccountStatus()
        {
            $sql = new \Sql();

            if (User::checkLogin()) {
                $results = $sql->select("SELECT usu_cstatus FROM usuario WHERE usu_id = :usu_id", [
                    ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                ]);
                
                if (count($results) > 0) {
                    if ((int)$results[0]['usu_cstatus'] === 0) {
                        User::logout();
                    }
                }
            }
        }

        public static function logout()
        {
            $_SESSION[User::SESSION] = NULL;
            User::destroyCookie();
        }

        public static function create($data = [])
        {
            $sql = new \Sql();

            if (!empty($data)) {
                $data["usu_senha"] = \Project::hashPasswordGenerator($data["usu_senha"]);

                if (isset($data['usu_mailmkt'])) $mkt = 1;
                else $mkt = 0;
    
                $results = $sql->select("CALL sp_usuario_create(:usu_id, :firstName, :lastName, :sexo, :cpf, :email, :senha, :cep, :end, :num, :comp, :bairro, :cid, :uf, :tpu_usu, :mkt)", [
                    ":usu_id" => 0, ":firstName" => $data["usu_nome"], ":lastName" => $data["usu_sobrenome"], 
                    ":sexo" => $data["usu_sexo"], ":cpf" => $data["usu_cpf"], ":email" => $data["usu_email"],
                    ":senha" => $data["usu_senha"], ":cep" => $data["usu_cep"], ":end" => $data["usu_end"],
                    ":num" => $data["usu_num"],  ":comp" => $data["usu_complemento"], 
                    ":bairro" => $data["usu_bairro"], ":cid" => $data["usu_cidade"], ":uf" => $data["usu_uf"],
                    ":tpu_usu" => 1, ":mkt" => $mkt
                ]);

                if (count($results) > 0) {
                    foreach ($data["tel_num"] as $k => $v) {
                        $stmt = $sql->query("INSERT INTO telefone(tel_num, tpu_tel, usu_id) VALUES(:tel_num, :tpu_tel, :usu_id)", [
                            ":tel_num" => $v,
                            ":tpu_tel" => $data["tipo_tel"][$k],
                            ":usu_id" => $results[0]['usu_id']
                        ]);
                    }

                    if (isset($data['usu_cookie'])) {
                        User::createCookie($results[0]['usu_id']);
                    }

                    return $results[0];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public static function getTelefones()
        {
            $sql = new \Sql();
            $data['tel'] = [];
            $data['tipo_tel'] = [];

            $results = $sql->select("SELECT * FROM telefone t JOIN tipo_tel tt ON t.tpu_tel = tt.tpu_tel_id WHERE t.usu_id = :id", [
                ":id" => $_SESSION[User::SESSION]['usu_id']
            ]);
            foreach ($results as $v) {
                array_push($data['tel'], $v);
            }

            $results = $sql->select("SELECT * FROM tipo_tel");
            foreach ($results as $v) {
                array_push($data['tipo_tel'], $v);
            }

            return $data;
        }

        public static function deleteTelefone($tel_id)
        {
            $sql = new \Sql();

            $data = [];
            $data["status"] = 1;
            $data["error"] = null;

            $results = $sql->select("SELECT tel_id FROM telefone WHERE usu_id = :id", [
                ":id" => $_SESSION[User::SESSION]['usu_id']
            ]);
            if (count($results) == 1) {
                $data['status'] = 0;
                $data["error"] = "Obrigatório ter pelo menos um telefone!";
            } else {
                $stmt = $sql->query("DELETE FROM telefone WHERE tel_id = :id", [
                    ":id" => $tel_id
                ]);
                if (!$stmt) {
                    $data['status'] = 0;
                    $data["error"] = "Ocorreu um erro ao deletar!";
                }
            }

            return $data;
        }

        public static function insertTelefone($post = [])
        {
            $sql = new \Sql();
            
            $data = [];
            $data["status"] = 1;
            $data["error"] = null;

            if (empty($post['tel_num'])) {
                $data["status"] = 0;
                $data["error"] = "<p>Não pode ser vazio</p>";
            } else {
                if (strlen($post['tel_num']) < 14) {
                    $data["status"] = 0;
                    $data["error"] = "<p>Informe o telefone corretamente, por favor!</p>";
                } else {
                    $results = $sql->select("SELECT * FROM telefone WHERE usu_id = :usu_id AND tel_num = :tel_num", [
                        ":tel_num" => $post['tel_num'], ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                    ]);
                    if (count($results) > 0) {
                        $data["status"] = 0;
                        $data["error"] = "<p>Este telefone já está cadastrado</p>";
                    } else {
                        $stmt = $sql->query("INSERT INTO telefone(tel_num, tpu_tel, usu_id) VALUE(:tel_num, :tpu_tel, :usu_id)", [
                            ":tel_num" => $post['tel_num'], ":tpu_tel" => $post['tipo_tel'], 
                            ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                        ]);

                        if (!$stmt) {
                            $data["status"] = 0;
                            $data["error"] = "<p>Ocorreu um erro ao inserir telefone</p>";
                        }
                    }
                }
            }

            return $data;
        }

        public static function updateTelefone($post = [])
        {
            $sql = new \Sql();
            $data = [];
            $data["status"] = 1;
            $data["error"] = null;

            $count_tel = count($post['telefone']);

            for ($c = 0; $c < $count_tel; $c++) {
                $tel_id[$c] = $post['tel_id'][$c];
                $telefone[$c] = $post['telefone'][$c];
                $tipo_tel[$c] = $post['tipo_tel'][$c];

                if (empty($telefone[$c]) || empty($tipo_tel[$c])) {
                    $data["error"] = "<p>Não pode haver campos vazios</p>";
                } else {
                    if (strlen($telefone[$c]) < 14) {
                        $data["error"] = "<p>Informe o(s) telefone(s) corretamente, por favor!</p>";
                    } else {
                        $results = $sql->select("SELECT * FROM telefone WHERE tel_id = :id", [
                            ":id" => $tel_id[$c]
                        ]);

                        if (($results[0]['tel_num'] != $telefone[$c]) || ($results[0]['tpu_tel'] != $tipo_tel[$c])) {
                            $changes[$c] = $post['tel_id'][$c];
                        }
                    }
                }

                if ($data['error']) {
                    break;
                }
            }

            if ($data['error']) {
                $data['status'] = 0;
            } else {
                if (!isset($changes)) {
                    $data['status'] = 0;
                    $data["error"] = "<p>Não houve alterações</p>";
                } else {
                    foreach ($changes as $k => $v) {
                        $stmt = $sql->query("UPDATE telefone SET tel_num = :num_tel, tpu_tel = :tpu_tel WHERE tel_id = :tel_id", [
                            ":num_tel" => $telefone[$k], ":tpu_tel" => $tipo_tel[$k], ":tel_id" => $tel_id[$k]
                        ]);
                        if (!$stmt) {
                            $data['status'] = 0;
                            $data["error"] = "<p>Ocorreu um erro ao alterar</p>";
                            break;
                        }
                    }
                }
            }

            return $data;
        }

        public static function checkToAddTelefone()
        {
            $sql = new \Sql();

            $data = [];
            $data["status"] = 1;

            $results = $sql->select("SELECT COUNT(tel_id) AS qtd_tel FROM telefone WHERE usu_id = :id", [
                ":id" => $_SESSION[User::SESSION]['usu_id']
            ]);
            if ($results[0]['qtd_tel'] == 5) {
                $data['status'] = 0;
            } else {
                $results = $sql->select("SELECT * FROM tipo_tel");
                foreach ($results as $v) {
                    $data['tipo_tel'][] = $v;
                }
            }

            return $data;
        }

        public static function changeEndereco($post = [])
        {
            $sql = new \Sql();

            $data = [];
            $data["status"] = 1;
            $data["error_list"] = [];

            if (empty($post["end_cep"])) {
                $data["error_list"]["#end_cep"] = "<p>Por favor, insira o CEP do seu logradouro ou da sua cidade neste campo</p>";
            } else {
                if (strlen($post["end_cep"]) < 9) {
                    $data["error_list"]["#end_cep"] = "<p>Por favor, insira seu CEP corretamente neste campo</p>";
                } else {
                    if (empty($post["end_uf"])) {
                        $data["error_list"]["#end_uf"] = "<p>Por favor, insira um <b>CEP</b> válido para que o endereço seja preenchido automaticamente</p>";
                    } else {
                        if (empty($post["end_log"])) {
                            $data["error_list"]["#end_log"] = "<p>Por favor, insira um <b>CEP</b> válido para que o endereço seja preenchido automaticamente</p>";
                        } else {
                            if (empty($post["end_bairro"])) {
                                $data["error_list"]["#end_bairro"] = "<p>Por favor, insira seu bairro neste campo</p>";
                            } else {
                                if (empty($post["end_num"])) {
                                    $data["error_list"]["#end_num"] = "<p>Por favor, insira o <b>número</b> de sua casa neste campo</p>";
                                } else {
                                    if (!is_numeric($post["end_num"])) {
                                        $data["error_list"]["#end_num"] = "<p>Somente números neste campo</p>";
                                    } else {
                                        if (($_SESSION[User::SESSION]['usu_cep'] == $post["end_cep"]) && 
                                            ($_SESSION[User::SESSION]['usu_end'] == $post["end_log"]) && 
                                            ($_SESSION[User::SESSION]['usu_num'] == $post["end_num"]) && 
                                            ($_SESSION[User::SESSION]['usu_complemento'] == $post["end_comp"]) && 
                                            ($_SESSION[User::SESSION]['usu_bairro'] == $post["end_bairro"]) && 
                                            ($_SESSION[User::SESSION]['usu_cidade'] == $post["end_cid"]) && 
                                            ($_SESSION[User::SESSION]['usu_uf'] == $post["end_uf"])) {
                                                $data["error_list"]["#btnSaveMudarEndereco"] = "<p>Não houve alterações</p>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($data["error_list"])) {
                $data["status"] = 0;
            } else {
                $stmt = $sql->query("UPDATE usuario SET usu_cep = :cep, usu_end = :log, usu_num = :num, usu_complemento = :comp, usu_bairro = :bai, usu_cidade = :cid, usu_uf = :uf WHERE usu_id = :usu_id", [
                    ":cep" => $post["end_cep"], ":log" => $post["end_log"], 
                    ":num" => $post["end_num"], ":comp" => $post["end_comp"],
                    ":bai" => $post["end_bairro"], ":cid" => $post["end_cid"],
                    ":uf" => $post["end_uf"], 
                    ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                ]);
                if ($stmt) {
                    $_SESSION[User::SESSION]['usu_cep'] = $post["end_cep"];
                    $_SESSION[User::SESSION]['usu_end'] = $post["end_log"];
                    $_SESSION[User::SESSION]['usu_num'] = $post["end_num"];
                    $_SESSION[User::SESSION]['usu_complemento'] = $post["end_comp"];
                    $_SESSION[User::SESSION]['usu_bairro'] = $post["end_bairro"];
                    $_SESSION[User::SESSION]['usu_cidade'] = $post["end_cid"];
                    $_SESSION[User::SESSION]['usu_uf'] = $post["end_uf"];
                } else {
                    $data['status'] = 0;
                    $data["error_list"]["#btnSaveMudarEndereco"] = "<p>Ocorreu um erro ao alterar</p>";
                }
            }

            return $data;
        }

        public static function changePhoto($newNome = null)
        {
            $sql = new \Sql();

            $stmt = $sql->query("UPDATE usuario SET foto = :nf WHERE usu_id = :usu_id", [
                ":nf" => $newNome,
                ":usu_id" => $_SESSION[User::SESSION]['usu_id']
            ]);

            if ($stmt === true) {
                $_SESSION[User::SESSION]['foto'] = $newNome;
                return true;
            } else return false;
        }

        public static function changeEmail($newEmail = null)
        {
            $sql = new \Sql();

            $stmt = $sql->query("UPDATE usuario SET email = :mail WHERE usu_id = :usu_id", [
                ":mail" => $newEmail,
                ":usu_id" => $_SESSION[User::SESSION]['usu_id']
            ]);

            if ($stmt === true) {
                $_SESSION[User::SESSION]['email'] = $newEmail;
                return true;
            } else return false;
        }

        public static function changeMailMkt($mkt)
        {
            $sql = new \Sql();

            $stmt = $sql->query("UPDATE usuario SET usu_mailmkt = :mkt WHERE usu_id = :usu_id", [
                ":mkt" => $mkt,
                ":usu_id" => $_SESSION[User::SESSION]['usu_id']
            ]);

            if ($stmt === true) {
                $_SESSION[User::SESSION]['usu_mailmkt'] = $mkt;
                return true;
            } else return false;
        }

        public static function changePassword($currentPass = null, $newPass = null, $newPassConfirm = null)
        {
            $sql = new \Sql();

            $data = [];
            $data["status"] = 1;
            $data["error_list"] = [];
    
            if (empty($currentPass)) {
                $data["error_list"]["#senha_atual"] = "<p style='width=100%;text-align:center;margin-bottom:-20px;color#333;'>Insira sua senha atual neste campo</p>";
            } else {
                if (password_verify($currentPass, $_SESSION[User::SESSION]['usu_senha'])) {
                    if (empty($newPass)) {
                        $data["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-20px;color#333;'>Insira uma nova senha neste campo</p>";
                    } else {
                        if (strpos($newPass, " ") !== false) {
                            $data["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-20px;color#333;'>Não pode haver espaços, por favor!</p>";
                        } else {
                            if ((strlen($newPass) < 6) || (strlen($newPass) > 14)) {
                                $data["error_list"]["#senha_nova"] = "<p style='width=100%;text-align:center;margin-bottom:-20px;color#333;'>Por favor, mínimo de 6 caracteres e máximo de 14!</p>";
                            } else {
                                if ($newPass !== $newPassConfirm) {
                                    $data["error_list"]["#senha_nova"] = "";
                                    $data["error_list"]["#senha_nova_confirme"] = "<p style='width=100%;text-align:center;margin-bottom:-20px;color#333;'>Senhas não conferem!</p>";
                                }
                            }
                        }
                    }
                } else {
                    $data["error_list"]["#senha_atual"] = "<p style='width=100%;text-align:center;margin-bottom:-20px;color#333;'>Senha atual incorreta</p>";
                }
            }

            if (!empty($data["error_list"])) {
                $data["status"] = 0;
            } else {
                $newPass = \Project::hashPasswordGenerator($newPass);
                
                $stmt = $sql->query("UPDATE usuario SET usu_senha = :ns WHERE usu_id = :usu_id", [
                    ":ns" => $newPass,
                    ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                ]);

                if ($stmt === true) {
                    $_SESSION[User::SESSION]['usu_senha'] = $newPass;
                } else {
                    $data["status"] = 0;
                }
            }

            return $data;
        }

        public static function getByCodeConfirmMail($code)
        {
            $cf_id = \Project::opensslCrypt($code, false);
            $sql = new \Sql();
            
            $results = $sql->select("SELECT * FROM confirmar_email c JOIN usuario u ON c.usu_id = u.usu_id WHERE c.cf_id = :cf_id", array(
                ":cf_id" => $cf_id
            ));

            if (count($results) > 0) return $results[0];
            else return false;
        }

        public static function createConfirmEmail($usu_id)
        {
            $sql = new \Sql();

            $results = $sql->select("CALL sp_confirmaremail_create(:usu_id)", [
                ":usu_id" => $usu_id
            ]);

            if (count($results) > 0) return $results[0];
            else return false;
        }

        public static function validConfirmMailDecrypt($code = null)
        {
            $cf_id = \Project::opensslCrypt($code, false);
            $sql = new \Sql();
            
            $results = $sql->select("SELECT * FROM confirmar_email c JOIN usuario u ON c.usu_id = u.usu_id WHERE c.cf_id = :cf_id AND c.cf_feito IS NULL AND DATE_ADD(c.cf_registro, INTERVAL 1 HOUR) >= NOW()", array(
                ":cf_id" => $cf_id
            ));

            if (count($results) === 0) {
                $results = $sql->select("SELECT * FROM confirmar_email c JOIN usuario u ON c.usu_id = u.usu_id WHERE c.cf_id = :cf_id AND c.cf_feito IS NOT NULL", array(
                    ":cf_id" => $cf_id
                ));

                if (count($results) > 0) {
                    $results[0]['verificado'] = true;
                    return $results[0];
                } else {
                    $results = $sql->select("SELECT * FROM confirmar_email c JOIN usuario u ON c.usu_id = u.usu_id WHERE c.cf_id = :cf_id AND c.cf_feito IS NULL", array(
                        ":cf_id" => $cf_id
                    ));

                    if (count($results) > 0) {
                        $results[0]['solicita'] = true;
                        return $results[0];
                    } else {
                        return 0;
                    }
                }
            } else {
                return $results[0];
            }
        }

        public static function setValidateConfirmMail($cf_id)
        {
            $sql = new \Sql();

            $results = $sql->select("SELECT * FROM confirmar_email c JOIN usuario u ON c.usu_id = u.usu_id WHERE c.cf_id = :cf_id AND c.cf_feito IS NULL AND DATE_ADD(c.cf_registro, INTERVAL 1 HOUR) >= NOW()", [
                ":cf_id" => $cf_id
            ]);

            if (count($results) > 0) {
                $stmt = $sql->query("UPDATE confirmar_email SET cf_feito = NOW() WHERE cf_id = :cf_id", [
                    ":cf_id" => $cf_id
                ]);

                return true;
            } else {
                return false;
            }
        }

        public static function getForgot($email = null): array
        {
            $sql = new \Sql();

            $data = [];
            $data["status"] = 1;
            $data["error"] = null;

            $results = $sql->select("SELECT * FROM usuario WHERE usu_email = :email", [
                ":email" => $email
            ]);

            if (count($results) > 0) {
                $confirmaMail = $sql->select("SELECT * FROM confirmar_email WHERE usu_id = :usu_id AND cf_feito IS NOT NULL", [
                    ":usu_id" => $results[0]['usu_id']
                ]);

                if (count($confirmaMail) > 0) {
                    if ((int)$results[0]['usu_cstatus'] === 1) {
                        User::createForgot($results[0]);
                    } else {
                        $data["status"] = 0;
                        $data["error"] = "<p style='color:#A94442;'><b>Esta conta está desativada!</b></p>";
                    }
                } else {
                    $data["status"] = 0;
                    $data["error"] = "<p style='color:#A94442;'><b>Esta conta precisa confirmar o email primeiro.</b></p>";
                }
            } else {
                $data["status"] = 0;
                $data["error"] = "<p style='color:#A94442;'><b>Email incorreto ou inexistente!</b></p>";
            }

            return $data;
        }

        public static function createForgot($userSearch = null)
        {
            if ($userSearch === null) {
                $user = User::getFromSession();
            } else {
                $user = new User();
                $user->setData($userSearch);

                $user->setdatanasc(\Project::formatDate($user->getusu_datanasc()));
                $user->setusu_registro(\Project::formatDate($user->getusu_registro()));
                $user->setusu_registro_hora(\Project::formatRegister($user->getusu_registro()));
                $user->setgenero(\Project::formatGenero($user->getgenero()));
            }

            $sql = new \Sql();

            $data = [];
            $data["status"] = 1;
            $data["error"] = null;
            $data['user'] = $user->getValues();

            $results = $sql->select("SELECT rec_id FROM recuperar_senha WHERE usu_id = :usu_id AND rec_data IS NOT NULL AND DATE_ADD(rec_registro, INTERVAL 24 HOUR) >= NOW()", array(
                ":usu_id" => $data['user']['usu_id']
            ));

            if (count($results) === 0) {
                $results = $sql->select("CALL sp_recuperarsenha_create(:usu_id)", array(
                    ":usu_id" => $data['user']['usu_id']
                ));

                if (count($results) > 0) {
                    $dataRecovery = $results[0];
                    $code = \Project::opensslCrypt($dataRecovery["rec_id"]);
                    $dataRecovery['link'] = \Project::baseUrlPhp() . "usuario/reset?code={$code}";
                    $data['recovery'] = $dataRecovery;
                } else {
                    $data["status"] = 0;
                    $data["error"] = "<p style='color:#A94442;'><b>Um erro inesperado ocorreu</b></p>";
                }
            } else {
                $data["status"] = 0;
                $data["error"] = "<p style='color:#A94442;'><b>Email já recuperou a senha em menos de 24 horas</b></p>";
            }

            return $data;
        }

        public static function validForgotDecrypt($code = null)
        {
            $rec_id = \Project::opensslCrypt($code, false);
            $sql = new \Sql();
            
            $results = $sql->select("SELECT * FROM recuperar_senha r JOIN usuario u ON r.usu_id = u.usu_id WHERE r.rec_id = :rec_id", array(
                ":rec_id" => $rec_id
            ));

            if (count($results) === 0) return false;
            else return $results[0];
        }

        public static function setForgot($code, $password, $usu_id)
        {
            $rec_id = \Project::opensslCrypt($code, false);
            $sql = new \Sql();
            
            $results = $sql->select("SELECT * FROM recuperar_senha r JOIN usuario u ON r.usu_id = u.usu_id WHERE r.rec_id = :rec_id AND r.rec_data IS NULL AND DATE_ADD(r.rec_registro, INTERVAL 1 HOUR) >= NOW()", array(
                ":rec_id" => $rec_id
            ));

            if (count($results) > 0) {
                $stmt = $sql->query("UPDATE recuperar_senha SET rec_data = NOW() WHERE rec_id = :rec_id", array(
                    ":rec_id" => $rec_id
                ));

                $stmt = $sql->query("UPDATE usuario SET usu_senha = :password WHERE usu_id = :usu_id", array(
                    ":password" => $password,
                    ":usu_id" => $usu_id
                ));

                return $stmt;
            } else {
                return false;
            }
        }
    }
