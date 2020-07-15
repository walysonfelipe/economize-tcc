<?php
    if (Project::isXmlHttpRequest()) {
        $sql = new Sql();

        $json = [];
        $json['status'] = 1;

        if (isset($_POST['duvida_id'])) {
            $results = $sql->select("SELECT duvida_resposta FROM duvida_frequente WHERE duvida_id = :duv_id", [
                ":duv_id" => $_POST['duvida_id']
            ]);

            if (count($results) > 0) {
                $json['duvida_resposta'] = $results[0]['duvida_resposta'];
            } else {
                $json['status'] = 0;
            }
        } elseif (isset($_POST['searchDuvida'])) {
            $json['empty'] = true;

            $results = $sql->select("SELECT duvida_id, duvida_pergunta FROM duvida_frequente WHERE duvida_pergunta LIKE :search OR duvida_resposta LIKE :search ORDER BY duvida_pergunta", [
                ":search" => "%{$_POST['searchDuvida']}%"
            ]);

            if (count($results) > 0) {
                $json['empty'] = false;

                foreach ($results as $row) {
                    $json['duvidas'][] = $row;
                }
            }
        } else {
            $json['empty'] = true;

            $results = $sql->select("SELECT duvida_id, duvida_pergunta FROM duvida_frequente ORDER BY duvida_pergunta");

            if (count($results) > 0) {
                $json['empty'] = false;

                foreach ($results as $row) {
                    $json['duvidas'][] = $row;
                }
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
