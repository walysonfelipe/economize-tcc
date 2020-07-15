<?php
    namespace Model;

    use \Model\User;
    
    class Admin extends \Model
    {
        const SESSION = "EconomizeAdmin";
        const RECAPTCHA_KEY = "6Lc-kMMUAAAAABZGOUwsLl9hd9zrbmTw67suDeEd";

        public static function permitionOnSystem($area = null, $determ = array()): bool
        {
            if (Admin::checkLogin() === true) {
                $idcargo = (int)$_SESSION[Admin::SESSION]["idcargo"];

                if (empty($determ)) {
                    if (($area === "lista-espera") || ($area === "educacional")) {
                        
                        if (((int)$idcargo !== 2) && ((int)$idcargo !== 5)) {
                            return true;
                        } else {
                            return false;
                        }

                    } elseif (($area === "ficha-social") || ($area === "ferramentas")) {

                        if (((int)$idcargo === 3) || ((int)$idcargo === 1)) {
                            return true;
                        } else {
                            return false;
                        }
                        
                    } elseif ($area === "matricula") {
                        
                    } elseif ($area === "administrador") {
                        if ((int)$idcargo === 5) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if (in_array($idcargo, $determ)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }

        public static function verifyMaintenance(bool $verifyByCharge = true): bool
        {
            /* 
                -- Caso a variável $verifyByCharge esteja como 'false', 
                o método não fará a verificação se o cargo do usuário é 'Desenvolvedor' --
            */
            $maintenance = false;

            $sql = new \Sql();
            $results = $sql->select("SELECT status_sistema FROM sistema");
            $v = $results[0];

            if (Admin::checkLogin() === true) {
                $charge = (int)$_SESSION[Admin::SESSION]["idcargo"];
                
                if ($verifyByCharge === true) {
                    if ($charge !== 5) {
                        if ((int)$v['status_sistema'] !== 0) {
                            $maintenance = true;
                        }
                    }
                } else {
                    if ((int)$v['status_sistema'] !== 0) {
                        $maintenance = true;
                    }
                }
            } else {
                if ((int)$v['status_sistema'] !== 0) {
                    $maintenance = true;
                }
            }

            return $maintenance;
        }

        public static function getFromSession()
        {
            $user = new Admin();

            if (isset($_SESSION[Admin::SESSION]) && (int)$_SESSION[Admin::SESSION]["iduser"] > 0) {
                $user->setData($_SESSION[Admin::SESSION]);
            }

            return $user;
        }

        public static function login($cpf, $password, $recaptcha)
        {
            $json = array();
            $json["status"] = 1;
            $json["error"] = null;
            
            if (\Project::validarCPF($cpf) == true) {
                $sql = new \Sql();

                $results = $sql->select("SELECT * FROM funcionario f JOIN setor s ON f.funcionario_setor = s.setor_id WHERE f.funcionario_cpf = :cpf", [
                    ":cpf" => $cpf
                ]);
                
                if (count($results) > 0) {
                    if (password_verify($password, $results[0]["funcionario_senha"])) {
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                            "secret" => Admin::RECAPTCHA_KEY,
                            "response" => $recaptcha,
                            "remoteip" => $_SERVER["REMOTE_ADDR"]
                        )));

                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        $recaptcha_answer = json_decode(curl_exec($ch), true);

                        curl_close($ch);
                        
                        if ($recaptcha_answer["success"] === true) {
                            if ($results[0]['funcionario_conta'] == "1") {
                                
                                session_regenerate_id();
                                $results[0]['session_id'] = session_id();
                                
                                $admin = new Admin();
                                $admin->setData($results[0]);

                                $admin->setfuncionario_datanasc(\Project::formatDate($admin->getdatanasc()));
                                $admin->setfuncionario_registro(\Project::formatRegister($admin->getregistro()));
                                
                                $_SESSION[Admin::SESSION] = $admin->getValues();
                            } else {
                                $json["status"] = 0;
                                $json["error"] = "<p style='text-align:center;color:#A94442;'><b>Esta conta foi desativada! (Acesso negado)</b></p>";
                            }
                        } else {
                            $json["status"] = 0;
                            $json["error"] = "<p style='text-align:center;color:#A94442;'><b>reCAPTCHA inválido!</b></p>";
                        }
                    } else {
                        $json["status"] = 0;
                        $json["error"] = "<p style='text-align:center;color:#A94442;'><b>Senha inválida ou incorreta!</b></p>";
                    }
                } else {
                    $json["status"] = 0;
                    $json["error"] = "<p style='text-align:center;color:#A94442;'><b>CPF incorreto ou inexistente!</b></p>";
                }
            } else {
                $json["status"] = 0;
                $json["error"] = "<p style='text-align:center;color:#A94442;'><b>Insira um CPF válido, por favor.</b></p>";
            }
            
            echo json_encode($json);
        }

        public static function checkLogin()
        {
            if (
                !isset($_SESSION[Admin::SESSION]) || 
                !$_SESSION[Admin::SESSION] || 
                !(int)$_SESSION[Admin::SESSION]["funcionario_id"] > 0
            ) {
                return false;
            } else {
                return true;
            }
        }

        public static function checkLoginAndRedirect()
        {
            if (
                !isset($_SESSION[Admin::SESSION]) || 
                !$_SESSION[Admin::SESSION] || 
                !(int)$_SESSION[Admin::SESSION]["funcionario_id"] > 0
            ) {
                header("Location: " . \Project::baseUrlAdmPhp() . "login");
                exit;
            } else {
                return true;
            }
        }

        public static function logout()
        {
            $_SESSION[Admin::SESSION] = null;
            header("Location: " . \Project::baseUrlAdmPhp() . "login");
            exit;
        }

        public static function changePhoto($newNome = null)
        {
            $sql = new \Sql();

            $stmt = $sql->query("UPDATE funcionario SET foto = :nf WHERE idfunc = :idfunc", [
                ":nf" => $newNome,
                ":idfunc" => $_SESSION[Admin::SESSION]['idfunc']
            ]);

            if ($stmt === true) {
                $_SESSION[Admin::SESSION]['foto'] = $newNome;
                return true;
            } else return false;
        }

        public static function changeEmail($newEmail = null)
        {
            $sql = new \Sql();

            $stmt = $sql->query("UPDATE funcionario SET email = :mail WHERE idfunc = :idfunc", [
                ":mail" => $newEmail,
                ":idfunc" => $_SESSION[Admin::SESSION]['idfunc']
            ]);

            if ($stmt === true) {
                $_SESSION[Admin::SESSION]['email'] = $newEmail;
                return true;
            } else return false;
        }

        public static function changePassword($newPass = null)
        {
            $sql = new \Sql();

            $newPass = password_hash($newPass, PASSWORD_DEFAULT);
            
            $stmt = $sql->query("UPDATE funcionario SET senha = :ns WHERE idfunc = :idfunc", [
                ":ns" => $newPass,
                ":idfunc" => $_SESSION[Admin::SESSION]['idfunc']
            ]);

            if ($stmt === true) {
                $_SESSION[Admin::SESSION]['senha'] = $newPass;
                return true;
            } else return false;
        }

        public static function getForgot($cpf = null, $recaptcha = null): array
        {
            $sql = new \Sql();

            $json["status"] = 1;
            $json["error"] = null;

            $results = $sql->select("SELECT * FROM funcionario f JOIN cargo c ON f.cargo = c.idcargo WHERE f.cpf=:cpf", [
                ":cpf" => $cpf
            ]);

            if (count($results) > 0) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                    "secret" => Admin::RECAPTCHA_KEY,
                    "response" => $recaptcha,
                    "remoteip" => $_SERVER["REMOTE_ADDR"]
                )));

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $recaptcha_answer = json_decode(curl_exec($ch), true);

                curl_close($ch);
                
                if ($recaptcha_answer["success"] === true) {
                    if ($results[0]['status_conta'] == "1") {
                        $user = new Admin();
                        $user->setData($results[0]);

                        $user->setdatanasc(\Project::formatDate($user->getdatanasc()));
                        $user->setregistro(\Project::formatRegister($user->getregistro()));
                        $user->setgenero(\Project::formatGenero($user->getgenero()));

                        $json['user'] = $user->getValues();

                        $results = $sql->select("SELECT idrecupera FROM recuperar_senha WHERE funcionario = :idfunc AND dtrecupera IS NOT NULL AND DATE_ADD(registro, INTERVAL 24 HOUR) >= NOW()", array(
                            ":idfunc" => $json['user']['idfunc']
                        ));

                        if (count($results) === 0) {
                            $results = $sql->select("CALL sp_recuperarsenha_create(:idfunc, :desip)", array(
                                ":idfunc" => $json['user']['idfunc'],
                                ":desip" => $_SERVER["REMOTE_ADDR"]
                            ));
            
                            if (count($results) > 0) {
                                $dataRecovery = $results[0];
            
                                $code = base64_encode(openssl_encrypt(
                                    $dataRecovery["idrecupera"],
                                    'AES-128-CBC',
                                    User::SECRET,
                                    0,
                                    User::SECRET_IV
                                ));
            
                                $dataRecovery['link'] = \Project::baseUrlPhp() . "conta/reset?code={$code}";

                                $json['recovery'] = $dataRecovery;
                            } else {
                                $json["status"] = 0;
                                $json["error"] = "<p style='color:#A94442;'><b>Um erro inesperado ocorreu</b></p>";
                            }
                        } else {
                            $json["status"] = 0;
                            $json["error"] = "<p style='color:#A94442;'><b>CPF já recuperou a senha em menos de 24 horas</b></p>";
                        }
                    } else {
                        $json["status"] = 0;
                        $json["error"] = "<p style='color:#A94442;'><b>Esta conta foi desativada! (Acesso negado)</b></p>";
                    }
                } else {
                    $json["status"] = 0;
                    $json["error"] = "<p style='color:#A94442;'><b>reCAPTCHA inválido!</b></p>";
                }
            } else {
                $json["status"] = 0;
                $json["error"] = "<p style='color:#A94442;'><b>CPF incorreto ou inexistente!</b></p>";
            }

            return $json;
        }

        public static function validForgotDecrypt($code = null, $ip = null)
        {
            $idrecupera = openssl_decrypt(
                base64_decode($code),
                'AES-128-CBC',
                User::SECRET,
                0,
                User::SECRET_IV
            );

            $sql = new \Sql();
            
            $results = $sql->select("SELECT * FROM recuperar_senha r JOIN funcionario f ON r.funcionario = f.idfunc WHERE r.idrecupera = :idrecupera AND r.dtrecupera IS NULL AND DATE_ADD(r.registro, INTERVAL 1 HOUR) >= NOW()", array(
                ":idrecupera" => $idrecupera
            ));

            if (count($results) === 0) return false;
            else {
                if ($ip == $results[0]['ip']) return $results[0];
                else return false;
            }
        }

        public static function setForgot($code, $password, $idfunc)
        {
            $sql = new \Sql();

            $idrecupera = openssl_decrypt(
                base64_decode($code),
                'AES-128-CBC',
                User::SECRET,
                0,
                User::SECRET_IV
            );

            $results = $sql->select("SELECT * FROM recuperar_senha r JOIN funcionario f ON r.funcionario = f.idfunc WHERE r.idrecupera = :idrecupera AND r.dtrecupera IS NULL AND DATE_ADD(r.registro, INTERVAL 1 HOUR) >= NOW()", array(
                ":idrecupera" => $idrecupera
            ));

            if (count($results) > 0) {
                $stmt = $sql->query("UPDATE recuperar_senha SET dtrecupera = NOW() WHERE idrecupera = :idrecupera", array(
                    ":idrecupera" => $idrecupera
                ));

                $stmt = $sql->query("UPDATE funcionario SET senha = :password WHERE idfunc = :idfunc", array(
                    ":password" => $password,
                    ":idfunc" => $idfunc
                ));

                return $stmt;
            } else {
                return false;
            }
        }
    }
