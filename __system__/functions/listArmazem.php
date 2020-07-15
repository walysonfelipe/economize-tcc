<?php
    use Model\Storage;

    $sql = new Sql();

    if (Project::isXmlHttpRequest()) {
        $arm = Storage::listStoragesInModal();
        echo json_encode($arm);
    } else {
        require_once '__system__/404.php';
    }
