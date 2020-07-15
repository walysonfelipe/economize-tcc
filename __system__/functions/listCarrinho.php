<?php
    use Model\Cart;

    if (Project::isXmlHttpRequest()) {
        $json = Cart::getCart();
        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
