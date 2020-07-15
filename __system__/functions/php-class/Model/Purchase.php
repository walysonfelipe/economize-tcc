<?php
    use Mailer\Message;
    use Model\{Cart, User, Storage};

    namespace Model;
    
    class Purchase extends \Model
    {
        const SESSION = "EconomizePurchaseSession";
        const PAYMENT = false; // false = Teste | true = Produção (Chama pagamentos profissionais)
        
        public static function createPurchase($xml)
        {
            $sql = new \Sql();
            $cart = Cart::getCart();

            if (!isset($xml->error)) {
                $cep = substr($xml->shipping->address->postalCode, 0, 5) . "-" . substr($xml->shipping->address->postalCode, -3);

                $results = $sql->select("CALL sp_compra_create(:arm_id, :hash, :total, :link, :usu_id, :status_id, :forma_id, :ent_hora, :ent_cep, :ent_log, :ent_num, :ent_comp, :ent_bairro, :ent_cid, :ent_uf) ", [
                    ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'], ":hash" => $xml->code, 
                    ":total" => $xml->grossAmount, ":link" => (isset($xml->paymentLink) ? $xml->paymentLink : ""),
                    ":usu_id" => $_SESSION[User::SESSION]['usu_id'], ":status_id" => $xml->status, 
                    ":forma_id" => $xml->paymentMethod->type, ":ent_hora" => $_SESSION['agend_horario'],
                    ":ent_cep" => $cep, ":ent_log" => $xml->shipping->address->street, 
                    ":ent_num" => $xml->shipping->address->number, 
                    ":ent_comp" => $xml->shipping->address->complement, 
                    ":ent_bairro" => $xml->shipping->address->district, ":ent_cid" => $xml->shipping->address->city,
                    ":ent_uf" => $xml->shipping->address->state
                ]);
                
                if (count($results) === 0) {
                    $xml->errorInsert = "Um erro inesperado aconteceu! Estamos trabalhando para consertá-lo. Desculpe-nos!";
                } else {
                    foreach ($cart['produtosCart'] as $k => $v) {
                        $stmt = $sql->query("INSERT INTO lista_compra(compra_id, produto_id, produto_qtd) VALUE (:compra_id, :prod_id, :prod_qtd)", [
                            ":compra_id" => $results[0]['compra_id'], ":prod_id" => $v['produto_id'],
                            ":prod_qtd" => $_SESSION[Cart::SESSION][$v['produto_id']]
                        ]);

                        if (!$stmt) {
                            $xml->errorInsert = "Um erro inesperado aconteceu! Estamos trabalhando para consertá-lo. Desculpe-nos!";
                            break;
                        }
                    }
        
                    if (!isset($xml->errorInsert)) {
                        $_SESSION['paymentDone'] = true;
    
                        $purchase = Purchase::getPurchaseById($results[0]['compra_id']);
                        
                        if (count($purchase) > 0) {
                            $registro = \Project::formatRegister($purchase[0]['compra_registro']);
                            
                            $_SESSION['compra']['id'] = $purchase[0]['compra_id'];
                            $_SESSION['compra']['armazem'] = $purchase[0]['armazem_nome'] . " &nbsp;| &nbsp;" . $purchase[0]['cid_nome'] . " - " . $purchase[0]['est_uf'];
                            $_SESSION['compra']['hash'] = $purchase[0]['compra_hash'];
                            $_SESSION['compra']['total'] = $purchase[0]['compra_total'];
                            $_SESSION['compra']['status'] = $purchase[0]['status_nome'];
                            $_SESSION['compra']['forma_pag'] = $purchase[0]['forma_nome'];
        
                            if ($purchase[0]['compra_link'] !== '' && $purchase[0]['compra_link'] !== null) {
                                $_SESSION['compra']['link'] = $purchase[0]['compra_link'];
                            }
                            
                            foreach ($purchase as $k => $row) {
                                $_SESSION['produto_id'][$k] = $row['produto_id'];
                                $_SESSION['produto_nome'][$k] = $row['produto_nome'];
                                $_SESSION['produto_qtd'][$k] = $row['produto_qtd'];
                            }

                            Purchase::sendPurchMail($_SESSION[User::SESSION]['usu_email'], $_SESSION[User::SESSION]['usu_first_name'], $_SESSION['compra']['id'], $registro);
                        }
                    }
                }
            }

            return $xml;
        }

        public static function getPurchaseById($id)
        {
            $sql = new \Sql();

            return $sql->select("SELECT * FROM lista_compra l JOIN compra c ON c.compra_id = l.compra_id JOIN armazem a ON c.armazem_id = a.armazem_id JOIN cidade ci ON a.cidade_id = ci.cid_id JOIN estado es ON ci.est_id = es.est_id JOIN status_compra s ON c.status_id = s.status_id JOIN forma_pag f ON c.forma_id = f.forma_id JOIN produto p ON l.produto_id = p.produto_id WHERE c.compra_id = :id", [
                ":id" => $id
            ]);
        }

        public static function sendPurchMail($email, $name, $purch_id, $register)
        {
            $subject = "e.conomize - Compra realizada com sucesso";
            $template = "template-purchase";

            $link = \Project::baseUrlPhp() . "usuario/nota-fiscal?compra={$purch_id}";

            $mail = new Message(
                Mailer::EMAIL_FROM, Mailer::NAME_FROM, $email, $name,
                $subject, $template, [
                    "**LINK**" => $link, "**NOME**" => $name, "**HORARIO**" => $register
                ]
            );

            $mail->send();
        }
    }
