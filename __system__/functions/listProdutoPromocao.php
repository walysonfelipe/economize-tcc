<?php
    require_once 'connection/conn.php';

    if(isXmlHttpRequest()) {
        $json['status'] = 1;

        $sel = $conn->prepare("SELECT * FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE d.produto_desconto_porcent <> '' AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
        $sel->execute();
        
        if($sel->rowCount() > 0) {
            $result = $sel->fetchAll();
            foreach($result as $row) {
                if($row['produto_qtd'] > 0) {
                    $row['empty'] = false;
                } else {
                    $row['empty'] = true;
                }
                $row["produto_desconto"] = $row["produto_preco"]*($row["produto_desconto_porcent"]/100);
                $row["produto_desconto"] = number_format($row["produto_desconto"], 2, '.', '');
                $row["produto_desconto"] = $row["produto_preco"]-$row["produto_desconto"];
                
                $row["produto_preco"] = number_format($row["produto_preco"], 2, ',', '.');
                $row["produto_desconto"] = number_format($row["produto_desconto"], 2, ',', '.');
                if(isset($_SESSION[Cart::SESSION][$row['produto_id']])) {
                    $row["carrinho"] = $_SESSION[Cart::SESSION][$row['produto_id']];
                } else {
                    $row["carrinho"] = 0;
                }
                $json['produtos'][] = $row;
            }
        } else {
            $json['status'] = 0;
        }
        echo json_encode($json);
    } else {
        header('Location: ../');
    }
?>