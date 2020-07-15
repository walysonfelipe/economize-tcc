<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['arm_id'])) {
            $sel = $conn->prepare("SELECT p.produto_id, p.produto_nome, p.produto_tamanho FROM dados_armazem AS d JOIN produto AS p ON p.produto_id=d.produto_id WHERE d.armazem_id={$_POST['arm_id']} ORDER BY p.produto_nome");
            $sel->execute();
            if($sel->rowCount() > 0) {
                while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                    $json['produtos'][] = $v;
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['promo_nome'])) {
            $json['error'] = NULL;

            $nome = $_POST['promo_nome'];
            $subtit = $_POST['promo_subtit'];
            $desconto = $_POST['promo_desconto'];
            $status = $_POST['promo_status'];
            $expira = $_POST['promo_expira'];

            if(empty($nome)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome da promoção</b></p>';
            } else {
                if(empty($desconto)) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o desconto da promoção</b></p>';
                } else {
                    if(($desconto > 100) || ($desconto < 1)) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O desconto da promoção não pode ser maior que 100 ou menor que 1</b></p>';
                    } else {
                        if($status == "*000*") {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira um status para inicialização da promoção</b></p>';
                        } else {
                            if(!empty($expira)) {
                                if(strlen($expira) < 19) {
                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a data de expiração corretamente</b></p>';
                                } else {
                                    $exp = explode(" ", $expira);
                                    $data = explode("/", $exp[0]);
                                    $hora = explode(":", $exp[1]);
                                    $expira_sql = $data[2] . "-" . $data[1] . "-" . $data[0] . " " 
                                    . $hora[0] . ":" . $hora[1] . ":" . $hora[2];
                                    $json['data'] = $expira_sql;

                                    //Verificando se a data é válida
                                    $res = checkdate($data[1], $data[0], $data[2]);
                                    if(!$res) {
                                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira uma data de expiração válida</b></p>';
                                    } else {
                                        if(($hora[0] > 23) || ($hora[1] > 59) || ($hora[2] > 59)) {
                                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira uma data de expiração válida</b></p>';
                                        } else {
                                            if(strtotime($expira_sql) < time()) {
                                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A data de expiração é inválida pois está no passado</b></p>';
                                            } elseif(strtotime($expira_sql) == time()) {
                                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A data de expiração é inválida pois está no presente</b></p>';
                                            } else {
                                                $sel = $conn->prepare("SELECT promo_id FROM promocao_temp WHERE promo_nome='{$nome}'");
                                                $sel->execute();
                                                if($sel->rowCount() > 0) {
                                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O nome da promoção não pode ser igual a outras anteriores</b></p>';
                                                } else {
                                                    $expira = substr($expira,6,4) . "-" . substr($expira,3,2) . "-" . substr($expira,0,2) . " " . substr($expira,-8);
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

            if($json['error'] != NULL) {
                $json['status'] = 0;
            } else {
                for($k = 0; $k < count($_POST['armazem_id']); $k++) {
                    $c = $k + 1;
                    $armazem_id[$k] = $_POST['armazem_id'][$k];
                    $produto_id[$k] = $_POST['produto_id'][$k];
                    $all_prods[$k] = (($produto_id[$k] == "*111*") ? 1 : NULL);

                    if($armazem_id[$k] == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o armazém na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        if(!$all_prods[$k]) {
                            if($produto_id[$k] == "*000*") {
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o produto na ' . $c . 'ª parte de cadastro</b></p>';
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
                    $_SESSION['promo_inf'][0] = $nome;
                    $_SESSION['promo_inf'][1] = $subtit;
                    $_SESSION['promo_inf'][2] = $desconto;
                    $_SESSION['promo_inf'][3] = $status;
                    $_SESSION['promo_inf'][4] = $expira;
                    foreach($produto_id as $k => $v) {
                        if($all_prods[$k]) {
                            $_SESSION['allProds'] = 1;
                            $sel = $conn->prepare("SELECT armazem_id FROM armazem WHERE armazem_id={$armazem_id[$k]}");
                            $sel->execute();
                            $res = $sel->fetch( PDO::FETCH_ASSOC );

                            $sel2 = $conn->prepare("SELECT produto_id FROM dados_armazem WHERE armazem_id={$armazem_id[$k]}");
                            $sel2->execute();
                            $c = 0;
                            while($row = $sel2->fetch( PDO::FETCH_ASSOC )) {
                                $_SESSION["arm_" . $res['armazem_id']][$c] = $row['produto_id'];
                                $c++;
                            }

                            foreach($_SESSION["arm_" . $res['armazem_id']] as $key => $value) {
                                $sel3 = $conn->prepare("SELECT produto_id FROM dados_armazem WHERE produto_id=$value AND produto_desconto_porcent <> NULL AND armazem_id={$armazem_id[$k]}");
                                $sel3->execute();
                                if($sel3->rowCount() > 0) {
                                    $json['status'] = 2;
                                }
                                
                                $sel4 = $conn->prepare("SELECT produto_id FROM dados_promocao WHERE produto_id=$value AND armazem_id={$armazem_id[$k]}");
                                $sel4->execute();
                                if($sel4->rowCount() > 0) {
                                    $json['status'] = 2;
                                }
                            }
                        } else {
                            $_SESSION['promo_produto'][$k] = $v;
                            $_SESSION['promo_armazem'][$k] = $armazem_id[$k];

                            $sel = $conn->prepare("SELECT produto_id FROM dados_armazem WHERE produto_id=$v AND produto_desconto_porcent <> NULL AND armazem_id={$armazem_id[$k]}");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                $json['status'] = 2;
                            }

                            $sel2 = $conn->prepare("SELECT produto_id FROM dados_promocao WHERE produto_id=$v AND armazem_id={$armazem_id[$k]}");
                            $sel2->execute();
                            if($sel2->rowCount() > 0) {
                                $json['status'] = 2;
                            }
                        }
                    }

                    if($json['status'] != 2) {
                        if($subtit) {
                            $ins = $conn->prepare("INSERT INTO promocao_temp(promo_nome, promo_subtit, promo_desconto," . (isset($expira) ? ' promo_expira,' : '') . " promo_status) VALUES('$nome', '$subtit', $desconto," . (isset($expira) ? " '$expira'," : '') . " $status)");
                        } else {
                            $ins = $conn->prepare("INSERT INTO promocao_temp(promo_nome, promo_desconto," . (isset($expira) ? ' promo_expira,' : '') . " promo_status) VALUES('$nome', $desconto," . (isset($expira) ? " '$expira'," : '') . " $status)");
                        }
                        $ins->execute();

                        $sel = $conn->prepare("SELECT promo_id FROM promocao_temp WHERE promo_nome='{$_SESSION["promo_inf"][0]}'");
                        $sel->execute();
                        $res = $sel->fetch( PDO::FETCH_ASSOC );
            
                        if(isset($_SESSION['allProds'])) {
                            $sel = $conn->prepare("SELECT armazem_id FROM armazem");
                            $sel->execute();
                            while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                                if(isset($_SESSION["arm_" . $row['armazem_id']])) {
                                    foreach($_SESSION["arm_" . $row['armazem_id']] as $k => $v) {
                                        $upd = $conn->prepare("UPDATE dados_armazem SET produto_desconto_porcent=NULL WHERE produto_id={$v} AND armazem_id={$row['armazem_id']}");
                                        $upd->execute();
            
                                        $del = $conn->prepare("DELETE FROM dados_promocao WHERE produto_id={$v} AND armazem_id={$row['armazem_id']}");
                                        $del->execute();
            
                                        $ins = $conn->prepare("INSERT INTO dados_promocao(produto_id, armazem_id, promo_id) VALUES({$v}, {$row['armazem_id']}, {$res['promo_id']})");
                                        $ins->execute();
                                    }

                                    unset($_SESSION["arm_" . $row['armazem_id']]);
                                }
                            }

                            unset($_SESSION['allProds']);
                        }
            
                        if(isset($_SESSION['promo_produto'])) {
                            foreach($_SESSION['promo_produto'] as $k => $v) {
                                $upd = $conn->prepare("UPDATE dados_armazem SET produto_desconto_porcent=NULL WHERE produto_id={$v} AND armazem_id={$_SESSION['promo_armazem'][$k]}");
                                $upd->execute();

                                $del = $conn->prepare("DELETE FROM dados_promocao WHERE produto_id={$v} AND armazem_id={$_SESSION['promo_armazem'][$k]}");
                                $del->execute();

                                $ins = $conn->prepare("INSERT INTO dados_promocao(produto_id, armazem_id, promo_id) VALUES({$v}, {$_SESSION['promo_armazem'][$k]}, {$res['promo_id']})");
                                $ins->execute();
                            }

                            unset($_SESSION['promo_produto']);
                            unset($_SESSION['promo_armazem']);
                        }

                        unset($_SESSION['promo_inf']);
                    }
                }
            }
        } elseif(isset($_POST['confirmaPromo'])) {
            if($_SESSION['promo_inf'][1]) {
                $ins = $conn->prepare("INSERT INTO promocao_temp(promo_nome, promo_subtit, promo_desconto," . (isset($_SESSION['promo_inf'][4]) ? ' promo_expira,' : '') . " promo_status) VALUES('{$_SESSION['promo_inf'][0]}', '{$_SESSION['promo_inf'][1]}', {$_SESSION['promo_inf'][2]}," . (isset($_SESSION['promo_inf'][4]) ? " '{$_SESSION['promo_inf'][4]}'," : '') . " {$_SESSION['promo_inf'][3]})");
            } else {
                $ins = $conn->prepare("INSERT INTO promocao_temp(promo_nome, promo_desconto," . (isset($_SESSION['promo_inf'][4]) ? ' promo_expira,' : '') . " promo_status) VALUES('{$_SESSION['promo_inf'][0]}', {$_SESSION['promo_inf'][2]}," . (isset($_SESSION['promo_inf'][4]) ? " '{$_SESSION['promo_inf'][4]}'," : '') . " {$_SESSION['promo_inf'][3]})");
            }
            $ins->execute();

            $sel = $conn->prepare("SELECT promo_id FROM promocao_temp WHERE promo_nome='{$_SESSION["promo_inf"][0]}'");
            $sel->execute();
            $res = $sel->fetch( PDO::FETCH_ASSOC );

            if(isset($_SESSION['allProds'])) {
                $sel = $conn->prepare("SELECT armazem_id FROM armazem");
                $sel->execute();
                while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                    if(isset($_SESSION["arm_" . $row['armazem_id']])) {
                        foreach($_SESSION["arm_" . $row['armazem_id']] as $k => $v) {
                            $upd = $conn->prepare("UPDATE dados_armazem SET produto_desconto_porcent=NULL WHERE produto_id={$v} AND armazem_id={$row['armazem_id']}");
                            $upd->execute();

                            $del = $conn->prepare("DELETE FROM dados_promocao WHERE produto_id={$v} AND armazem_id={$row['armazem_id']}");
                            $del->execute();

                            $ins = $conn->prepare("INSERT INTO dados_promocao(produto_id, armazem_id, promo_id) VALUES({$v}, {$row['armazem_id']}, {$res['promo_id']})");
                            $ins->execute();
                        }

                        unset($_SESSION["arm_" . $row['armazem_id']]);
                    }
                }
                unset($_SESSION['allProds']);
            }

            if(isset($_SESSION['promo_produto'])) {
                foreach($_SESSION['promo_produto'] as $k => $v) {
                    $upd = $conn->prepare("UPDATE dados_armazem SET produto_desconto_porcent=NULL WHERE produto_id={$v} AND armazem_id={$_SESSION['promo_armazem'][$k]}");
                    $upd->execute();

                    $del = $conn->prepare("DELETE FROM dados_promocao WHERE produto_id={$v} AND armazem_id={$_SESSION['promo_armazem'][$k]}");
                    $del->execute();

                    $ins = $conn->prepare("INSERT INTO dados_promocao(produto_id, armazem_id, promo_id) VALUES({$v}, {$_SESSION['promo_armazem'][$k]}, {$res['promo_id']})");
                    $ins->execute();
                }

                unset($_SESSION['promo_produto']);
                unset($_SESSION['promo_armazem']);
            }

            unset($_SESSION['promo_inf']);
        }

        echo json_encode($json);
    }
?>