<?php
    use Model\{Product, User};

    namespace Model;
    
    class Cart extends \Model
    {
        const SESSION = "EconomizeCartSession";

        public static function getContentCart()
        {
            $results = [];
            
            if (isset($_SESSION[Cart::SESSION])) {
                $cart = $_SESSION[Cart::SESSION];
                $products =  Product::getProductsByIds(implode(',', array_keys($cart)));
    
                foreach ($products as $k => $product) {
                    if ($product['produto_desconto_porcent'] != "") {
                        $product["produto_desconto"] = \Project::promotionCalculation($product["produto_desconto_porcent"], $product["produto_preco"], false);
                        $results[$k] = $product;
                        $results[$k]['subtotal'] = $cart[$product['produto_id']] * $product['produto_desconto'];
                        $product["produto_desconto"] = \Project::formatPriceToReal($product["produto_desconto"]);
                    } elseif ($product['promo_desconto']) {
                        $product["produto_desconto"] = \Project::promotionCalculation($product["promo_desconto"], $product["produto_preco"], false);
                        $results[$k] = $product;
                        $results[$k]['subtotal'] = $cart[$product['produto_id']] * $product['produto_desconto'];
                        $product["produto_desconto"] = \Project::formatPriceToReal($product["produto_desconto"]);
                    } else {
                        $results[$k] = $product;
                        $results[$k]['subtotal'] = $cart[$product['produto_id']] * $product['produto_preco'];
                    }
                }
            }
            
            return $results;
        }

        public static function getCart()
        {
            $dados = [];
            $dados['empty'] = true;
            $dados['logado'] = true;
            $dados['totDesconto'] = 0;
            $dados['totCompra'] = 0;
            $dados['produtosCart'] = [];
            $_SESSION['totCompra'] = 0;

            if (isset($_SESSION[Cart::SESSION])) {
                if (!empty($_SESSION[Cart::SESSION])) {
                    $dados['empty'] = false;
                    if (!User::checkLogin()) {
                        $dados['logado'] = false;
                    }
        
                    $resultsCarts = Cart::getContentCart();
                    
                    foreach ($resultsCarts as $k => $v) {
                        if ($v['produto_desconto_porcent'] <> "") {
                            $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"], false);
                            $dados['totDesconto'] += Cart::getTotDesconto($v['produto_desconto_porcent'], $v["produto_preco"], $v['produto_id']);
                        } elseif ($v['promo_desconto']) {
                            $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"], false);
                            $dados['totDesconto'] += Cart::getTotDesconto($v['promo_desconto'], $v["produto_preco"], $v['produto_id']);
                        }

                        if (isset($v["produto_desconto"])) {
                            $v["produto_desconto"] = \Project::formatPriceToReal($v["produto_desconto"]);
                        }
                        
                        $_SESSION['totCompra'] += $v['subtotal'];
                        $_SESSION['subtotal'][$k] = $v['subtotal'];
                        $v['subtotal'] = \Project::formatPriceToReal($v['subtotal']);
                        $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);
                        $v['carrinho'] = $_SESSION[Cart::SESSION][$v['produto_id']];
        
                        array_push($dados['produtosCart'], $v);
                    }
                    
                    if (isset($_SESSION['cupom_compra'])) {
                        $_SESSION['totCompraCupom'] = $_SESSION['totCompra'];
                        $dados['totCupomPorc'] = \Project::promotionCalculation($_SESSION['cupom_compra']['cupom_desconto_porcent'], $_SESSION['totCompra'], false);
                        $_SESSION['totCompra'] -= $dados['totCupomPorc'];
                    }

                    if (isset($_SESSION['subcid_frete'])) {
                        if ($_SESSION['subcid_frete'] > 0) {
                            $_SESSION['totCompra'] += $_SESSION['subcid_frete'];
                        }
                        $dados['frete'] = \Project::formatPriceToReal($_SESSION['subcid_frete']);
                    }
        
                    $dados['totDesconto'] = \Project::formatPriceToReal($dados['totDesconto']);
                    $dados['totCompra'] = \Project::formatPriceToReal($_SESSION['totCompra']);
                }
            }

            return $dados;
        }

        public static function getTotDesconto($discount, $price, $id)
        {
            $priceDiscount = $price * ($discount / 100);
            $priceDiscount = \Project::formatPriceToDolar($priceDiscount);
            return $priceDiscount * $_SESSION[Cart::SESSION][$id];
        }

        public static function clearCart()
        {
            unset($_SESSION['totCompra']);
            unset($_SESSION[Cart::SESSION]);
            unset($_SESSION['subtotal']);
            unset($_SESSION[Cart::SESSION]);
            unset($_SESSION['end_agend']);
            unset($_SESSION['agend_horario']);
            unset($_SESSION['pagamento']);
            unset($_SESSION['paymentDone']);
            unset($_SESSION['compra']);
            unset($_SESSION['produto_id']);
            unset($_SESSION['produto_nome']);
            unset($_SESSION['produto_qtd']);
    
            if (isset($_SESSION['totCompraCupom'])) {
                unset($_SESSION['totCompraCupom']);
            }
    
            if (isset($_SESSION['cupom_compra'])) {
                unset($_SESSION['cupom_compra']);
            }
    
            if (isset($_SESSION['subcid_id'])) {
                unset($_SESSION['subcid_id']);
                unset($_SESSION['subcid_frete']);
            }
        }

        public static function clearCartToChangeArm()
        {
            if (isset($_SESSION[Cart::SESSION])) {
                unset($_SESSION[Cart::SESSION]);
            }

            if (isset($_SESSION['totCompra'])) {
                unset($_SESSION['totCompra']);
            }
            
            if (isset($_SESSION['totCompraCupom'])) {
                unset($_SESSION['totCompraCupom']);
            }

            if (isset($_SESSION['end_agend'])) {
                unset($_SESSION['end_agend']);
            }

            if (isset($_SESSION['agend_horario'])) {
                unset($_SESSION['agend_horario']);
            }

            if (isset($_SESSION['subcid_id'])) {
                unset($_SESSION['subcid_id']);
            }
        }

        public static function getQuantity($id)
        {
            $quantity = 0;

            if (isset($_SESSION[Cart::SESSION][$id])) {
                $quantity = $_SESSION[Cart::SESSION][$id];
            }

            return $quantity;
        }

        public static function addProduct($id, $qtd_post)
        {
            $data = [];
            $data['type'] = "success";
            $data['answer'] = null;

            if ($qtd_post > 0) {
                $qtd_prod = Product::getQuantityInArm($id);
                
                if (($qtd_prod >= 20) && ($qtd_post <= 20)) {
                    $_SESSION[Cart::SESSION][$id] = $qtd_post;
                    $data['answer'] = "Produto adicionado ao carrinho";
                } elseif (($qtd_prod < 20) && ($qtd_post <= $qtd_prod)) {
                    $_SESSION[Cart::SESSION][$id] = $qtd_post;
                    $data['answer'] = "Produto adicionado ao carrinho";
                } elseif (($qtd_prod >= 20) && ($qtd_post >= 20)) {
                    $data['type'] = "error";
                    $data['answer'] = "No máximo 20 produtos";
                } else {
                    $data['type'] = "error";
                    $data['answer'] = "No máximo $qtd_prod produtos";
                }
            } else {
                if (isset($_SESSION[Cart::SESSION][$id])) {
                    unset($_SESSION[Cart::SESSION][$id]);
                    if (empty($_SESSION[Cart::SESSION])) {
                        Cart::clearCartToChangeArm();
                    }
                    $data['answer'] = "Produto removido do carrinho";
                } else {
                    $data['type'] = "error";
                    $data['answer'] = "Produto não está no carrinho";
                }
            }

            return $data;
        }

        public static function removeProduct($id)
        {
            $data = [];
            $data['type'] = "success";
            $data['answer'] = null;

            if (isset($_SESSION[Cart::SESSION][$id])) {
                unset($_SESSION[Cart::SESSION][$id]);
                $data['answer'] = "Produto retirado do carrinho";
            } else {
                $data['type'] = "error";
                $data['answer'] = "Produto não está mais no carrinho";
            }

            if (empty($_SESSION[Cart::SESSION])) {
                Cart::clearCartToChangeArm();
            }

            return $data;
        }
    }
