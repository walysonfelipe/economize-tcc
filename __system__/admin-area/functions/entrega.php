<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['searchEnt'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['entregas'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(e.entrega_id) AS qtd FROM entrega AS e JOIN compra AS c ON e.compra_id=c.compra_id JOIN status_compra AS s ON c.status_id=s.status_id JOIN forma_pag AS fp ON c.forma_id=fp.forma_id JOIN armazem AS a ON c.armazem_id=a.armazem_id JOIN usuario AS u ON c.usu_id=u.usu_id LEFT JOIN dados_entrega AS d ON e.entrega_id=d.entrega_id LEFT JOIN funcionario AS f ON d.funcionario_id=f.funcionario_id WHERE e.entrega_horario LIKE '%{$_POST['searchEnt']}%' OR e.entrega_cep LIKE '%{$_POST['searchEnt']}%' OR e.entrega_end LIKE '%{$_POST['searchEnt']}%' OR e.entrega_num LIKE '%{$_POST['searchEnt']}%' OR e.entrega_complemento LIKE '%{$_POST['searchEnt']}%' OR e.entrega_bairro LIKE '%{$_POST['searchEnt']}%' OR e.entrega_cidade LIKE '%{$_POST['searchEnt']}%' OR e.entrega_uf LIKE '%{$_POST['searchEnt']}%' OR c.compra_hash LIKE '%{$_POST['searchEnt']}%' OR c.compra_total LIKE '%{$_POST['searchEnt']}%' OR fp.forma_nome LIKE '%{$_POST['searchEnt']}%' OR s.status_nome LIKE '%{$_POST['searchEnt']}%' OR f.funcionario_nome LIKE '%{$_POST['searchEnt']}%' OR f.funcionario_cpf LIKE '%{$_POST['searchEnt']}%' OR u.usu_first_name LIKE '%{$_POST['searchEnt']}%' OR u.usu_last_name LIKE '%{$_POST['searchEnt']}%' OR u.usu_cpf LIKE '%{$_POST['searchEnt']}%' OR a.armazem_nome LIKE '%{$_POST['searchEnt']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT e.entrega_id, e.entrega_horario, c.compra_hash, c.status_id, e.entrega_cidade, e.entrega_uf, e.entrega_cidade, a.armazem_nome FROM entrega AS e JOIN compra AS c ON e.compra_id=c.compra_id JOIN status_compra AS s ON c.status_id=s.status_id JOIN forma_pag AS fp ON c.forma_id=fp.forma_id JOIN armazem AS a ON c.armazem_id=a.armazem_id JOIN usuario AS u ON c.usu_id=u.usu_id LEFT JOIN dados_entrega AS d ON e.entrega_id=d.entrega_id LEFT JOIN funcionario AS f ON d.funcionario_id=f.funcionario_id WHERE e.entrega_horario LIKE '%{$_POST['searchEnt']}%' OR e.entrega_cep LIKE '%{$_POST['searchEnt']}%' OR e.entrega_end LIKE '%{$_POST['searchEnt']}%' OR e.entrega_num LIKE '%{$_POST['searchEnt']}%' OR e.entrega_complemento LIKE '%{$_POST['searchEnt']}%' OR e.entrega_bairro LIKE '%{$_POST['searchEnt']}%' OR e.entrega_cidade LIKE '%{$_POST['searchEnt']}%' OR e.entrega_uf LIKE '%{$_POST['searchEnt']}%' OR c.compra_hash LIKE '%{$_POST['searchEnt']}%' OR c.compra_total LIKE '%{$_POST['searchEnt']}%' OR fp.forma_nome LIKE '%{$_POST['searchEnt']}%' OR s.status_nome LIKE '%{$_POST['searchEnt']}%' OR f.funcionario_nome LIKE '%{$_POST['searchEnt']}%' OR f.funcionario_cpf LIKE '%{$_POST['searchEnt']}%' OR u.usu_first_name LIKE '%{$_POST['searchEnt']}%' OR u.usu_last_name LIKE '%{$_POST['searchEnt']}%' OR u.usu_cpf LIKE '%{$_POST['searchEnt']}%' OR a.armazem_nome LIKE '%{$_POST['searchEnt']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $exp = explode(" ", $v['entrega_horario']);
                        $dia = explode("-", $exp[0]);
                        $v['entrega_horario'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        if($v['status_id'] < 5) {
                            $v['status_id'] = '<span class="noVisuAtend">PENDENTE</span>';
                        } else {
                            $v['status_id'] = '<span class="jaVisuAtend">ATIVADO</span>';
                        }

                        $json['entregas'][] = $v;
                        $json['registrosMostra']++;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(entrega_id) AS qtd FROM entrega");
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

            $json['entregas'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT e.entrega_id, e.entrega_horario, c.compra_hash, c.status_id, e.entrega_cidade, e.entrega_uf, e.entrega_cidade, a.armazem_nome FROM entrega AS e JOIN compra AS c ON e.compra_id=c.compra_id JOIN armazem AS a ON c.armazem_id=a.armazem_id ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT e.entrega_id, e.entrega_horario, c.compra_hash, c.status_id, e.entrega_cidade, e.entrega_uf, e.entrega_cidade, a.armazem_nome FROM entrega AS e JOIN compra AS c ON e.compra_id=c.compra_id JOIN armazem AS a ON c.armazem_id=a.armazem_id LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $prods = $sel->fetchAll();
                foreach($prods as $v) {
                    $exp = explode(" ", $v['entrega_horario']);
                    $dia = explode("-", $exp[0]);
                    $v['entrega_horario'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                    if($v['status_id'] < 5) {
                        $v['status_id'] = '<span class="noVisuAtend">PENDENTE</span>';
                    } else {
                        $v['status_id'] = '<span class="jaVisuAtend">ATIVADO</span>';
                    }

                    $json['entregas'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['getEnt_id'])) {
            $json['entrega'] = NULL;
            $sel = $conn->prepare("SELECT * FROM lista_compra AS l JOIN compra AS c ON c.compra_id=l.compra_id JOIN usuario AS u ON u.usu_id=c.usu_id JOIN armazem AS a ON c.armazem_id=a.armazem_id JOIN cidade AS ci ON a.cidade_id=ci.cid_id JOIN estado AS es ON ci.est_id=es.est_id JOIN status_compra AS s ON c.status_id=s.status_id JOIN forma_pag AS f ON c.forma_id=f.forma_id JOIN entrega AS e ON e.compra_id=c.compra_id JOIN produto AS p ON l.produto_id=p.produto_id WHERE e.entrega_id=:id");
            $sel->bindValue("id", $_POST['getEnt_id']);
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $c = 0;
                    while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $exp = explode(" ", $row['compra_registro']);
                        $day = explode("-", $exp[0]);
                        $row['compra_registro'] = $day[2] . "/" . $day[1] . "/" . $day[0] . 
                        " às " . $exp[1];

                        $datetime = str_replace(" ", "|", $row['entrega_horario']);
                        $json['end']['horario_sql'] = $datetime;
                        
                        $exp = explode(" ", $row['entrega_horario']);
                        $day = explode("-", $exp[0]);
                        $row['entrega_horario'] = $day[2] . "/" . $day[1] . "/" . $day[0] . 
                        " às " . $exp[1];

                        $row['compra_total'] = number_format($row['compra_total'], 2, ',', '.');

                        $json['compra']['id'] = $row['compra_id'];
                        $json['compra']['armazem'] = $row['armazem_nome'] . " &nbsp;| &nbsp;" . $row['cid_nome'] . " - " . $row['est_uf'];
                        $json['compra']['registro'] = $row['compra_registro'];
                        $json['compra']['hash'] = $row['compra_hash'];
                        $json['compra']['total'] = $row['compra_total'];
                        $json['compra']['status'] = $row['status_nome'];
                        $json['compra']['forma_pag'] = $row['forma_nome'];

                        if($row['compra_link'] != '') {
                            $json['compra']['link'] = $row['compra_link'];
                        }
                    
                        $json['end']['horario'] = $row['entrega_horario'];
                        $json['end']['cep'] = $row['entrega_cep'];
                        $json['end']['log'] = $row['entrega_end'];
                        $json['end']['num'] = $row['entrega_num'];
                        $json['end']['complemento'] = $row['entrega_complemento'];
                        $json['end']['bairro'] = $row['entrega_bairro'];
                        $json['end']['cidade'] = $row['entrega_cidade'];
                        $json['end']['uf'] = $row['entrega_uf'];

                        $json['usuario']['nome'] = $row['usu_first_name'] . " " . $row['usu_last_name'];
                        $json['usuario']['cpf'] = $row['usu_cpf'];

                        $json['produto_id'][$c] = $row['produto_id'];
                        $json['produto_nome'][$c] = $row['produto_nome'];
                        $json['produto_qtd'][$c] = $row['produto_qtd'];
                        
                        $c++;
                    }

                    $sel2 = $conn->prepare("SELECT f.funcionario_nome, f.funcionario_cpf FROM dados_entrega AS d JOIN funcionario AS f ON d.funcionario_id=f.funcionario_id WHERE d.entrega_id={$_POST['getEnt_id']}");
                    $sel2->execute();
                    if($sel2->rowCount() > 0) {
                        $t = 0;
                        while($row2 = $sel2->fetch( PDO::FETCH_ASSOC )) {
                            $json['funcionario_nome'][$t] = $row2['funcionario_nome'];
                            $json['funcionario_cpf'][$t] = $row2['funcionario_cpf'];
                            $t++;
                        }
                    }
                } else {
                    $json['status'] = 0;
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['updEnt_id'])) {
            $sel = $conn->prepare("SELECT d.entrega_id, f.funcionario_id, f.funcionario_nome, f.funcionario_cpf FROM dados_entrega AS d JOIN funcionario AS f ON d.funcionario_id=f.funcionario_id WHERE d.entrega_id={$_POST['updEnt_id']}");
            $sel->execute();
            if($sel->rowCount() > 0) {
                while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                    $json['funcionario_entrega'][] = $row;
                }
            } else {
                $json['funcionario_entrega'] = NULL;
            }

            $sel = $conn->prepare("SELECT c.status_id FROM compra AS c JOIN entrega AS e ON c.compra_id=e.compra_id WHERE e.entrega_id={$_POST['updEnt_id']}");
            $sel->execute();
            $json['status_compra'] = $sel->fetch( PDO::FETCH_ASSOC );

            $sel = $conn->prepare("SELECT funcionario_id, funcionario_nome, funcionario_cpf FROM funcionario WHERE funcionario_setor=1");
            $sel->execute();
            while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                $json['funcionarios'][] = $row;
            }
        } elseif(isset($_POST['entrega_idDel'])) {
            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(!in_array("d", $permicoes)) {
                $json['status'] = 0;
                $json['error'] = 'Você não tem permição para deletar';
            } else {
                $sel = $conn->prepare("SELECT entrega_horario FROM entrega WHERE entrega_id={$_POST['entrega_idDel']}");
                $sel->execute();
                $row = $sel->fetch( PDO::FETCH_ASSOC );

                if(strtotime($row['entrega_horario']) < time()) {
                    $json['status'] = 0;
                    $json['error'] = 'Entrega já deve ter sido feita, por isso, não pode haver alterações!';
                } elseif(strtotime($row['entrega_horario']) == time()) {
                    $json['status'] = 0;
                    $json['error'] = 'Não há mais tempo para alterações, por isso, não pode haver alterações!';
                } else {
                    $del = $conn->prepare("DELETE FROM dados_entrega WHERE entrega_id={$_POST['entrega_idDel']} AND funcionario_id={$_POST['funcionario_idDel']}");
                    if(!$del->execute()) {
                        $json['status'] = 0;
                        $json['error'] = 'Erro ao deletar: Código '  . $upd->errorCode();
                    } else {
                        $sel = $conn->prepare("SELECT c.compra_id, e.entrega_id FROM entrega AS e JOIN compra AS c ON e.compra_id=c.compra_id WHERE e.entrega_id={$_POST['entrega_idDel']}");
                        $sel->execute();
                        $row = $sel->fetch( PDO::FETCH_ASSOC );
                        
                        $sel2 = $conn->prepare("SELECT * FROM dados_entrega WHERE entrega_id={$_POST['entrega_idDel']}");
                        $sel2->execute();
                        if($sel2->rowCount() == 0) {
                            $upd = $conn->prepare("UPDATE compra SET status_id=1 WHERE compra_id={$row['compra_id']}");
                            if(!$upd->execute()) {
                                $json['status'] = 0;
                                $json['error_del'] = "Ocorreu um erro ao editar o status da compra. Tente novamente!";
                            }
                        }
                    }
                }
            }
        } elseif(isset($_POST['funcionario_entrega'])) {
            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(!in_array("a", $permicoes)) {
                $json['status'] = 0;
                $json['error'] = 'Você não tem permição para adicionar';
            } else {
                $sel = $conn->prepare("SELECT dados_id FROM dados_entrega WHERE entrega_id={$_POST['entrega_idUpd']} AND funcionario_id={$_POST['funcionario_entrega']}");
                $sel->execute();
                if($sel->rowCount() == 0) {
                    $sel = $conn->prepare("SELECT entrega_horario FROM entrega WHERE entrega_id={$_POST['entrega_idUpd']}");
                    $sel->execute();
                    $row = $sel->fetch( PDO::FETCH_ASSOC );

                    if(strtotime($row['entrega_horario']) < time()) {
                        $json['status'] = 0;
                        $json['error_del'] = 'Entrega já deve ter sido feita, por isso, não pode haver alterações!';
                    } elseif(strtotime($row['entrega_horario']) == time()) {
                        $json['status'] = 0;
                        $json['error_del'] = 'Não há mais tempo para alterações, por isso, não pode haver alterações!';
                    } else {
                        $ins = $conn->prepare("INSERT INTO dados_entrega(entrega_id, funcionario_id) VALUES({$_POST['entrega_idUpd']}, {$_POST['funcionario_entrega']})");
                        if(!$ins->execute()) {
                            $json['status'] = 0;
                            $json['error_del'] = "Erro ao inserir: Código " . $upd->errorCode();
                        } else {
                            $sel = $conn->prepare("SELECT c.compra_id FROM compra AS c JOIN entrega AS e ON e.compra_id=c.compra_id WHERE e.entrega_id={$_POST['entrega_idUpd']}");
                            $sel->execute();
                            $row = $sel->fetch( PDO::FETCH_ASSOC );

                            $upd = $conn->prepare("UPDATE compra SET status_id=2 WHERE compra_id={$row['compra_id']}");
                            if(!$upd->execute()) {
                                $json['status'] = 0;
                                $json['error_del'] = "Ocorreu um erro ao editar o status da compra. Tente novamente!";
                            }
                        }
                    }
                }
            }
        } else {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização


            $json['empty'] = TRUE;
            $json['entregas'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(entrega_id) AS qtd FROM entrega");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT e.entrega_id, e.entrega_horario, c.compra_hash, c.status_id, e.entrega_cidade, e.entrega_uf, e.entrega_cidade, a.armazem_nome FROM entrega AS e JOIN compra AS c ON e.compra_id=c.compra_id JOIN armazem AS a ON c.armazem_id=a.armazem_id ORDER BY c.status_id AND c.compra_id LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $exp = explode(" ", $v['entrega_horario']);
                        $dia = explode("-", $exp[0]);
                        $v['entrega_horario'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        if($v['status_id'] < 5) {
                            $v['status_id'] = '<span class="noVisuAtend">PENDENTE</span>';
                        } else {
                            $v['status_id'] = '<span class="jaVisuAtend">ATIVADO</span>';
                        }

                        $json['entregas'][] = $v;
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