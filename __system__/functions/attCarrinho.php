<?php
    use Model\Cart;

    if (Project::isXmlHttpRequest()) {
        if (isset($_POST['id_prod'])) {

            $json = Cart::addProduct($_POST['id_prod'], $_POST['qtd_prod']);

        } elseif (isset($_POST['produto_id'])) {

            $json = Cart::removeProduct($_POST['produto_id']);

        } elseif (isset($_POST['limpaCart'])) {
            Cart::clearCartToChangeArm();

            $json['type'] = "success";
            $json['answer'] = "Carrinho foi limpo";
        } elseif (isset($_POST['prod_id'])) {

            $json = Cart::addProduct($_POST['prod_id'], $_POST['qtd_prod']);

        } elseif (isset($_POST['attCampo_id'])) {

            $json['carrinho_qtd'] = Cart::getQuantity($_POST['attCampo_id']);
            
        } else {
            $json['type'] = "error";
            $json['answer'] = "Produto esgotado";
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
