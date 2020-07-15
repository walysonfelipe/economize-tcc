<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;
        
        if(isset($_POST['fornecedor_nome'])) {
            for($k=0; $k<count($_POST['fornecedor_nome']); $k++) {
                $c = $k + 1;
                $fornecedor_nome[$k] = $_POST['fornecedor_nome'][$k];
                $fornecedor_responsavel_nome[$k] = $_POST['fornecedor_responsavel_nome'][$k];
                $fornecedor_cnpj[$k] = $_POST['fornecedor_cnpj'][$k];

                if(empty($fornecedor_nome[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do fornecedor na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(empty($fornecedor_responsavel_nome[$k])) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira nome do responsável na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        if(empty($fornecedor_cnpj[$k])) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o CNPJ do fornecedor na ' . $c . 'ª parte de cadastro</b></p>';
                        } else {
                            $sel = $conn->prepare("SELECT fornecedor_nome FROM fornecedor WHERE fornecedor_nome='{$fornecedor_nome[$k]}'");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                $res = $sel->fetchAll();
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O fornecedor ' . $res[0]['fornecedor_nome'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrado</b></p>';
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
                for($k=0; $k<count($fornecedor_nome); $k++) {
                    $ins = $conn->prepare("INSERT INTO fornecedor(fornecedor_nome, fornecedor_responsavel_nome, fornecedor_cnpj) VALUES ('{$fornecedor_nome[$k]}', '{$fornecedor_responsavel_nome[$k]}', '{$fornecedor_cnpj[$k]}')");
                    
                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }
        } elseif(isset($_POST['updFornecedor_id'])) {
            $json['fornecedor'] = NULL;
            $sel = $conn->prepare("SELECT * FROM fornecedor WHERE fornecedor_id={$_POST['updFornecedor_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $fornecedor = $sel->fetchAll();
                    foreach($fornecedor as $v) {
                        $json['fornecedor'] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['fornecedor_nomeUpd'])) {
            $json['error'] = NULL;

            $fornecedor_id = intval($_POST['fornecedor_idUpd']);
            $fornecedor_nome = trim($_POST['fornecedor_nomeUpd']);
            $fornecedor_responsavel_nome = $_POST['fornecedor_responsavel_nomeUpd'];
            $fornecedor_cnpj = trim($_POST['fornecedor_cnpjUpd']);

            if(empty($fornecedor_nome)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do fornecedor</b></p>';
            } else {
                if(empty($fornecedor_responsavel_nome)) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira nome do responsável</b></p>';
                } else {
                    if(empty($fornecedor_cnpj)) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o CNPJ do fornecedor</b></p>';
                    } else {
                        $sel = $conn->prepare("SELECT fornecedor_nome FROM fornecedor WHERE fornecedor_nome='{$fornecedor_nome}' AND fornecedor_id <> {$fornecedor_id}");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O fornecedor ' . $res[0]['fornecedor_nome'] . ' que inseriu já foi previamente cadastrado</b></p>';
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
                $upd = $conn->prepare("UPDATE fornecedor SET fornecedor_nome='$fornecedor_nome', fornecedor_responsavel_nome='$fornecedor_responsavel_nome', fornecedor_cnpj='$fornecedor_cnpj' WHERE fornecedor_id=$fornecedor_id");

                if(!$upd->execute()) {
                    $json['status'] = 0;
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                }
            }
        } elseif(isset($_POST['delFornecedor_id'])) {
            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(!in_array("e", $permicoes)) {
                $json['status'] = 0;
                $json['error_del'] = 'Você não tem permição para excluir';
            } else {
                $del = $conn->prepare("DELETE FROM fornecedor WHERE fornecedor_id=:id");
                $del->bindValue(":id", "{$_POST['delFornecedor_id']}");
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

            $sel = $conn->prepare("SELECT COUNT(fornecedor_id) AS qtd FROM fornecedor");
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

            $json['fornecedores'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM fornecedor ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM fornecedor LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $forn = $sel->fetchAll();
                foreach($forn as $v) {
                    $exp = explode(" ", $v['fornecedor_data_registro']);
                    $dia = explode("-", $exp[0]);
                    $v['fornecedor_data_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                    $json['fornecedores'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchFornecedor'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['fornecedores'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(fornecedor_id) AS qtd FROM fornecedor WHERE fornecedor_nome LIKE '%{$_POST['searchFornecedor']}%' OR fornecedor_responsavel_nome LIKE '%{$_POST['searchFornecedor']}%' OR fornecedor_cnpj LIKE '%{$_POST['searchFornecedor']}%' OR fornecedor_data_registro LIKE '%{$_POST['searchFornecedor']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM fornecedor WHERE fornecedor_nome LIKE '%{$_POST['searchFornecedor']}%' OR fornecedor_responsavel_nome LIKE '%{$_POST['searchFornecedor']}%' OR fornecedor_cnpj LIKE '%{$_POST['searchFornecedor']}%' OR fornecedor_data_registro LIKE '%{$_POST['searchFornecedor']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $exp = explode(" ", $v['fornecedor_data_registro']);
                        $dia = explode("-", $exp[0]);
                        $v['fornecedor_data_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        $json['fornecedores'][] = $v;
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
            $json['fornecedores'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(fornecedor_id) AS qtd FROM fornecedor");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM fornecedor LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $exp = explode(" ", $v['fornecedor_data_registro']);
                        $dia = explode("-", $exp[0]);
                        $v['fornecedor_data_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        $json['fornecedores'][] = $v;
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