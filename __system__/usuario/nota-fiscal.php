<?php
    use Model\{User};

    $sql = new \Sql();

    // GERA PDF DA COMPRA NA PÁGINA DE HISTÓRICO DE COMPRAS DO CLIENTE
    if (isset($_SESSION['inf_func']) || isset($_SESSION[User::SESSION]['usu_id'])) {
        $perm = 1;
    }

    if (isset($_GET['compra']) && isset($perm)) {
        require_once '__system__/functions/fpdf/fpdf.php';

        $compra = $_GET['compra'];
        
        if (isset($_SESSION['inf_func'])) {
            $results = $sql->select("SELECT * FROM lista_compra l JOIN compra c ON c.compra_id = l.compra_id JOIN entrega e ON e.compra_id = c.compra_id JOIN usuario u ON c.usu_id = u.usu_id JOIN armazem a ON c.armazem_id = a.armazem_id JOIN cidade ci ON a.cidade_id = ci.cid_id JOIN estado es ON ci.est_id = es.est_id JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id JOIN produto p ON l.produto_id = p.produto_id WHERE c.compra_id = :c_id", [
                ":c_id" => $compra
            ]);
        } else {
            $results = $sql->select("SELECT * FROM lista_compra l JOIN compra c ON c.compra_id = l.compra_id JOIN entrega e ON e.compra_id = c.compra_id JOIN usuario u ON c.usu_id = u.usu_id JOIN armazem a ON c.armazem_id = a.armazem_id JOIN cidade ci ON a.cidade_id = ci.cid_id JOIN estado es ON ci.est_id = es.est_id JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id JOIN produto p ON l.produto_id = p.produto_id WHERE c.compra_id = :c_id AND c.usu_id = :usu_id", [
                ":c_id" => $compra,
                ":usu_id" => $_SESSION[User::SESSION]['usu_id']
            ]);
        }
        
        if (count($results) > 0) {
            $c = 0;
            foreach ($results as $row) {
                $row['compra_registro'] = \Project::formatRegister($row['compra_registro']);
                $row['entrega_horario'] = \Project::formatRegister($row['entrega_horario']);

                $inf['compra']['armazem'] = $row['armazem_nome'] . "  |  " . $row['cid_nome'] . " - " . $row['est_uf'];
                $inf['compra']['registro'] = $row['compra_registro'];
                $inf['compra']['hash'] = $row['compra_hash'];
                $inf['compra']['total'] = $row['compra_total'];
                $inf['compra']['status'] = $row['status_nome'];
                $inf['compra']['forma_pag'] = $row['forma_nome'];

                $inf['end']['horario'] = $row['entrega_horario'];
                $inf['end']['cep'] = $row['entrega_cep'];
                $inf['end']['log'] = $row['entrega_end'];
                $inf['end']['num'] = $row['entrega_num'];
                $inf['end']['complemento'] = $row['entrega_complemento'];
                $inf['end']['bairro'] = $row['entrega_bairro'];
                $inf['end']['cidade'] = $row['entrega_cidade'];
                $inf['end']['uf'] = $row['entrega_uf'];

                if ($inf['end']['complemento'] == "") {
                    $inf['end']['complemento'] = "-";
                }

                $inf['usuario']['nome'] = $row['usu_first_name'] . " " . $row['usu_last_name'];
                $inf['usuario']['cpf'] = $row['usu_cpf'];

                $results2 = $sql->select("SELECT f.funcionario_nome, f.funcionario_cpf FROM dados_entrega d JOIN funcionario f ON d.funcionario_id = f.funcionario_id WHERE d.entrega_id = :e_id", [
                    ":e_id" => $row['entrega_id']
                ]);
                if (count($results2) > 0) {
                    $t = 0;

                    foreach ($results2 as $row2) {
                        $inf['funcionario_nome'][$t] = $row2['funcionario_nome'];
                        $inf['funcionario_cpf'][$t] = $row2['funcionario_cpf'];
                        
                        $t++;
                    }
                }

                $inf['produto_id'][$c] = $row['produto_id'];
                $inf['produto_nome'][$c] = $row['produto_nome'];
                $inf['produto_qtd'][$c] = $row['produto_qtd'];
                $c++;
            }

            $day = Date("d/m/Y H:i:s");

            $pdf = new FPDF();
            $pdf->AddPage();
        
            $arquivo = "nota-fiscal.pdf";
            $tipo_pdf = "I";
        
            $pdf->Image('__system__/style/img/banner/logo_corPadrao.png', 91, 10, 30);
            
            $pdf->SetY(10);
            $pdf->SetFont("Arial", "", 8);
            $pdf->Cell(190, 10, "Data gerada: {$day}", 0, 1, "C");

            $pdf->SetY(18);
            $pdf->SetFont("Arial", "", 12);
            $pdf->Cell(190, 10, str_repeat("----", 15), 0, 1, "C");

            $pdf->SetY(26);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, "Data da compra:  {$inf['compra']['registro']}", 0, 1, "L");

            $str = iconv('UTF-8', 'windows-1252', "Cliente:  {$inf['usuario']['nome']}");
            $pdf->SetY(34);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            $pdf->SetY(42);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, "CPF:  {$inf['usuario']['cpf']}", 0, 1, "L");

            $str = iconv('UTF-8', 'windows-1252', "Código:  {$inf['compra']['hash']}");
            $pdf->SetY(50);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            $str = iconv('UTF-8', 'windows-1252', "Status:  {$inf['compra']['status']}");
            $pdf->SetY(58);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            $str = iconv('UTF-8', 'windows-1252', "Forma pagamento:  {$inf['compra']['forma_pag']}");
            $pdf->SetY(66);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            $str = iconv('UTF-8', 'windows-1252', "Armazém:  {$inf['compra']['armazem']}");
            $pdf->SetY(74);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            $pdf->SetY(82);
            $pdf->SetFont("Arial", "", 12);
            $pdf->Cell(190, 10, str_repeat("----", 15), 0, 1, "C");

            $pdf->SetY(90);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "B", 11);
            $pdf->Cell(90, 10, "TOTAL", 0, 1, "L");

            $pdf->SetY(90);
            $pdf->SetX(87);
            $pdf->SetFont("Arial", "B", 11);
            $pdf->Cell(90, 10, "R$ " . $inf['compra']['total'], 0, 1, "C");

            $y = 90;
            foreach ($inf['produto_nome'] as $k => $v) {
                $c = 0;
                if ($y >= 266) {
                    $pdf->AddPage();
                    $y = 0;
                }

                while ($c < $inf['produto_qtd'][$k]) {
                    if ($y >= 266) {
                        $pdf->AddPage();
                        $y = 0;
                    }
                    $y += 8;
                    $str = iconv('UTF-8', 'windows-1252', "000-" . $inf['produto_id'][$k] . " - " . $v);
                    $pdf->SetY($y);
                    $pdf->SetX(65);
                    $pdf->SetFont("Arial", "", 7);
                    $pdf->Cell(90, 10, $str, 0, 1, "L");

                    $c++;
                }
            }

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $pdf->SetY($y);
            $pdf->SetFont("Arial", "", 12);
            $pdf->Cell(190, 10, str_repeat("----", 15), 0, 1, "C");
            
            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, "Agendamento:  {$inf['end']['horario']}", 0, 1, "L");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $str = iconv('UTF-8', 'windows-1252', "CEP:  {$inf['end']['cep']}");
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $str = iconv('UTF-8', 'windows-1252', "Logradouro:  {$inf['end']['log']}, {$inf['end']['num']}");
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $str = iconv('UTF-8', 'windows-1252', "Complemento:  {$inf['end']['complemento']}");
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $str = iconv('UTF-8', 'windows-1252', "Bairro:  {$inf['end']['bairro']}");
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $str = iconv('UTF-8', 'windows-1252', "Localidade:  {$inf['end']['cidade']} - {$inf['end']['uf']}");
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(90, 10, $str, 0, 1, "L");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $pdf->SetY($y);
            $pdf->SetFont("Arial", "", 12);
            $pdf->Cell(190, 10, str_repeat("----", 15), 0, 1, "C");

            if (isset($inf['funcionario_nome'])) {
                if ($y >= 266) {
                    $pdf->AddPage();
                    $y = 0;
                }
                $y += 8;
                $pdf->SetY($y);
                $pdf->SetX(65);
                $pdf->SetFont("Arial", "B", 9);
                $pdf->Cell(90, 10, "REMETENTE", 0, 1, "L");

                foreach ($inf['funcionario_nome'] as $k => $v) {
                    if ($y >= 266) {
                        $pdf->AddPage();
                        $y = 0;
                    }
                    $y += 8;
                    $str = iconv('UTF-8', 'windows-1252', "Nome:  {$v}");
                    $pdf->SetY($y);
                    $pdf->SetX(65);
                    $pdf->SetFont("Arial", "", 9);
                    $pdf->Cell(90, 10, $str, 0, 1, "L");

                    if ($y >= 266) {
                        $pdf->AddPage();
                        $y = 0;
                    }
                    $y += 4;
                    $str = iconv('UTF-8', 'windows-1252', "CPF:  {$inf['funcionario_cpf'][$k]}");
                    $pdf->SetY($y);
                    $pdf->SetX(65);
                    $pdf->SetFont("Arial", "", 9);
                    $pdf->Cell(90, 10, $str, 0, 1, "L");
                }

                if ($y >= 266) {
                    $pdf->AddPage();
                    $y = 0;
                }
                $y += 8;
                $pdf->SetY($y);
                $pdf->SetFont("Arial", "", 12);
                $pdf->Cell(190, 10, str_repeat("----", 15), 0, 1, "C");
            }

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 8;
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Courier", "", 9);
            $pdf->Cell(78, 10, "Para maior garantia guarde este recibo.", 0, 1, "C");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y += 4;
            $pdf->SetY($y);
            $pdf->SetX(65);
            $pdf->SetFont("Courier", "", 9);
            $pdf->Cell(78, 10, "Muito obrigado e volte sempre!", 0, 1, "C");

            if ($y >= 266) {
                $pdf->AddPage();
                $y = 0;
            }
            $y -= 4;
            $pdf->Image('__system__/style/img/banner/Logo_fundoDegrade.png', 78, $y, 50);
            
            $pdf->Output($arquivo, $tipo_pdf);
        } else {
            echo "Sem permição de acesso ou compra inexistente!";
        }
    } else {
        require '__system__/404.php';
    }
