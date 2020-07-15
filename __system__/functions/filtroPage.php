<?php
    use Model\{Department, Product};

    if (Project::isXmlHttpRequest()) {
        $json = [];
        
        $json['produtos'] = Product::searchDepartment($_SESSION[Department::SESSION]['rawQuery'], $_POST['page']);

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
