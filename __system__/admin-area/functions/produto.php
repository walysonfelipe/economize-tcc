<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['nome_produto'])) {
            $json['error'] = NULL;

            for($k=0; $k<count($_POST['nome_produto']); $k++) {
                $c = $k + 1;
                $nome_produto[$k] = trim($_POST['nome_produto'][$k]);
                $marca_produto[$k] = $_POST['marca_produto'][$k];
                $categoria_produto[$k] = $_POST['categoria_produto'][$k];
                $descricao_produto[$k] = $_POST['descricao_produto'][$k];
                $tamanho_produto[$k] = trim($_POST['produto_tamanho'][$k]);

                $imagem_produto = $_FILES['imagem_produto']['name'];
                $imagem_produto_tmp = $_FILES['imagem_produto']['tmp_name'];

                if(empty($nome_produto[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do produto na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if($marca_produto[$k] == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a marca do produto na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        if($categoria_produto[$k] == "*000*") {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a categoria do produto na ' . $c . 'ª parte de cadastro</b></p>';
                        } else {
                            if(empty($tamanho_produto[$k])) {
                                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o volume do produto na ' . $c . 'ª parte de cadastro</b></p>';
                            } else {
                                $sel = $conn->prepare("SELECT produto_nome, produto_tamanho FROM produto WHERE produto_nome='{$nome_produto[$k]}' AND produto_tamanho='{$tamanho_produto[$k]}'");
                                $sel->execute();
                                if($sel->rowCount() > 0) {
                                    $res = $sel->fetchAll();
                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O produto ' . $res[0]['produto_nome'] . ' - ' . $res[0]['produto_tamanho'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrado</b></p>';
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
                }

                if($json['error']) {
                    break;
                }
            }

            if($json['error']) {
                $json['status'] = 0;
            } else {
                for($k=0; $k<count($nome_produto); $k++) {
                    if(empty($imagem_produto[$k])) {
                        $imagem_produto[$k] = "img_default.png";
                    } else {
                        move_uploaded_file($imagem_produto_tmp[$k], "__system__/admin_area/imagens_produtos/{$imagem_produto[$k]}");
                    }
                    $ins = $conn->prepare("INSERT INTO produto(produto_nome,produto_descricao,produto_img,produto_marca,produto_tamanho, produto_categ) VALUES ('{$nome_produto[$k]}', '{$descricao_produto[$k]}','{$imagem_produto[$k]}','{$marca_produto[$k]}','{$tamanho_produto[$k]}','{$categoria_produto[$k]}')");

                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }
        } elseif(isset($_POST['delProd_id'])) {
            $del = $conn->prepare("DELETE FROM produto WHERE produto_id=:id");
            $del->bindValue(":id", "{$_POST['delProd_id']}");
            if(!$del->execute()) {
                $json['status'] = 0;
                $json['error_del'] = "Código erro: " . $del->errorCode();
            }
        } elseif(isset($_POST['getProd_id'])) {
            $json['produto'] = NULL;
            $sel = $conn->prepare("SELECT c.categ_nome, s.subcateg_nome, d.depart_nome, p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, m.marca_nome FROM produto AS p JOIN categ AS c ON c.categ_id=p.produto_categ JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id JOIN marca_prod AS m ON p.produto_marca=m.marca_id WHERE p.produto_id={$_POST['getProd_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $prods = $sel->fetchAll();
                    foreach($prods as $v) {
                        $json['produto'] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['updProd_id'])) {
            $json['produto'] = NULL;
            $sel = $conn->prepare("SELECT * FROM produto WHERE produto_id={$_POST['updProd_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $prods = $sel->fetchAll();
                    foreach($prods as $v) {
                        $json['produto'] = $v;
                    }
                    $sel2 = $conn->prepare("SELECT * FROM marca_prod");
                    $sel2->execute();
                    $res2 = $sel2->fetchAll();
                    foreach($res2 as $v) {
                        $json['marca_prod'][] = $v;
                    }
                    
                    $sel3 = $conn->prepare("SELECT * FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id");
                    $sel3->execute();
                    $res3 = $sel3->fetchAll();
                    foreach($res3 as $v) {
                        $json['categ_prod'][] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['nome_produto_upd'])) {
            $json['error'] = NULL;
            $id_produto = trim($_POST['id_produto_upd']);
            $nome_produto = trim($_POST['nome_produto_upd']);
            $marca_produto = $_POST['marca_produto_upd'];
            $categoria_produto = $_POST['categoria_produto_upd'];
            $descricao_produto = $_POST['descricao_produto_upd'];
            $tamanho_produto = trim($_POST['produto_tamanho_upd']);

            $imagem_produto = $_FILES['imagem_produto_upd']['name'];
            $imagem_produto_tmp = $_FILES['imagem_produto_upd']['tmp_name'];

            if(empty($nome_produto)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do produto</b></p>';
            } else {
                if($marca_produto == "*000*") {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a marca do produto</b></p>';
                } else {
                    if($categoria_produto == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a categoria do produto</b></p>';
                    } else {
                        if(empty($tamanho_produto)) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o volume do produto</b></p>';
                        } else {
                            $sel = $conn->prepare("SELECT * FROM produto WHERE produto_id='{$id_produto}'");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                $res = $sel->fetchAll();
                                if(($nome_produto == $res[0]['produto_nome']) &&
                                    ($marca_produto == $res[0]['produto_marca']) &&
                                    ($categoria_produto == $res[0]['produto_categ']) &&
                                    (empty($imagem_produto)) &&
                                    ($descricao_produto == $res[0]['produto_descricao']) &&
                                    ($tamanho_produto == $res[0]['produto_tamanho'])
                                    ) {
                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Não houve alterações</b></p>';
                                } else {
                                    $sel = $conn->prepare("SELECT produto_nome, produto_tamanho FROM produto WHERE produto_nome='$nome_produto' AND produto_tamanho='$tamanho_produto' AND produto_id <> $id_produto");
                                    $sel->execute();
                                    if($sel->rowCount() > 0) {
                                        $res = $sel->fetchAll();
                                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O produto ' . $res[0]['produto_nome'] . ' - ' . $res[0]['produto_tamanho'] . ' já foi previamente cadastrado</b></p>';
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if($json['error']) {
                $json['status'] = 0;
            } else {
                if(!empty($imagem_produto)) {
                    move_uploaded_file($imagem_produto_tmp, "__system__/admin_area/imagens_produtos/$imagem_produto");
                    $new_img = TRUE;
                }

                if(isset($new_img)) {
                    $upd = $conn->prepare("UPDATE produto SET produto_nome='$nome_produto', produto_marca=$marca_produto, produto_categ=$categoria_produto, produto_img='$imagem_produto', produto_descricao='$descricao_produto', produto_tamanho='$tamanho_produto' WHERE produto_id=$id_produto");
                    if(!$upd->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                    }
                } else {
                    $upd = $conn->prepare("UPDATE produto SET produto_nome='$nome_produto', produto_marca=$marca_produto, produto_categ=$categoria_produto, produto_descricao='$descricao_produto', produto_tamanho='$tamanho_produto' WHERE produto_id=$id_produto");
                    if(!$upd->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                    }
                }
            }
        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(produto_id) AS qtd FROM produto");
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

            $json['produtos'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_nome, p.produto_tamanho, m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_nome, p.produto_tamanho, m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $prods = $sel->fetchAll();
                foreach($prods as $v) {
                    $json['produtos'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchProd'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['produtos'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(p.produto_id) AS qtd FROM produto AS p JOIN categ AS c ON c.categ_id=p.produto_categ JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id JOIN marca_prod AS m ON p.produto_marca=m.marca_id WHERE p.produto_nome LIKE '%{$_POST['searchProd']}%' OR p.produto_descricao LIKE '%{$_POST['searchProd']}%' OR p.produto_tamanho LIKE '%{$_POST['searchProd']}%' OR m.marca_nome LIKE '%{$_POST['searchProd']}%' OR c.categ_nome LIKE '%{$_POST['searchProd']}%' OR s.subcateg_nome LIKE '%{$_POST['searchProd']}%' OR d.depart_nome LIKE '%{$_POST['searchProd']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_nome, p.produto_tamanho, m.marca_nome FROM produto AS p JOIN categ AS c ON c.categ_id=p.produto_categ JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id JOIN marca_prod AS m ON p.produto_marca=m.marca_id WHERE p.produto_nome LIKE '%{$_POST['searchProd']}%' OR p.produto_descricao LIKE '%{$_POST['searchProd']}%' OR p.produto_tamanho LIKE '%{$_POST['searchProd']}%' OR m.marca_nome LIKE '%{$_POST['searchProd']}%' OR c.categ_nome LIKE '%{$_POST['searchProd']}%' OR s.subcateg_nome LIKE '%{$_POST['searchProd']}%' OR d.depart_nome LIKE '%{$_POST['searchProd']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $json['produtos'][] = $v;
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
            $json['produtos'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(produto_id) AS qtd FROM produto");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_nome, p.produto_tamanho, m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $json['produtos'][] = $v;
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