<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['post_title'])) {
            $json['error'] = NULL;

            for($k=0; $k<count($_POST['post_title']); $k++) {
                $c = $k + 1;
                $post_title[$k] = $_POST['post_title'][$k];
                $post_text[$k] = $_POST['post_text'][$k];
                $post_envio[$k] = $_POST['post_envio'][$k];

                $post_img = $_FILES['post_img']['name'];
                $post_img_tmp = $_FILES['post_img']['tmp_name'];

                if(empty($post_title[$k])) {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o título da postagem na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(empty($post_text[$k])) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o texto da postagem na ' . $c . 'ª parte de cadastro</b></p>';
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

                if($json['error']) {
                    break;
                }
            }

            if($json['error']) {
                $json['status'] = 0;
            } else {
                for($k=0; $k<count($post_img); $k++) {
                    if(empty($post_img[$k])) {
                        $post_img[$k] = "logo_economize.png";
                    } else {
                        move_uploaded_file($post_img_tmp[$k], "__system__/img/postagem/{$post_img[$k]}");
                    }
                    $ins = $conn->prepare("INSERT INTO postagem(post_img,post_title,post_text) VALUES('{$post_img[$k]}', '{$post_title[$k]}','{$post_text[$k]}')");

                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    } else {
                        if($post_envio[$k] == 1) {
                            $sel = $conn->prepare("SELECT usu_email FROM usuario WHERE usu_mailmkt=1");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                                    // Envia email para os usuários com mailmkt ativados
                                }
                            }
                        }
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
            $post_img = trim($_POST['nome_produto_upd']);
            $post_title = $_POST['marca_produto_upd'];
            $post_text = $_POST['categoria_produto_upd'];
            $post_envio = $_POST['descricao_produto_upd'];
            $tamanho_produto = trim($_POST['produto_tamanho_upd']);

            $imagem_produto = $_FILES['imagem_produto_upd']['name'];
            $imagem_produto_tmp = $_FILES['imagem_produto_upd']['tmp_name'];

            if(empty($post_img)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do produto</b></p>';
            } else {
                if($post_title == "*000*") {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a marca do produto</b></p>';
                } else {
                    if($post_text == "*000*") {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira a categoria do produto</b></p>';
                    } else {
                        if(empty($tamanho_produto)) {
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o volume do produto</b></p>';
                        } else {
                            $sel = $conn->prepare("SELECT * FROM produto WHERE produto_id='{$id_produto}'");
                            $sel->execute();
                            if($sel->rowCount() > 0) {
                                $res = $sel->fetchAll();
                                if(($post_img == $res[0]['produto_nome']) &&
                                    ($post_title == $res[0]['produto_marca']) &&
                                    ($post_text == $res[0]['produto_categ']) &&
                                    (empty($imagem_produto)) &&
                                    ($post_envio == $res[0]['produto_descricao']) &&
                                    ($tamanho_produto == $res[0]['produto_tamanho'])
                                    ) {
                                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Não houve alterações</b></p>';
                                } else {
                                    $sel = $conn->prepare("SELECT produto_nome, produto_tamanho FROM produto WHERE produto_nome='$post_img' AND produto_tamanho='$tamanho_produto' AND produto_id <> $id_produto");
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
                    $upd = $conn->prepare("UPDATE produto SET produto_nome='$post_img', produto_marca=$post_title, produto_categ=$post_text, produto_img='$imagem_produto', produto_descricao='$post_envio', produto_tamanho='$tamanho_produto' WHERE produto_id=$id_produto");
                    if(!$upd->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                    }
                } else {
                    $upd = $conn->prepare("UPDATE produto SET produto_nome='$post_img', produto_marca=$post_title, produto_categ=$post_text, produto_descricao='$post_envio', produto_tamanho='$tamanho_produto' WHERE produto_id=$id_produto");
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

            $sel = $conn->prepare("SELECT COUNT(post_id) AS qtd FROM postagem");
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

            $json['postagens'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM postagem ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM postagem LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $prods = $sel->fetchAll();
                foreach($prods as $v) {
                    $v['post_title'] = (strlen($v['post_title']) > 100) ? substr($v['post_title'],0,100) . "..." : $v['post_title'];
                    $v['post_text'] = (strlen($v['post_text']) > 250) ? substr($v['post_text'],0,250) . "..." : $v['post_text'];

                    $exp = explode(" ", $v['post_registro']);
                    $day = explode("-", $exp[0]);
                    $v['post_registro'] = $day[2] . "/" . $day[1] . "/" . $day[0] . " às " . $exp[1];

                    $json['postagens'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchPost'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['postagens'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(post_id) AS qtd FROM postagem WHERE post_title LIKE '%{$_POST['searchPost']}%' OR post_text LIKE '%{$_POST['searchPost']}%' OR post_registro LIKE '%{$_POST['searchPost']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM postagem WHERE post_title LIKE '%{$_POST['searchPost']}%' OR post_text LIKE '%{$_POST['searchPost']}%' OR post_registro LIKE '%{$_POST['searchPost']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['post_title'] = (strlen($v['post_title']) > 100) ? substr($v['post_title'],0,100) . "..." : $v['post_title'];
                        $v['post_text'] = (strlen($v['post_text']) > 250) ? substr($v['post_text'],0,250) . "..." : $v['post_text'];

                        $exp = explode(" ", $v['post_registro']);
                        $day = explode("-", $exp[0]);
                        $v['post_registro'] = $day[2] . "/" . $day[1] . "/" . $day[0] . " às " . $exp[1];

                        $json['postagens'][] = $v;
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
            $json['postagens'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(post_id) AS qtd FROM postagem");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM postagem LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['post_title'] = (strlen($v['post_title']) > 100) ? substr($v['post_title'],0,100) . "..." : $v['post_title'];
                        $v['post_text'] = (strlen($v['post_text']) > 250) ? substr($v['post_text'],0,250) . "..." : $v['post_text'];

                        $exp = explode(" ", $v['post_registro']);
                        $day = explode("-", $exp[0]);
                        $v['post_registro'] = $day[2] . "/" . $day[1] . "/" . $day[0] . " às " . $exp[1];

                        $json['postagens'][] = $v;
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