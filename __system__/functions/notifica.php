<?php
    $sql = new Sql();

    if (Project::isXmlHttpRequest()) {
        $json['status'] = 1;

        if (isset($_POST['searchPost'])) {
            $search = $_POST['searchPost'];
            $json['error'] = null;

            $results = $sql->select("SELECT * FROM postagem WHERE post_title LIKE :search OR post_text LIKE :search OR post_registro LIKE :search ORDER BY post_registro DESC", [
                ":search" => "%{$_POST['searchPost']}%"
            ]);
            
            if (count($results) > 0) {
                foreach ($results as $v) {
                    $v['post_title'] = (strlen($v['post_title']) > 65) ? substr($v['post_title'],0,65) . "..." : $v['post_title'];
                    $v['post_registro'] = Project::formatRegister($v['post_registro']);
                    $json['postagens'][] = $v;
                }
            } else {
                $json['status'] = 0;
                $json['error'] = "Não houve resposta para o que foi pesquisado!";
            }
        } elseif (isset($_POST['showPost'])) {
            $post_id = $_POST['showPost'];
            $json['error'] = null;

            $results = $sql->select("SELECT * FROM postagem WHERE post_id = :id", [
                ":id" => $post_id
            ]);
            
            if (count($results) > 0) {
                foreach ($results as $v) {
                    $v['post_registro'] = Project::formatRegister($v['post_registro']);
                    $json['postagem'] = $v;
                }
            } else {
                $json['status'] = 0;
                $json['error'] = "
                    Ocorreu um erro!<br/>
                    Notificação não encontrada.<br/>
                    <a href='" . Project::baseUrlPhp() . "suporte/atendimento'>Contate-nos, por favor.</a>
                ";
            }
        } else {
            $json['error'] = null;

            $results = $sql->select("SELECT * FROM postagem ORDER BY post_registro DESC");
            
            if (count($results) > 0) {
                foreach ($results as $v) {
                    $v['post_title'] = (strlen($v['post_title']) > 65) ? substr($v['post_title'],0,65) . "..." : $v['post_title'];
                    $v['post_registro'] = Project::formatRegister($v['post_registro']);
                    $json['postagens'][] = $v;
                }
            } else {
                $json['status'] = 0;
                $json['error'] = "Não há notificações no momento!";
            }
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
