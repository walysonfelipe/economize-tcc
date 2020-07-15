<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;
        
        if(isset($_POST['duvida_pergunta'])) {
            for($k=0; $k<count($_POST['duvida_pergunta']); $k++) {
                $c = $k + 1;
                $duvida_pergunta[$k] = $_POST['duvida_pergunta'][$k];
                $duvida_resposta[$k] = $_POST['duvida_resposta'][$k];

                if(empty($duvida_pergunta[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a pergunta da dúvida na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(empty($duvida_resposta[$k])) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a resposta da dúvida na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        $sel = $conn->prepare("SELECT duvida_pergunta FROM duvida_frequente WHERE duvida_pergunta='{$duvida_pergunta[$k]}'");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A dúvida ' . $res[0]['duvida_pergunta'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrada</b></p>';
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

                if($json['error']) {
                    break;
                }
            }

            if($json['error']) {
                $json['status'] = 0;
            } else {
                for($k=0; $k<count($duvida_pergunta); $k++) {
                    $ins = $conn->prepare("INSERT INTO duvida_frequente(duvida_pergunta, duvida_resposta) VALUES ('{$duvida_pergunta[$k]}', '{$duvida_resposta[$k]}')");
                    
                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }
        } elseif(isset($_POST['updDuvida_id'])) {
            $json['duvida'] = NULL;
            $sel = $conn->prepare("SELECT * FROM duvida_frequente WHERE duvida_id={$_POST['updDuvida_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $duvida = $sel->fetchAll();
                    foreach($duvida as $v) {
                        $json['duvida'] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['duvida_perguntaUpd'])) {
            $json['error'] = NULL;

            $duvida_id = $_POST['duvida_idUpd'];
            $duvida_pergunta = trim($_POST['duvida_perguntaUpd']);
            $duvida_resposta = $_POST['duvida_respostaUpd'];

            if(empty($duvida_pergunta)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a pergunta da dúvida</b></p>';
            } else {
                if(empty($duvida_resposta)) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a resposta da dúvida</b></p>';
                } else {
                    $sel = $conn->prepare("SELECT duvida_pergunta FROM duvida_frequente WHERE duvida_pergunta='{$duvida_pergunta}' AND duvida_id <> {$duvida_id}");
                    $sel->execute();
                    if($sel->rowCount() > 0) {
                        $res = $sel->fetchAll();
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A dúvida ' . $res[0]['duvida_pergunta'] . ' que inseriu parte já foi previamente cadastrado</b></p>';
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

            if($json['error']) {
                $json['status'] = 0;
            } else {
                $upd = $conn->prepare("UPDATE duvida_frequente SET duvida_pergunta='$duvida_pergunta', duvida_resposta='$duvida_resposta' WHERE duvida_id=$duvida_id");

                if(!$upd->execute()) {
                    $json['status'] = 0;
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                }
            }
        } elseif(isset($_POST['delDuvida_id'])) {
            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(!in_array("e", $permicoes)) {
                $json['status'] = 0;
                $json['error_del'] = 'Você não tem permição para excluir';
            } else {
                $del = $conn->prepare("DELETE FROM duvida_frequente WHERE duvida_id=:id");
                $del->bindValue(":id", "{$_POST['delDuvida_id']}");
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

            $sel = $conn->prepare("SELECT COUNT(duvida_id) AS qtd FROM duvida_frequente");
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

            $json['duvidas'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM duvida_frequente ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM duvida_frequente LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $duv = $sel->fetchAll();
                foreach($duv as $v) {
                    $v['duvida_pergunta'] = (strlen($v['duvida_pergunta']) > 50) ? substr($v['duvida_pergunta'],0,50) . '...' : $v['duvida_pergunta'];
                    $v['duvida_resposta'] = (strlen($v['duvida_resposta']) > 250) ? substr($v['duvida_resposta'],0,250) . '...' : $v['duvida_resposta'];

                    $json['duvidas'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchDuvida'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['duvidas'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(duvida_id) AS qtd FROM duvida_frequente WHERE duvida_pergunta LIKE '%{$_POST['searchDuvida']}%' OR duvida_resposta LIKE '%{$_POST['searchDuvida']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM duvida_frequente WHERE duvida_pergunta LIKE '%{$_POST['searchDuvida']}%' OR duvida_resposta LIKE '%{$_POST['searchDuvida']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['duvida_pergunta'] = (strlen($v['duvida_pergunta']) > 50) ? substr($v['duvida_pergunta'],0,50) . '...' : $v['duvida_pergunta'];
                        $v['duvida_resposta'] = (strlen($v['duvida_resposta']) > 250) ? substr($v['duvida_resposta'],0,250) . '...' : $v['duvida_resposta'];
    
                        $json['duvidas'][] = $v;
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
            $json['duvidas'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(duvida_id) AS qtd FROM duvida_frequente");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM duvida_frequente LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['duvida_pergunta'] = (strlen($v['duvida_pergunta']) > 50) ? substr($v['duvida_pergunta'],0,50) . '...' : $v['duvida_pergunta'];
                        $v['duvida_resposta'] = (strlen($v['duvida_resposta']) > 250) ? substr($v['duvida_resposta'],0,250) . '...' : $v['duvida_resposta'];

                        $json['duvidas'][] = $v;
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