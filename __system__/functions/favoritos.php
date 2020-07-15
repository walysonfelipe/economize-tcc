<?php
    use Model\Product;
    $sql = new Sql();

    if (Project::isXmlHttpRequest()) {
        $json = [];

        if (isset($_POST['add_prod_id'])) {
            $json = Product::addFavorite($_POST['add_prod_id']);
        } elseif (isset($_POST['rem_prod_id'])) {
            $json = Product::removeFavorite($_POST['rem_prod_id']);
        } else {
            $json['status'] = 1;
            $json['products'] = Product::listAll(true);
            $json['favorites'] = Product::getFavoriteProducts();
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
