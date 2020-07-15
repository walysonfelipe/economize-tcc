<?php
    use Model\{Department, Product};

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json["empty"] = false;
        
        if (isset($_POST["produto_tamanho"])) {
            $json['first'] = false;

            if (!isset($_SESSION[Department::SESSION]['tamQuery'])) {
                $json['first'] = "tamanho";
                $_SESSION[Department::SESSION]['tamQuery'] = "AND p.produto_tamanho='{$_POST["produto_tamanho"]}' ";

                if (isset($_SESSION[Department::SESSION]['precoQuery'])) {
                    $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['precoQuery'], $_SESSION[Department::SESSION]['tamQuery'] . $_SESSION[Department::SESSION]['precoQuery'], $_SESSION[Department::SESSION]['rawQuery']);
                } else {
                    $_SESSION[Department::SESSION]['rawQuery'] = str_replace("ORDER BY", $_SESSION[Department::SESSION]['tamQuery'] . "ORDER BY", $_SESSION[Department::SESSION]['rawQuery']);
                }
            } else {
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['tamQuery'],"AND p.produto_tamanho='{$_POST["produto_tamanho"]}' ", $_SESSION[Department::SESSION]['rawQuery']);
                $_SESSION[Department::SESSION]['tamQuery'] = "AND p.produto_tamanho='{$_POST["produto_tamanho"]}' ";
            }
            
            $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            if (count($json['produtos']['data']) === 0) {
                $json['empty'] = true;
            }

            $json['query'] = $_SESSION[Department::SESSION]['rawQuery'];
        } else {
            $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['tamQuery'],"", $_SESSION[Department::SESSION]['rawQuery']);
            unset($_SESSION[Department::SESSION]['tamQuery']);
            
            $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            if (count($json['produtos']['data']) === 0) {
                $json['empty'] = true;
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
