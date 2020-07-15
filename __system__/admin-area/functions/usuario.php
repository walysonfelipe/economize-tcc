<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;
        
        if(isset($_POST['usu_first_name'])) {
            
        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(usu_id) AS qtd FROM usuario");
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

            $json['usuarios'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT u.usu_first_name, u.usu_last_name, u.usu_sexo, u.usu_cidade, u.usu_uf, t.tpu_usu_nome, u.usu_registro FROM usuario AS u JOIN tipousu AS t ON u.usu_tipo=t.tpu_id ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT u.usu_first_name, u.usu_last_name, u.usu_sexo, u.usu_cidade, u.usu_uf, t.tpu_usu_nome, u.usu_registro FROM usuario AS u JOIN tipousu AS t ON u.usu_tipo=t.tpu_id LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $usu = $sel->fetchAll();
                foreach($usu as $v) {
                    $v['usu_nome'] = $v['usu_first_name'] . " " . $v['usu_last_name'];

                    if($v['usu_sexo'] == "M")
                        $v['usu_sexo'] = "Masc";
                    elseif($v['usu_sexo'] == "F")
                        $v['usu_sexo'] = "Fem";
                    else
                        $v['usu_sexo'] = "Outro";

                    $v['usu_cidade'] = $v['usu_cidade'] . " - " . $v['usu_uf'];
                    
                    $exp = explode(" ", $v['usu_registro']);
                    $dia = explode("-", $exp[0]);
                    $v['usu_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                    $json['usuarios'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchUsuario'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['usuarios'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(u.usu_id) AS qtd FROM usuario AS u JOIN tipousu AS t ON u.usu_tipo=t.tpu_id WHERE u.usu_first_name LIKE '%{$_POST['searchUsuario']}%' OR u.usu_last_name LIKE '%{$_POST['searchUsuario']}%' OR u.usu_cpf LIKE '%{$_POST['searchUsuario']}%' OR u.usu_email LIKE '%{$_POST['searchUsuario']}%' OR u.usu_cep LIKE '%{$_POST['searchUsuario']}%' OR u.usu_end LIKE '%{$_POST['searchUsuario']}%' OR u.usu_num LIKE '%{$_POST['searchUsuario']}%' OR u.usu_complemento LIKE '%{$_POST['searchUsuario']}%' OR u.usu_bairro LIKE '%{$_POST['searchUsuario']}%' OR u.usu_cidade LIKE '%{$_POST['searchUsuario']}%' OR u.usu_uf LIKE '%{$_POST['searchUsuario']}%' OR u.usu_registro LIKE '%{$_POST['searchUsuario']}%' OR t.tpu_usu_nome LIKE '%{$_POST['searchUsuario']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT u.usu_first_name, u.usu_last_name, u.usu_sexo, u.usu_cidade, u.usu_uf, t.tpu_usu_nome, u.usu_registro FROM usuario AS u JOIN tipousu AS t ON u.usu_tipo=t.tpu_id WHERE u.usu_first_name LIKE '%{$_POST['searchUsuario']}%' OR u.usu_last_name LIKE '%{$_POST['searchUsuario']}%' OR u.usu_cpf LIKE '%{$_POST['searchUsuario']}%' OR u.usu_email LIKE '%{$_POST['searchUsuario']}%' OR u.usu_cep LIKE '%{$_POST['searchUsuario']}%' OR u.usu_end LIKE '%{$_POST['searchUsuario']}%' OR u.usu_num LIKE '%{$_POST['searchUsuario']}%' OR u.usu_complemento LIKE '%{$_POST['searchUsuario']}%' OR u.usu_bairro LIKE '%{$_POST['searchUsuario']}%' OR u.usu_cidade LIKE '%{$_POST['searchUsuario']}%' OR u.usu_uf LIKE '%{$_POST['searchUsuario']}%' OR u.usu_registro LIKE '%{$_POST['searchUsuario']}%' OR t.tpu_usu_nome LIKE '%{$_POST['searchUsuario']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['usu_nome'] = $v['usu_first_name'] . " " . $v['usu_last_name'];

                        if($v['usu_sexo'] == "M")
                            $v['usu_sexo'] = "Masc";
                        elseif($v['usu_sexo'] == "F")
                            $v['usu_sexo'] = "Fem";
                        else
                            $v['usu_sexo'] = "Outro";

                        $v['usu_cidade'] = $v['usu_cidade'] . " - " . $v['usu_uf'];
                        
                        $exp = explode(" ", $v['usu_registro']);
                        $dia = explode("-", $exp[0]);
                        $v['usu_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        $json['usuarios'][] = $v;
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
            $json['usuarios'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(usu_id) AS qtd FROM usuario");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT u.usu_first_name, u.usu_last_name, u.usu_sexo, u.usu_cidade, u.usu_uf, t.tpu_usu_nome, u.usu_registro FROM usuario AS u JOIN tipousu AS t ON u.usu_tipo=t.tpu_id LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        $v['usu_nome'] = $v['usu_first_name'] . " " . $v['usu_last_name'];

                        if($v['usu_sexo'] == "M")
                            $v['usu_sexo'] = "Masc";
                        elseif($v['usu_sexo'] == "F")
                            $v['usu_sexo'] = "Fem";
                        else
                            $v['usu_sexo'] = "Outro";

                        $v['usu_cidade'] = $v['usu_cidade'] . " - " . $v['usu_uf'];
                        
                        $exp = explode(" ", $v['usu_registro']);
                        $dia = explode("-", $exp[0]);
                        $v['usu_registro'] = $dia[2] . "/" . $dia[1] . "/" . $dia[0] . " às " . $exp[1];

                        $json['usuarios'][] = $v;
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