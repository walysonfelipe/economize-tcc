<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['banner_nome'])) {
            $json['error'] = NULL;

            for($k=0; $k<count($_POST['banner_nome']); $k++) {
                $c = $k + 1;
                $banner_nome[$k] = trim($_POST['banner_nome'][$k]);
                $banner_status[$k] = $_POST['banner_status'][$k];

                $banner_path = $_FILES['banner_path']['name'];
                $banner_path_tmp = $_FILES['banner_path']['tmp_name'];

                if($banner_status[$k] == "*000*") {
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o status do banner na ' . $c . 'ª parte de cadastro</b></p>';
                } else {
                    if(empty($banner_path[$k])) {
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o banner na ' . $c . 'ª parte de cadastro</b></p>';
                    } else {
                        $sel = $conn->prepare("SELECT banner_nome FROM banner WHERE banner_nome='{$banner_nome[$k]}'");
                        $sel->execute();
                        if($sel->rowCount() > 0) {
                            $res = $sel->fetchAll();
                            $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O banner ' . $res[0]['banner_nome'] . ' que inseriu na ' . $c . 'ª parte já foi previamente cadastrado</b></p>';
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
                for($k=0; $k<count($banner_nome); $k++) {
                    move_uploaded_file($banner_path_tmp[$k], "__system__/img/banner/{$banner_path[$k]}");

                    if(!empty($banner_nome[$k])) {
                        $ins = $conn->prepare("INSERT INTO banner(banner_nome, banner_path, banner_status) VALUES ('{$banner_nome[$k]}', '{$banner_path[$k]}', {$banner_status[$k]})");
                    } else {
                        $ins = $conn->prepare("INSERT INTO banner(banner_path, banner_status) VALUES ('{$banner_path[$k]}', {$banner_status[$k]})");
                    }

                    if(!$ins->execute()) {
                        $json['status'] = 0;
                        $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                        break;
                    }
                }
            }
        } elseif(isset($_POST['updBanner_id'])) {
            $json['banner'] = NULL;
            $sel = $conn->prepare("SELECT * FROM banner WHERE banner_id={$_POST['updBanner_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $banner = $sel->fetchAll();
                    foreach($banner as $v) {
                        $json['banner'] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['banner_nomeUpd'])) {
            $json['error'] = NULL;

            $banner_id = $_POST['banner_idUpd'];
            $banner_nome = trim($_POST['banner_nomeUpd']);
            $banner_status = $_POST['banner_statusUpd'];

            $banner_path = $_FILES['banner_pathUpd']['name'];
            $banner_path_tmp = $_FILES['banner_pathUpd']['tmp_name'];

            if($banner_status == "*000*") {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o status do banner</b></p>';
            } else {
                $sel = $conn->prepare("SELECT banner_nome FROM banner WHERE banner_nome='{$banner_nome}' AND banner_id <> {$banner_id}");
                $sel->execute();
                if($sel->rowCount() > 0) {
                    $res = $sel->fetchAll();
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O banner ' . $res[0]['banner_nome'] . ' que inseriu já foi previamente cadastrado</b></p>';
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

            if($json['error']) {
                $json['status'] = 0;
            } else {
                if(!empty($banner_path)) {
                    move_uploaded_file($banner_path_tmp, "__system__/img/banner/$banner_path");
                    $new_img = TRUE;
                }

                if(isset($new_img)) {
                    $upd = $conn->prepare("UPDATE banner SET banner_nome='$banner_nome', banner_status=$banner_status, banner_path=$banner_path WHERE banner_id=$banner_id");
                } else {
                    $upd = $conn->prepare("UPDATE banner SET banner_nome='$banner_nome', banner_status=$banner_status WHERE banner_id=$banner_id");
                }

                if(!$upd->execute()) {
                    $json['status'] = 0;
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                }
            }
        } elseif(isset($_POST['delBanner_id'])) {
            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(!in_array("e", $permicoes)) {
                $json['status'] = 0;
                $json['error_del'] = 'Você não tem permição para excluir';
            } else {
                $del = $conn->prepare("DELETE FROM banner WHERE banner_id=:id");
                $del->bindValue(":id", "{$_POST['delBanner_id']}");
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

            $sel = $conn->prepare("SELECT COUNT(banner_id) AS qtd FROM banner");
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

            $json['banners'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM banner ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM banner ORDER BY banner_status DESC LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $prods = $sel->fetchAll();
                foreach($prods as $v) {
                    if($v['banner_status'] == 0)
                        $v['banner_status'] = '<span class="noVisuAtend">DESATIVADO</span>';
                    else
                        $v['banner_status'] = '<span class="jaVisuAtend">ATIVADO</span>';

                    $json['banners'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchBanner'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['banners'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(banner_id) AS qtd FROM banner WHERE banner_nome LIKE '%{$_POST['searchBanner']}%' OR banner_status LIKE '%{$_POST['searchBanner']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM banner WHERE banner_nome LIKE '%{$_POST['searchBanner']}%' OR banner_status LIKE '%{$_POST['searchBanner']}%' ORDER BY banner_status DESC LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        if($v['banner_status'] == 0)
                            $v['banner_status'] = '<span class="noVisuAtend">DESATIVADO</span>';
                        else
                            $v['banner_status'] = '<span class="jaVisuAtend">ATIVADO</span>';

                        $json['banners'][] = $v;
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
            $json['banners'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(banner_id) AS qtd FROM banner");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM banner ORDER BY banner_status DESC LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        if($v['banner_status'] == 0)
                            $v['banner_status'] = '<span class="noVisuAtend">DESATIVADO</span>';
                        else
                            $v['banner_status'] = '<span class="jaVisuAtend">ATIVADO</span>';

                        $json['banners'][] = $v;
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