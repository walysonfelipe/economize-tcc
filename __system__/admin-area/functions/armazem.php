<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;

        if(isset($_POST['produto_qtd'])) {
            for($k=0; $k<count($_POST['produto_qtd']); $k++) {
                $c = $k + 1;
                $armazem_id[$k] = $_POST['armazem'][$k];
                $produto_id[$k] = $_POST['produto'][$k];
                $produto_qtd[$k] = $_POST['produto_qtd'][$k];

                $produto_preco[$k] = $_POST['produto_preco'][$k];
                $produto_preco[$k] = str_replace(".","",$produto_preco[$k]);
                $produto_preco[$k] = str_replace(",",".",$produto_preco[$k]);
                $produto_desconto_porcent[$k] = $_POST['produto_desconto_porcent'][$k];
                
                if($armazem_id[$k] == "*000*") {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Escolha um armazém na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if($produto_id[$k] == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Escolha um produto na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        if(empty($produto_qtd[$k])) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a quantidade de produto na ' . $c . 'ª parte de cadastro</b></p>';
                        } else {
                            if(empty($produto_preco[$k])) {
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o preço do produto na ' . $c . 'ª parte de cadastro</b></p>';
                            } else {
                                if(!empty($produto_desconto_porcent[$k])) {
                                    if(($produto_desconto_porcent[$k] == 0) || ($produto_desconto_porcent[$k] > 100)) {
                                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O desconto do produto inserido na ' . $c . 'ª parte de cadastro deve ser maior que 0 e menor ou igual a 100 (este campo não é obrigatório)</b></p>';
                                    } else {
                                        $sel = $conn->prepare("SELECT p.produto_nome, p.produto_tamanho, a.armazem_nome FROM dados_armazem AS d JOIN produto AS p ON d.produto_id=p.produto_id JOIN armazem AS a ON d.armazem_id=a.armazem_id WHERE d.produto_id={$produto_id[$k]} AND d.armazem_id={$armazem_id[$k]}");
                                        $sel->execute();
                                        if($sel->rowCount() > 0) {
                                            $res = $sel->fetchAll();
                                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O produto ' . $res[0]['produto_nome'] . ' - ' . $res[0]['produto_tamanho'] . ' que escolheu na ' . $c . 'ª parte já foi previamente cadastrado no ' . $res[0]['armazem_nome'] . '</b></p>';
                                        }
                                    }
                                } else {
                                    $sel = $conn->prepare("SELECT p.produto_nome, p.produto_tamanho, a.armazem_nome FROM dados_armazem AS d JOIN produto AS p ON d.produto_id=p.produto_id JOIN armazem AS a ON d.armazem_id=a.armazem_id WHERE d.produto_id={$produto_id[$k]} AND d.armazem_id={$armazem_id[$k]}");
                                    $sel->execute();
                                    if($sel->rowCount() > 0) {
                                        $res = $sel->fetchAll();
                                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O produto ' . $res[0]['produto_nome'] . ' - ' . $res[0]['produto_tamanho'] . ' que escolheu na ' . $c . 'ª parte já foi previamente cadastrado no ' . $res[0]['armazem_nome'] . '</b></p>';
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
                for($k = 0; $k < count($armazem_id); $k++) {
                    if($produto_desconto_porcent[$k] != "") {
                        $ins = $conn->prepare("INSERT INTO dados_armazem(produto_id,armazem_id,produto_qtd,produto_preco,produto_desconto_porcent) VALUES ({$produto_id[$k]}, {$armazem_id[$k]}, {$produto_qtd[$k]}, {$produto_preco[$k]},{$produto_desconto_porcent[$k]})");
                    } else {
                        $ins = $conn->prepare("INSERT INTO dados_armazem(produto_id,armazem_id,produto_qtd,produto_preco) VALUES ({$produto_id[$k]}, {$armazem_id[$k]}, {$produto_qtd[$k]}, {$produto_preco[$k]})");
                    }
                
                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }

        }
        echo json_encode($json);
    }
?>