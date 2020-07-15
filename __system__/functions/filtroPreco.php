<?php
    use Model\{Department, Product};

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json["empty"] = false;
        
        if (isset($_POST["produto_preco"])) {
            $json['first'] = false;

            if (!isset($_SESSION[Department::SESSION]['precoQuery'])) {
                $json['first'] = "preco";
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace("ORDER BY p.produto_nome ", "ORDER BY d.produto_preco {$_POST["produto_preco"]} ", $_SESSION[Department::SESSION]['rawQuery']);
            } else {
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['precoQuery'], "ORDER BY d.produto_preco {$_POST["produto_preco"]} ", $_SESSION[Department::SESSION]['rawQuery']);
            }
            $_SESSION[Department::SESSION]['precoQuery'] = "ORDER BY d.produto_preco {$_POST["produto_preco"]} ";
            
            $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            if (count($json['produtos']['data']) === 0) {
                $json['empty'] = true;
            }
        } else {
            $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['precoQuery'], "ORDER BY p.produto_nome ", $_SESSION[Department::SESSION]['rawQuery']);
            unset($_SESSION[Department::SESSION]['precoQuery']);
            
            $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            if (count($json['produtos']['data']) === 0) {
                $json['empty'] = true;
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
