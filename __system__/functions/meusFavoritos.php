<?php
    use Model\Product;

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json['logado'] = true;
        $json['produtos'] = Product::getFavoriteProductsByArm();
        
        if ($json['produtos'] === false) $json['logado'] = false;

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
