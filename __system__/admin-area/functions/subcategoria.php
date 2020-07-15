<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;

        if(isset($_POST['subcateg_nome'])) {
            for($k=0; $k<count($_POST['subcateg_nome']); $k++) {
                $c = $k + 1;
                $subcateg_nome[$k] = $_POST['subcateg_nome'][$k];
                $depart_id[$k] = $_POST['depart_id'][$k];

                if(empty($subcateg_nome[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome da subcategoria na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if($depart_id[$k] == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o departamento da subcategoria na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        $sel = $conn->prepare("SELECT s.subcateg_nome, d.depart_nome FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id WHERE s.subcateg_nome='{$subcateg_nome[$k]}' AND d.depart_id={$depart_id[$k]}");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A subcategoria ' . $res[0]['depart_nome'] . ' / ' . $res[0]['subcateg_nome'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrada</b></p>';
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
                for($k=0; $k<count($subcateg_nome); $k++) {
                    $ins = $conn->prepare("INSERT INTO subcateg(subcateg_nome,depart_id) VALUES ('{$subcateg_nome[$k]}','{$depart_id[$k]}')");
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

            $sel = $conn->prepare("SELECT COUNT(subcateg_id) AS qtd FROM subcateg");
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

            $json['subcategorias'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $sub = $sel->fetchAll();
                foreach($sub as $v) {
                    $json['subcategorias'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchSubcateg'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['subcategorias'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(s.subcateg_id) AS qtd FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id WHERE s.subcateg_nome LIKE '%{$_POST['searchSubcateg']}%' OR d.depart_nome LIKE '%{$_POST['searchSubcateg']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id WHERE s.subcateg_nome LIKE '%{$_POST['searchSubcateg']}%' OR d.depart_nome LIKE '%{$_POST['searchSubcateg']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {    
                        $json['subcategorias'][] = $v;
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
            $json['subcategorias'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(subcateg_id) AS qtd FROM subcateg");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $json['subcategorias'][] = $v;
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