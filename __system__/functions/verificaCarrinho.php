<?php
    require_once 'connection/conn.php';

    if(isXmlHttpRequest()) {
        $json = array();
        $json['status'] = 1;
        $json['prod_id'] = array();

        $sel = $conn->prepare("SELECT * FROM produto");
        $sel->execute();
        
        $result = $sel->fetchAll();
        foreach($result as $row) {
            if(isset($_SESSION[Cart::SESSION][$row['produto_id']])) {
                $json['prod_id'][$row['produto_id']] = $_SESSION[Cart::SESSION][$row['produto_id']];
            } else {
                $json['prod_id'][$row['produto_id']] = 0;
            }
        }

        echo json_encode($json);
    } else {
        header('Location: ../');
    }
?>