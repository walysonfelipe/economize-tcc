<?php
    use Model\Storage;

    if (Project::isXmlHttpRequest()) {
        if (isset($_POST['arm_id'])) {
            $json = Storage::changeStorage($_POST['arm_id']);
        }

        echo json_encode($json);
    } else {
        require_once '__system__/404.php';
    }
