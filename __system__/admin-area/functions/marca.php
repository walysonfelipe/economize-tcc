<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;
        
        if(isset($_POST['marca_nome'])) {
            for($k=0; $k<count($_POST['marca_nome']); $k++) {
                $c = $k + 1;
                $marca_nome[$k] = $_POST['marca_nome'][$k];
                $marca_promocao[$k] = $_POST['marca_promocao'][$k];

                if(empty($marca_nome[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome da marca na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(!empty($marca_promocao[$k])) {
                        if(($marca_promocao[$k] == 0) || ($marca_promocao[$k] > 100)) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O desconto da marca inserido na ' . $c . 'ª parte de cadastro deve ser maior que 0 e menor ou igual a 100 (este campo não é obrigatório)</b></p>';
                        } else {
                            $sel = $conn->prepare("SELECT marca_nome FROM marca_prod WHERE marca_nome='{$marca_nome[$k]}'");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                $res = $sel->fetchAll();
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A marca ' . $res[0]['marca_nome'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrada</b></p>';
                            }
                        }
                    } else {
                        $sel = $conn->prepare("SELECT marca_nome FROM marca_prod WHERE marca_nome='{$marca_nome[$k]}'");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A marca ' . $res[0]['marca_nome'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrada</b></p>';
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
                for($k=0; $k<count($marca_nome); $k++) {
                    if($marca_promocao[$k] != "") {
                        $ins = $conn->prepare("INSERT INTO marca_prod(marca_nome, marca_promocao) VALUES ('{$marca_nome[$k]}', {$marca_promocao[$k]})");
                    } else {
                        $ins = $conn->prepare("INSERT INTO marca_prod(marca_nome) VALUES ('{$marca_nome[$k]}')");
                    }
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

            $sel = $conn->prepare("SELECT COUNT(marca_id) AS qtd FROM marca_prod");
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

            $json['marcas'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM marca_prod ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM marca_prod LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $marca = $sel->fetchAll();
                foreach($marca as $v) {
                    $json['marcas'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchMarca'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['marcas'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(marca_id) AS qtd FROM marca_prod WHERE marca_nome LIKE '%{$_POST['searchMarca']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM marca_prod WHERE marca_nome LIKE '%{$_POST['searchMarca']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {    
                        $json['marcas'][] = $v;
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
            $json['marcas'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(marca_id) AS qtd FROM marca_prod");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM marca_prod LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $json['marcas'][] = $v;
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