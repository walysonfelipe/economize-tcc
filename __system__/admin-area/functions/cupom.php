<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['cupom_codigo'])) {
            $json['error'] = NULL;

            for($k=0; $k<count($_POST['cupom_codigo']); $k++) {
                $c = $k + 1;
                $cupom_codigo[$k] = trim($_POST['cupom_codigo'][$k]);
                $cupom_desconto_porcent[$k] = $_POST['cupom_desconto_porcent'][$k];

                if(empty($cupom_codigo[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o código do cupom na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(empty($cupom_desconto_porcent[$k])) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o desconto porcentado na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        if(($cupom_desconto_porcent[$k] < 1) || ($cupom_desconto_porcent[$k] > 100)) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O desconto na ' . $c . 'ª parte de cadastro tem de ser mair que 0 e menor ou igual a 100</b></p>';
                        } else {
                            $sel = $conn->prepare("SELECT cupom_codigo FROM cupom WHERE cupom_codigo='{$cupom_codigo[$k]}'");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                $res = $sel->fetchAll();
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O código ' . $res[0]['cupom_codigo'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrado</b></p>';
                            } else {
                                $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
                                $sel->execute();
                                $res = $sel->fetchAll();
                                $permicoes = explode("-", $res[0]['setor_permicao']);
                                if(!in_array("a", $permicoes)) {
                                    $json['error'] = '<p style="color:red;text-align:center;"><b>Você não tem permição para adicionar</b></p>';
                                }
                            }
                        }
                    }
                }

                if($json['error']) {
                    break;
                }
            }

            if($json['error']) {
                $json['status'] = 0;
            } else {
                for($k=0; $k<count($cupom_codigo); $k++) {
                    $ins = $conn->prepare("INSERT INTO cupom(cupom_codigo, cupom_desconto_porcent) VALUES ('{$cupom_codigo[$k]}', {$cupom_desconto_porcent[$k]})");

                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }
        } elseif(isset($_POST['updCupom_id'])) {
            $json['cupom'] = NULL;
            $sel = $conn->prepare("SELECT * FROM cupom WHERE cupom_id={$_POST['updCupom_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $cupom = $sel->fetchAll();
                    foreach($cupom as $v) {
                        $json['cupom'] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['cupom_codigoUpd'])) {
            $json['error'] = NULL;

            $cupom_id = $_POST['cupom_idUpd'];
            $cupom_codigo = trim($_POST['cupom_codigoUpd']);
            $cupom_desconto_porcent = $_POST['cupom_desconto_porcentUpd'];

            if(empty($cupom_codigo)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o código do cupom</b></p>';
            } else {
                if(empty($cupom_desconto_porcent)) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o desconto porcentado</b></p>';
                } else {
                    if(($cupom_desconto_porcent < 1) || ($cupom_desconto_porcent > 100)) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O desconto tem de ser mair que 0 e menor ou igual a 100</b></p>';
                    } else {
                        $sel = $conn->prepare("SELECT cupom_codigo FROM cupom WHERE cupom_codigo='{$cupom_codigo}' AND cupom_id <> {$cupom_id}");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O código ' . $res[0]['cupom_codigo'] . ' que inseriu parte já foi previamente cadastrado</b></p>';
                        } else {
                            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
                            $sel->execute();
                            $res = $sel->fetchAll();
                            $permicoes = explode("-", $res[0]['setor_permicao']);
                            if(!in_array("e", $permicoes)) {
                                $json['error'] = '<p style="color:red;text-align:center;"><b>Você não tem permição para editar</b></p>';
                            }
                        }
                    }
                }
            }

            if($json['error']) {
                $json['status'] = 0;
            } else {
                $upd = $conn->prepare("UPDATE cupom SET cupom_codigo='$cupom_codigo', cupom_desconto_porcent=$cupom_desconto_porcent WHERE cupom_id=$cupom_id");

                if(!$upd->execute()) {
                    $json['status'] = 0;
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                }
            }
        } elseif(isset($_POST['delCupom_id'])) {
            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(!in_array("e", $permicoes)) {
                $json['status'] = 0;
                $json['error_del'] = 'Você não tem permição para excluir';
            } else {
                $del = $conn->prepare("DELETE FROM cupom WHERE cupom_id=:id");
                $del->bindValue(":id", "{$_POST['delCupom_id']}");
                if(!$del->execute()) {
                    $json['status'] = 0;
                    $json['error_del'] = "Código erro: " . $del->errorCode();
                }
            }
        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(cupom_id) AS qtd FROM cupom");
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

            $json['cupons'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM cupom ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM cupom LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $cup = $sel->fetchAll();
                foreach($cup as $v) {
                    $json['cupons'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchCupom'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['cupons'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(cupom_id) AS qtd FROM cupom WHERE cupom_codigo LIKE '%{$_POST['searchCupom']}%' OR cupom_desconto_porcent LIKE '%{$_POST['searchCupom']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM cupom WHERE cupom_codigo LIKE '%{$_POST['searchCupom']}%' OR cupom_desconto_porcent LIKE '%{$_POST['searchCupom']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $json['cupons'][] = $v;
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
            $json['cupons'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(cupom_id) AS qtd FROM cupom");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM cupom LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $json['cupons'][] = $v;
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