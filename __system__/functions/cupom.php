<?php
    if (Project::isXmlHttpRequest()) {
        $sql = new Sql();

        $json = [];
        $json['new_total_price'] = null;
        $json['cupom'] = null;

        if (isset($_POST['addCupom'])) {
            $json['status'] = 1;
            $json['answer'] = null;

            if (!isset($_SESSION['cupom_compra'])) {
                $results = $sql->select("SELECT * FROM cupom WHERE cupom_codigo = :cod", [
                    ":cod" => $_POST['addCupom']
                ]);

                if (count($results) > 0) {
                    $v = $results[0];

                    $totCupomPorc = $_SESSION['totCompra'] * ($v['cupom_desconto_porcent'] / 100);
                    $totCupomPorc = Project::formatPriceToDolar($totCupomPorc);
                    $_SESSION['totCompraCupom'] = $_SESSION['totCompra'];
                    $_SESSION['totCompra'] -= $totCupomPorc;
                    $json['new_total_price'] = Project::formatPriceToReal($_SESSION['totCompra']);
                    $_SESSION['cupom_compra'] = $v;
                    $json['cupom'] = $_SESSION['cupom_compra'];
                } else {
                    $json['status'] = 0;
                    $json['answer'] = "Cupom expirado ou inexistente";
                }
            }
        } elseif (isset($_POST['remCupom'])) {
            if (isset($_SESSION['cupom_compra'])) {
                $totCupomPorc = $_SESSION['totCompraCupom'] * ($_SESSION['cupom_compra']['cupom_desconto_porcent'] / 100);
                $totCupomPorc = Project::formatPriceToDolar($totCupomPorc);
                $_SESSION['totCompra'] += $totCupomPorc;
                unset($_SESSION['cupom_compra']);
                unset($_SESSION['totCompraCupom']);
            }

            $json['new_total_price'] = Project::formatPriceToReal($_SESSION['totCompra']);
        } else {
            $json['empty'] = true;

            if (isset($_SESSION['cupom_compra'])) {
                $json['empty'] = false;
                $json['new_total_price'] = \Project::formatPriceToReal($_SESSION['totCompra']);
                $json['cupom'] = $_SESSION['cupom_compra'];
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
