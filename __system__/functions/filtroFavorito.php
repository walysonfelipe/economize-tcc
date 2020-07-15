<?php
    use Model\{Department, Product, User};

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json["empty"] = false;
        $json['logado'] = true;

        if (User::checkLogin()) {
            $json['first'] = false;

            if (!isset($_POST['prod_fav'])) {
                $json['first'] = "favorito";
                $_SESSION[Department::SESSION]['favQuery'][] = "produto p JOIN produtos_favorito pf ON p.produto_id = pf.produto_id";
                $_SESSION[Department::SESSION]['favQuery'][] = "WHERE pf.usu_id = {$_SESSION[User::SESSION]['usu_id']} AND";

                $_SESSION[Department::SESSION]['rawQuery'] = str_replace("produto p", $_SESSION[Department::SESSION]['favQuery'][0], $_SESSION[Department::SESSION]['rawQuery']);
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace("WHERE", $_SESSION[Department::SESSION]['favQuery'][1], $_SESSION[Department::SESSION]['rawQuery']);
                
                $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
                if (count($json['produtos']['data']) === 0) {
                    $json['empty'] = true;
                }

                $json['query'] = $_SESSION[Department::SESSION]['rawQuery'];
            } else {
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['favQuery'][0], "produto p", $_SESSION[Department::SESSION]['rawQuery']);
                $_SESSION[Department::SESSION]['rawQuery'] = str_replace($_SESSION[Department::SESSION]['favQuery'][1], "WHERE", $_SESSION[Department::SESSION]['rawQuery']);
                unset($_SESSION[Department::SESSION]['favQuery']);

                $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery']);
                if (count($json['produtos']['data']) === 0) {
                    $json['empty'] = true;
                }

                $json['query'] = $_SESSION[Department::SESSION]['rawQuery'];
            }
        } else {
            $json['logado'] = false;
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
