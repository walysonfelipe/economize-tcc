<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;

        if(isset($_POST['funcionario_nome'])) {
            function validaData($dat) {
                $ret = TRUE;
                $data = explode("/","$dat");
                $d = $data[0];
                $m = $data[1];
                $y = $data[2];

                $res = checkdate($m,$d,$y);
                if(!$res) {
                   $ret = FALSE;
                }

                return $ret;
            }
            for($k=0; $k<count($_POST['funcionario_nome']); $k++) {
                $c = $k + 1;
                $funcionario_nome[$k] = $_POST['funcionario_nome'][$k];
                $funcionario_email[$k] = $_POST['funcionario_email'][$k];
                $funcionario_senha[$k] = $_POST['funcionario_senha'][$k];
                $funcionario_cpf[$k] = $_POST['funcionario_cpf'][$k];
                $funcionario_datanasc[$k] = $_POST['funcionario_datanasc'][$k];
                $funcionario_setor[$k] = $_POST['funcionario_setor'][$k];

                if(empty($funcionario_nome[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(empty($funcionario_email[$k])) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o email do funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        if(!filter_var($funcionario_email[$k], FILTER_VALIDATE_EMAIL)) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira um email válido do funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                        } else {
                            $verifica = $conn->prepare("SELECT funcionario_email FROM funcionario WHERE funcionario_email=:email");
                            $verifica->bindValue(":email", "{$funcionario_email[$k]}");
                            $verifica->execute();
                            if($verifica->rowCount() > 0) {
                                $res = $verifica->fetchAll();
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O email ' . $res[0]['funcionario_email'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrado</b></p>';
                            } else {
                                if(empty($funcionario_cpf[$k])) {
                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o CPF do funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                                } else {
                                    if(strlen($funcionario_cpf[$k]) < 14) {
                                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira um CPF válido pro funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                                    } else {
                                        $verifica = $conn->prepare("SELECT funcionario_cpf FROM funcionario WHERE funcionario_cpf=:cpf");
                                        $verifica->bindValue(":cpf", "{$funcionario_cpf[$k]}");
                                        $verifica->execute();
                                        if($verifica->rowCount() > 0) {
                                            $res = $sel->fetchAll();
                                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O CPF ' . $res[0]['funcionario_cpf'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrado</b></p>';
                                        } else {
                                            if(empty($funcionario_datanasc[$k])) {
                                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a data de nascimento do funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                                            } else {
                                                if(strlen($funcionario_datanasc[$k]) < 10) {
                                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira uma data de nascimento válida pro funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                                                } else {
                                                    $valida_data = validaData($funcionario_datanasc[$k]);
                                                    if(!$valida_data) {
                                                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira uma data de nascimento válida pro funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                                                    } else {
                                                        if($funcionario_setor[$k] == "*000*") {
                                                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Escolha um setor pro funcionário na ' . $c . 'ª parte de cadastro</b></p>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if($json['error']) {
                    break;
                }
            }

            if($json['error'] != NULL) {
                $json['status'] = 0;
            } else {
                for($k = 0; $k < count($funcionario_nome); $k++) {
                    $funcionario_senha[$k] = password_hash($funcionario_senha[$k], PASSWORD_DEFAULT);
                    $funcionario_datanasc[$k] = substr($funcionario_datanasc[$k],-4) . "-" . substr($funcionario_datanasc[$k],3,2) . "-" . substr($funcionario_datanasc[$k],0,2);
                    $json['data'] = $funcionario_datanasc[$k];
                    
                    $ins = $conn->prepare("INSERT INTO funcionario(funcionario_nome, funcionario_email, funcionario_senha, funcionario_cpf, funcionario_datanasc, funcionario_setor) VALUES ('{$funcionario_nome[$k]}', '{$funcionario_email[$k]}', '{$funcionario_senha[$k]}', '{$funcionario_cpf[$k]}', '{$funcionario_datanasc[$k]}', {$funcionario_setor[$k]})");
                
                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }

        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(f.funcionario_id) AS qtd FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sort = $_POST['data_sort'];

            if(!isset($_POST['sec'])) {
                $json['sort'] = "up";
                if(!isset($_SESSION['data_sort'][$sort])) {
                    $_SESSION['data_sort'][$sort] = "ASC";
                } else {
                    if($_SESSION['data_sort'][$sort] == "ASC") {
                        $_SESSION['data_sort'][$sort] = "DESC";
                        $json['sort'] = "down";
                    } else {
                        unset($_SESSION['data_sort'][$sort]);
                        $json['sort'] = "none";
                    }
                }
            }

            $json['funcionarios'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT f.funcionario_id, f.funcionario_nome, f.funcionario_cpf, f.funcionario_datanasc, f.funcionario_registro, s.setor_nome FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT f.funcionario_id, f.funcionario_nome, f.funcionario_cpf, f.funcionario_datanasc, f.funcionario_registro, s.setor_nome FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $func = $sel->fetchAll();
                foreach($func as $v) {
                    $dia = explode("-", $v['funcionario_datanasc']);
                    $v['funcionario_datanasc'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0];

                    $exp = explode(" ", $v['funcionario_registro']);
                    $dia = explode("-", $exp[0]);
                    $v['funcionario_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                    $json['funcionarios'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchFunc'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['funcionarios'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(f.funcionario_id) AS qtd FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_nome LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_cpf LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_email LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_registro LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_datanasc LIKE '%{$_POST['searchFunc']}%' OR s.setor_nome LIKE '%{$_POST['searchFunc']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT f.funcionario_id, f.funcionario_nome, f.funcionario_cpf, f.funcionario_datanasc, f.funcionario_registro, s.setor_nome FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_nome LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_cpf LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_email LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_registro LIKE '%{$_POST['searchFunc']}%' OR f.funcionario_datanasc LIKE '%{$_POST['searchFunc']}%' OR s.setor_nome LIKE '%{$_POST['searchFunc']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $dia = explode("-", $v['funcionario_datanasc']);
                        $v['funcionario_datanasc'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0];

                        $exp = explode(" ", $v['funcionario_registro']);
                        $dia = explode("-", $exp[0]);
                        $v['funcionario_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        $json['funcionarios'][] = $v;
                        $json['registrosMostra']++;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } else {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['funcionarios'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(f.funcionario_id) AS qtd FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT f.funcionario_id, f.funcionario_nome, f.funcionario_cpf, f.funcionario_datanasc, f.funcionario_registro, s.setor_nome FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $dia = explode("-", $v['funcionario_datanasc']);
                        $v['funcionario_datanasc'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0];

                        $exp = explode(" ", $v['funcionario_registro']);
                        $dia = explode("-", $exp[0]);
                        $v['funcionario_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        $json['funcionarios'][] = $v;
                        $json['registrosMostra']++;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        }

        echo json_encode($json);
    }
?>