<?php
    use Model\User;
    $sql = new Sql();

    if (Project::isXmlHttpRequest()) {
        $json['status'] = 1;

        if (isset($_POST['searchPurch'])) {
            $search = $_POST['searchPurch'];
            $json['error'] = null;

            $results = $sql->select("SELECT c.compra_id, c.compra_registro, c.compra_total, s.status_nome, f.forma_nome FROM lista_compra l JOIN compra c ON c.compra_id = l.compra_id JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id JOIN produto p ON l.produto_id = p.produto_id WHERE c.usu_id = :id AND (c.compra_registro LIKE :search OR c.compra_total LIKE :search OR c.compra_hash LIKE :search OR f.forma_nome LIKE :search OR s.status_nome LIKE :search OR p.produto_nome LIKE :search OR p.produto_tamanho LIKE :search) ORDER BY c.compra_registro DESC", [
                ":search" => "%{$search}%",
                ":id" => $_SESSION[User::SESSION]['usu_id']
            ]);
            
            if (count($results) > 0) {
                $c = 0;
                foreach ($results as $row) {
                    if ($c != $row['compra_id']) {
                        $row['compra_registro'] = Project::formatRegister($row['compra_registro']);
                        $row['compra_total'] = Project::formatPriceToReal($row['compra_total']);
                        $json['compra'][] = $row;
                        $c = $row['compra_id'];
                    }
                }
            } else {
                $json['status'] = 0;
                $json['error'] = "Não houve resposta para o que foi pesquisado!";
            }
        } elseif (isset($_POST['showPurch'])) {
            $compra_id = $_POST['showPurch'];
            $json['error'] = null;

            $results = $sql->select("SELECT * FROM lista_compra l JOIN compra c ON c.compra_id = l.compra_id JOIN armazem a ON c.armazem_id = a.armazem_id JOIN cidade ci ON a.cidade_id = ci.cid_id JOIN estado es ON ci.est_id = es.est_id JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id JOIN entrega e ON e.compra_id = c.compra_id JOIN produto p ON l.produto_id = p.produto_id WHERE c.usu_id = :id AND c.compra_id = :c_id", [
                ":id" => $_SESSION[User::SESSION]['usu_id'],
                ":c_id" => $compra_id
            ]);
            
            if (count($results) > 0) {
                $c = 0;
                foreach ($results as $row) {
                    $row['compra_registro'] = Project::formatRegister($row['compra_registro']);
                    $row['entrega_horario'] = Project::formatRegister($row['entrega_horario']);
                    $row['compra_total'] = Project::formatPriceToReal($row['compra_total']);

                    $json['compra']['id'] = $row['compra_id'];
                    $json['compra']['armazem'] = $row['armazem_nome'] . " &nbsp;| &nbsp;" . $row['cid_nome'] . " - " . $row['est_uf'];
                    $json['compra']['registro'] = $row['compra_registro'];
                    $json['compra']['hash'] = $row['compra_hash'];
                    $json['compra']['total'] = $row['compra_total'];
                    $json['compra']['status'] = $row['status_nome'];
                    $json['compra']['forma_pag'] = $row['forma_nome'];

                    if ($row['compra_link'] != '') {
                        $json['compra']['link'] = $row['compra_link'];
                    }
                
                    $json['end']['horario'] = $row['entrega_horario'];
                    $json['end']['cep'] = $row['entrega_cep'];
                    $json['end']['log'] = $row['entrega_end'];
                    $json['end']['num'] = $row['entrega_num'];
                    $json['end']['complemento'] = $row['entrega_complemento'];
                    $json['end']['bairro'] = $row['entrega_bairro'];
                    $json['end']['cidade'] = $row['entrega_cidade'];
                    $json['end']['uf'] = $row['entrega_uf'];

                    $json['produto_id'][$c] = $row['produto_id'];
                    $json['produto_cript'][$c] = md5($row['produto_id']);
                    $json['produto_nome'][$c] = $row['produto_nome'];
                    $json['produto_qtd'][$c] = $row['produto_qtd'];
                    $c++;
                }
            } else {
                $json['status'] = 0;
                $json['error'] = "
                    Ocorreu um erro!<br/>
                    Compra não encontrada.<br/>
                    <a href='" . Project::baseUrlPhp() . "suporte/atendimento'>Contate-nos, por favor.</a>
                ";
            }
        } else {
            $json['error'] = null;

            $results = $sql->select("SELECT c.compra_id, c.compra_registro, c.compra_total, f.forma_nome FROM compra c JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id WHERE c.usu_id = :id ORDER BY c.compra_registro DESC", [
                ":id" => $_SESSION[User::SESSION]['usu_id']
            ]);
            
            if (count($results) > 0) {
                foreach ($results as $row) {
                    $row['compra_registro'] = Project::formatRegister($row['compra_registro']);
                    $row['compra_total'] = Project::formatPriceToReal($row['compra_total']);
                    $json['compra'][] = $row;
                }
            } else {
                $json['status'] = 0;
                $json['error'] = "Não há compras registradas!";
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
