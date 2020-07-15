<?php
    use Model\User;
    $sql = new Sql();

    if (Project::isXmlHttpRequest()) {
        $json = [];
        
        if (isset($_POST["senha_atual"])) {

            $json = User::changePassword($_POST["senha_atual"], $_POST["senha_nova"], $_POST["senha_nova_confirme"]);

        } elseif (isset($_POST['show_tel'])) {
            
            $json = User::getTelefones();

        } elseif (isset($_POST['deletaTel'])) {
            
            $json = User::deleteTelefone($_POST['deletaTel']);

        } elseif (isset($_POST['add_tel'])) {
            
            $json = User::checkToAddTelefone();

        } elseif (isset($_POST['telefone'])) {
            
            $json = User::updateTelefone($_POST);

        } elseif (isset($_POST['tel_num'])) {

            $json = User::insertTelefone($_POST);
            
        } elseif (isset($_POST['mudarEnd'])) {
            $json['end'][0] = $_SESSION[User::SESSION]['usu_cep'];
            $json['end'][1] = $_SESSION[User::SESSION]['usu_end'];
            $json['end'][2] = $_SESSION[User::SESSION]['usu_num'];
            $json['end'][3] = $_SESSION[User::SESSION]['usu_complemento'];
            $json['end'][4] = $_SESSION[User::SESSION]['usu_bairro'];
            $json['end'][5] = $_SESSION[User::SESSION]['usu_cidade'];
            $json['end'][6] = $_SESSION[User::SESSION]['usu_uf'];
        } elseif (isset($_POST['end_cep'])) {
            
            $json = User::changeEndereco($_POST);

        } elseif (isset($_POST['mailmkt'])) {

            $json = User::changeMailMkt($_POST['mailmkt']);
            
        }
        
        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
