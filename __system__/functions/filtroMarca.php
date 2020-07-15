<?php
    use Model\{Department, Product};

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json["empty"] = false;
        
        if (isset($_POST["produto_marca"])) {
            $json['first'] = false;

            if (!isset($_SESSION[Department::SESSION]['marcaQuery'])) {
                $json['first'] = "marca";
                $_SESSION[Department::SESSION]['marcaQuery'] = "AND m.marca_nome='{$_POST["produto_marca"]}' ";

                if (isset($_SESSION[Department::SESSION]['precoQuery'])) {
                    $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['precoQuery'], $_SESSION[Department::SESSION]['marcaQuery'] . $_SESSION[Department::SESSION]['precoQuery'], $_SESSION[Department::SESSION]['rawQuery']);
                } else {
                    $_SESSION[Department::SESSION]['rawQuery'] = str_replace("ORDER BY", $_SESSION[Department::SESSION]['marcaQuery'] . "ORDER BY", $_SESSION[Department::SESSION]['rawQuery']);
                }
            } else {
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['marcaQuery'], "AND m.marca_nome='{$_POST["produto_marca"]}' ", $_SESSION[Department::SESSION]['rawQuery']);
                $_SESSION[Department::SESSION]['marcaQuery'] = "AND m.marca_nome='{$_POST["produto_marca"]}' ";
            }
            
            $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            if (count($json['produtos']['data']) === 0) {
                $json['empty'] = true;
            }

            $json['query'] = $_SESSION[Department::SESSION]['rawQuery'];
        } else {
            $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['marcaQuery'], "", $_SESSION[Department::SESSION]['rawQuery']);
            unset($_SESSION[Department::SESSION]['marcaQuery']);
            
            $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
            if (count($json['produtos']['data']) === 0) {
                $json['empty'] = true;
            }

            $json['query'] = $_SESSION[Department::SESSION]['rawQuery'];
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
