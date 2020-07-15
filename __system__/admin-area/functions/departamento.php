<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        if(isset($_POST['updDepart_id'])) {
            $json['depart'] = NULL;
            $sel = $conn->prepare("SELECT * FROM departamento WHERE depart_id={$_POST['updDepart_id']}");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $depart = $sel->fetchAll();
                    foreach($depart as $v) {
                        $json['depart'] = $v;
                    }
                }
            } else {
                $json['status'] = 0;
            }
        } elseif(isset($_POST['depart_nomeUpd'])) {
            $json['error'] = NULL;

            $depart_id = $_POST['depart_idUpd'];
            $depart_nome = trim($_POST['depart_nomeUpd']);
            $depart_desc = $_POST['depart_descUpd'];

            if(empty($depart_nome)) {
                $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Insira o nome do departamento</b></p>';
            } else {
                $sel = $conn->prepare("SELECT depart_nome FROM departamento WHERE depart_nome='{$depart_nome}' AND depart_id <> {$depart_id}");
                $sel->execute();
                if($sel->rowCount() > 0) {
                    $res = $sel->fetchAll();
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>O departamento ' . $res[0]['depart_nome'] . ' que inseriu na já foi previamente cadastrado</b></p>';
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
                $upd = $conn->prepare("UPDATE departamento SET depart_nome='$depart_nome', depart_desc='$depart_desc' WHERE depart_id=$depart_id");

                if(!$upd->execute()) {
                    $json['status'] = 0;
                    $json['error'] = '<p style="padding-bottom:10px;color:red;text-align:center;"><b>Um erro inesperado aconteceu. Tente novamente!</b></p>';
                }
            }
        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(depart_id) AS qtd FROM departamento");
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

            $json['departamentos'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM departamento ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM departamento LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $departs = $sel->fetchAll();
                foreach($departs as $v) {
                    if($v['depart_desc'] == '') 
                        $v['depart_desc']  = "-";

                    $json['departamentos'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchDepart'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['departamentos'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(depart_id) AS qtd FROM departamento WHERE depart_nome LIKE '%{$_POST['searchDepart']}%' OR depart_desc LIKE '%{$_POST['searchDepart']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM departamento WHERE depart_nome LIKE '%{$_POST['searchDepart']}%' OR depart_desc LIKE '%{$_POST['searchDepart']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        if($v['depart_desc'] == '') 
                            $v['depart_desc']  = "-";

                        $json['departamentos'][] = $v;
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
            $json['departamentos'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(depart_id) AS qtd FROM departamento");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM departamento LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        if($v['depart_desc'] == '') 
                            $v['depart_desc']  = "-";

                        $json['departamentos'][] = $v;
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