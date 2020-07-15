<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;

        if(isset($_POST['categ_nome'])) {
            for($k=0; $k<count($_POST['categ_nome']); $k++) {
                $c = $k + 1;
                $categ_nome[$k] = $_POST['categ_nome'][$k];
                $subcateg_id[$k] = $_POST['subcateg_id'][$k];

                if(empty($categ_nome[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome da categoria na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if($subcateg_id[$k] == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a subcategoria na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        $sel = $conn->prepare("SELECT c.categ_nome, s.subcateg_nome FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id WHERE c.categ_nome='{$categ_nome[$k]}' AND c.subcateg_id={$subcateg_id[$k]}");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>A categoria ' . $res[0]['subcateg_nome'] . ' / ' . $res[0]['categ_nome'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrada</b></p>';
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
                for($k=0; $k<count($categ_nome); $k++) {
                    $ins = $conn->prepare("INSERT INTO categ(categ_nome, subcateg_id) VALUES ('{$categ_nome[$k]}', '{$subcateg_id[$k]}')");
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

            $sel = $conn->prepare("SELECT COUNT(categ_id) AS qtd FROM categ");
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

            $json['categorias'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT c.categ_id, c.categ_nome, s.subcateg_nome, d.depart_nome FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT c.categ_id, c.categ_nome, s.subcateg_nome, d.depart_nome FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $categ = $sel->fetchAll();
                foreach($categ as $v) {
                    $v['sub_dep'] = $v['subcateg_nome'] . " / " . $v['depart_nome'];
                    
                    $sel2 = $conn->prepare("SELECT COUNT(p.produto_id) AS qtd_prod FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id WHERE p.produto_categ={$v['categ_id']}");
                    $sel2->execute();
                    $row2 = $sel2->fetch( PDO::FETCH_ASSOC );
                    $v['qtd_prod'] = $row2['qtd_prod'];

                    $json['categorias'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchCateg'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['categorias'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(c.categ_id) AS qtd FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id WHERE c.categ_nome LIKE '%{$_POST['searchCateg']}%' OR s.subcateg_nome LIKE '%{$_POST['searchCateg']}%' OR d.depart_nome LIKE '%{$_POST['searchCateg']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT c.categ_id, c.categ_nome, s.subcateg_nome, d.depart_nome FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id WHERE c.categ_nome LIKE '%{$_POST['searchCateg']}%' OR s.subcateg_nome LIKE '%{$_POST['searchCateg']}%' OR d.depart_nome LIKE '%{$_POST['searchCateg']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['sub_dep'] = $v['subcateg_nome'] . " / " . $v['depart_nome'];
                        
                        $sel2 = $conn->prepare("SELECT COUNT(p.produto_id) AS qtd_prod FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id WHERE p.produto_categ={$v['categ_id']}");
                        $sel2->execute();
                        $row2 = $sel2->fetch( PDO::FETCH_ASSOC );
                        $v['qtd_prod'] = $row2['qtd_prod'];

                        $json['categorias'][] = $v;
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
            $json['categorias'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(categ_id) AS qtd FROM categ");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT c.categ_id, c.categ_nome, s.subcateg_nome, d.depart_nome FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['sub_dep'] = $v['subcateg_nome'] . " / " . $v['depart_nome'];
                        
                        $sel2 = $conn->prepare("SELECT COUNT(p.produto_id) AS qtd_prod FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id WHERE p.produto_categ={$v['categ_id']}");
                        $sel2->execute();
                        $row2 = $sel2->fetch( PDO::FETCH_ASSOC );
                        $v['qtd_prod'] = $row2['qtd_prod'];

                        $json['categorias'][] = $v;
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