<?php
    use Model\User;

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json['logado'] = true;

        if (User::checkLogin() === false) {
            $json['logado'] = false;
        } else {
            $json['usuario'] = $_SESSION[User::SESSION];
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }