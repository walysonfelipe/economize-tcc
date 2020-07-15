<?php
    if(isXmlHttpRequest()) {
        $json['status'] = 1;
        $json['error'] = NULL;

        if(isset($_POST['hora'])) {
            
        } elseif(isset($_POST['data_sort'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização
            
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(hora_id) AS qtd FROM horarios_entrega");
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

            $json['horarios'] = array();
            
            if(isset($_SESSION['data_sort'][$sort])) {
                $sel = $conn->prepare("SELECT * FROM horarios_entrega ORDER BY $sort {$_SESSION['data_sort'][$sort]} LIMIT $begin, $qtd_result");
            } else {
                $sel = $conn->prepare("SELECT * FROM horarios_entrega ORDER BY dia, hora LIMIT $begin, $qtd_result");
            }
            $sel->execute();
            if($sel->rowCount() > 0) {
                $hora = $sel->fetchAll();
                foreach($hora as $v) {
                    if($v['dia'] == 1) 
                        $v['dia'] = "1 - Segunda-feira";
                    elseif($v['dia'] == 2)
                        $v['dia'] = "2 - Terça-feira";
                    elseif($v['dia'] == 3)
                        $v['dia'] = "3 - Quarta-feira";
                    elseif($v['dia'] == 4)
                        $v['dia'] = "4 - Quinta-feira";
                    elseif($v['dia'] == 5)
                        $v['dia'] = "5 - Sexta-feira";
                    elseif($v['dia'] == 6)
                        $v['dia'] = "6 - Sábado";
                    else
                        $v['dia'] = "7 - Domingo";
                    
                    $json['horarios'][] = $v;
                    $json['registrosMostra']++;
                }
            }
        } elseif(isset($_POST['searchHorario'])) {
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $qtd_result = filter_input(INPUT_POST, 'qtd_result', FILTER_SANITIZE_NUMBER_INT);

            $begin = ($page * $qtd_result) - $qtd_result; // Calcula o início da visualização

            $json['empty'] = TRUE;
            $json['horarios'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(hora_id) AS qtd FROM horarios_entrega WHERE dia LIKE '%{$_POST['searchHorario']}%' OR hora LIKE '%{$_POST['searchHorario']}%'");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM horarios_entrega WHERE dia LIKE '%{$_POST['searchHorario']}%' OR hora LIKE '%{$_POST['searchHorario']}%' LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        if($v['dia'] == 1) 
                            $v['dia'] = "1 - Segunda-feira";
                        elseif($v['dia'] == 2)
                            $v['dia'] = "2 - Terça-feira";
                        elseif($v['dia'] == 3)
                            $v['dia'] = "3 - Quarta-feira";
                        elseif($v['dia'] == 4)
                            $v['dia'] = "4 - Quinta-feira";
                        elseif($v['dia'] == 5)
                            $v['dia'] = "5 - Sexta-feira";
                        elseif($v['dia'] == 6)
                            $v['dia'] = "6 - Sábado";
                        else
                            $v['dia'] = "7 - Domingo";
                        
                        $json['horarios'][] = $v;
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
            $json['horarios'] = array();
            $json['registrosMostra'] = 0;

            $sel = $conn->prepare("SELECT COUNT(hora_id) AS qtd FROM horarios_entrega");
            $sel->execute();
            $row = $sel->fetch( PDO::FETCH_ASSOC );
            $json['registrosTotal'] = $row['qtd'];

            $sel = $conn->prepare("SELECT * FROM horarios_entrega ORDER BY dia, hora LIMIT $begin, $qtd_result");
            $sel->execute();
            if($sel) {
                if($sel->rowCount() > 0) {
                    $json['empty'] = FALSE;
                    while($v = $sel->fetch( PDO::FETCH_ASSOC )) {
                        if($v['dia'] == 1) 
                            $v['dia'] = "1 - Segunda-feira";
                        elseif($v['dia'] == 2)
                            $v['dia'] = "2 - Terça-feira";
                        elseif($v['dia'] == 3)
                            $v['dia'] = "3 - Quarta-feira";
                        elseif($v['dia'] == 4)
                            $v['dia'] = "4 - Quinta-feira";
                        elseif($v['dia'] == 5)
                            $v['dia'] = "5 - Sexta-feira";
                        elseif($v['dia'] == 6)
                            $v['dia'] = "6 - Sábado";
                        else
                            $v['dia'] = "7 - Domingo";
                        
                        $json['horarios'][] = $v;
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