<?php
    use Model\Product;
    $sql = new \Sql();

    if (Project::isXmlHttpRequest()) {
        if (isset($_POST['buscaProd_id'])) {
            $json['produto'] = Product::getProductByIdAndArm($_POST['buscaProd_id'], false);

            if ($json['produto'] !== "") {
                $json['produto']['id_cript'] = md5($json['produto']['produto_id']);
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
