<?php
    use Model\{Cart, Product};

    if (Project::isXmlHttpRequest()) {
        $json = [];

        if (isset($_POST['q'])) {
            $json["empty"] = true;
            $json['prods'] = [];

            $products = Product::getProductsBySearch($_POST['q']);

            if (count($products) > 0) {
                $json['empty'] = false;

                foreach ($products as $v) {
                    if ($v['produto_qtd'] > 0) {
                        $v['empty'] = false;
                    } else {
                        $v['empty'] = true;
                    }
                    
                    if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                        $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                    } else {
                        $v["carrinho"] = 0;
                    }
                    
                    array_push($json['prods'], $v);
                }
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
