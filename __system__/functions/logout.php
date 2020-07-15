<?php
    use Model\User;

    if (User::checkLogin()) {
        User::logout();

        if (isset($_SESSION["url_sair"])) {
            $url = $_SESSION['url_sair'];
            unset($_SESSION['url_sair']);
            header("Location: $url");
        } else {
            header("Location: " . Project::baseUrlPhp());
        }
    } else {
        require_once '__system__/404.php';
    }
