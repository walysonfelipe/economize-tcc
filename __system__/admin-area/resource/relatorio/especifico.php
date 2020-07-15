<?php
    // GERA PDF DAS COMPRAS NO HORÁRIO DEFINIDO NA URL
    if(isset($_SESSION['inf_func'])) {
        require_once '__system__/functions/connection/conn.php';
        require_once '__system__/functions/fpdf/fpdf.php';

        if(isset($_POST['dayRelat'])) {
            $type = $_POST['typeDateRelat'];
            
            if(!empty($_POST['dayRelat'])) {
                if($type == "day") {
                    $desc = TRUE;
                    $date[0] = substr($_POST['dayRelat'],-4);
                    $date[1] = substr($_POST['dayRelat'],3,2);
                    $date[2] = substr($_POST['dayRelat'],0,2);
                } elseif($type == "month") {
                    $desc = TRUE;
                    $date[0] = substr($_POST['dayRelat'],-4);
                    $date[1] = substr($_POST['dayRelat'],0,2);
                } else {
                    $desc = TRUE;
                    $date[0] = $_POST['dayRelat'];
                }
            }

            $sel = $conn->prepare("SELECT s.setor_permicao FROM funcionario AS f JOIN setor AS s ON f.funcionario_setor=s.setor_id WHERE f.funcionario_id={$_SESSION['inf_func']['funcionario_id']}");
            $sel->execute();
            $res = $sel->fetchAll();
            $permicoes = explode("-", $res[0]['setor_permicao']);
            if(in_array("g", $permicoes)) {
                $pdf = new FPDF();
                $arquivo = "relatorio-especifico-" . $_POST['dayRelat'] . ".pdf";
                $tipo_pdf = "I";

                $pdf->AddPage();

                $pdf->Image('__system__/img/banner/Logo_fundoDegrade.png', -7, -10, 70);
                
                $day = Date("d/m/Y H:i:s");
                $pdf->SetY(11);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->Cell(190, 10, "Data gerada: {$day}", 0, 1, "R");

                $str = iconv('UTF-8', 'windows-1252', "RELATÓRIO ESPECÍFICO");
                $pdf->SetY(20);
                $pdf->SetFont("Arial", "B", 13);
                $pdf->Cell(190, 10, $str, 0, 1, "C");

                $str = iconv('UTF-8', 'windows-1252', "Data: " . $_POST['dayRelat']);
                $pdf->SetY(27);
                $pdf->SetFont("Arial", "", 11);
                $pdf->Cell(190, 10, $str, "B", 1, "C");

                $esc = "";
                if(isset($date[0]))
                    $esc .= "WHERE YEAR(compra_registro)={$date[0]} ";
                if(isset($date[1]))
                    $esc .= "AND MONTH(compra_registro)={$date[1]} ";
                if(isset($date[2]))
                    $esc .= "AND DAY(compra_registro)={$date[2]} ";

                $sel = $conn->prepare("SELECT COUNT(compra_id) AS totCompra, SUM(compra_total) AS totVal FROM compra {$esc}");
                $sel->execute();
                $row = $sel->fetch( PDO::FETCH_ASSOC );
                if($row['totVal'] == NULL)
                    $row['totVal'] = "0.00";
                
                $pdf->SetY(47);
                $pdf->SetX(63);
                $pdf->SetFont("Arial", "", 10);
                $pdf->Cell(40, 10, "Total de vendas", 1, 0, "C");
                
                $pdf->SetFont("Arial", "B", 10);
                $pdf->Cell(40, 10, $row['totCompra'], 1, 1, "C");
                
                $pdf->SetX(63);
                $pdf->SetFont("Arial", "", 10);
                $pdf->Cell(40, 10, "Total de receita", 1, 0, "C");
                
                $pdf->SetFont("Arial", "B", 10);
                $pdf->Cell(40, 10, "R$ " . $row['totVal'], 1, 1, "C");


                $sel = $conn->prepare("SELECT SUM(l.produto_qtd) AS totProd FROM lista_compra AS l JOIN compra AS c ON l.compra_id=c.compra_id " . (($esc != "") ? $esc . " AND" : "WHERE" ) . " c.status_id <> 4");
                $sel->execute();
                $row = $sel->fetch( PDO::FETCH_ASSOC );
                if($row['totProd'] == NULL)
                    $row['totProd'] = 0;
                
                $pdf->SetX(63);
                $pdf->SetFont("Arial", "", 10);
                $pdf->Cell(40, 10, "Produtos vendidos", 1, 0, "C");
                
                $pdf->SetFont("Arial", "B", 10);
                $pdf->Cell(40, 10, $row['totProd'], 1, 1, "C");

                $sel = $conn->prepare("SELECT a.armazem_id, a.armazem_nome, c.cid_nome, e.est_uf FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id");
                $sel->execute();

                $x = 15;
                $y = 90;
                while($row = $sel->fetch( PDO::FETCH_ASSOC )) {
                    if(isset($_POST[$row['armazem_id']])) {
                        if($y >= 266) {
                            $pdf->AddPage();
                            $y = 0;
                        }

                        $array = explode(" ", $row['cid_nome']);
                        if(count($array) > 1) {
                            $qtd = strlen($row['cid_nome']) - (strlen($array[0]) + 1);
                            $row['cid_nome'] = substr($row['cid_nome'],0,1) . ". " . substr($row['cid_nome'],-$qtd);
                        }

                        $str = iconv('UTF-8', 'windows-1252', $row['armazem_nome'] . " | " . $row['cid_nome'] . " - " . $row['est_uf']);
                        $pdf->SetY($y);
                        $pdf->SetX($x);
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(80, 10, $str, 1, 1, "C");
                        
                        $sel2 = $conn->prepare("SELECT COUNT(compra_id) AS totCompra, SUM(compra_total) AS totVal FROM compra " . (($esc != "") ? $esc . " AND" : "WHERE" ) . " armazem_id={$row['armazem_id']}");
                        $sel2->execute();
                        $row2 = $sel2->fetch( PDO::FETCH_ASSOC );
                        if($row2['totVal'] == NULL)
                            $row2['totVal'] = "0.00";

                        $pdf->SetX($x);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 10, "Total de vendas", 1, 0, "C");
                        
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(40, 10, $row2['totCompra'], 1, 1, "C");
                        
                        $pdf->SetX($x);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 10, "Total de receita", 1, 0, "C");
                        
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(40, 10, "R$ " . $row2['totVal'], 1, 1, "C");

                        $sel3 = $conn->prepare("SELECT SUM(l.produto_qtd) AS totProd FROM lista_compra AS l JOIN compra AS c ON l.compra_id=c.compra_id " . (($esc != "") ? $esc . " AND" : "WHERE" ) . " c.status_id <> 4 AND c.armazem_id={$row['armazem_id']}");
                        $sel3->execute();
                        $row3 = $sel3->fetch( PDO::FETCH_ASSOC );
                        if($row3['totProd'] == NULL)
                            $row3['totProd'] = 0;
                        
                        $pdf->SetX($x);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 10, "Produtos vendidos", 1, 0, "C");
                        
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(40, 10, $row3['totProd'], 1, 1, "C");

                        if($x == 110) {
                            $x = 15;
                            $y += 60;
                        } else {
                            $x = 110;
                        }
                    }
                }
                
                $pdf->Output($arquivo, $tipo_pdf);
            } else {
                echo "Sem permição de acesso à este relatório!";
            }
        } else {
            header("Location: geral");
        }
    } else {
        require '__system__/404.php';
    }
?>