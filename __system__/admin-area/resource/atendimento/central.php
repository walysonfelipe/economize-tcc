<?php
    require_once '__system__/functions/connection/conn.php';
    if(!isset($_SESSION['inf_func']['funcionario_id'])) {
        header("Location: " . base_url_adm_php() . "login");
    }
    if(isset($_SESSION['data_sort'])) {
        unset($_SESSION['data_sort']);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Central de atendimento online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= base_url(); ?>img/e_icon.png"/>
    <link href="<?= base_url_adm(); ?>style/admin.css" rel="stylesheet"/>
    <link href="<?= base_url(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
</head>
<body>
    <div class="l-wrapper">
        <?php
            require '__system__/admin_area/functions/includes/menu.php';
        ?>
        <section class="l-main">
            <h3 class="titleAdm">GERENCIADOR DE ATENDIMENTOS</h3>
            <div id="conteudo">
                
            </div>
            <div class="divSearch">
                <form class="formSearch">
                    <label for="searchAtend">Procure: </label>
                    <input type="text" class="inputSearch" id="searchAtend"/>
                    <div class="divResetSearch"></div>
                </form>
            </div>
            <div class="divEcoTable">
                <table width="80%" class="tableView tableProdConfigAdm" align="center">
                    <thead>
                        <th class="thTitle sort" data-sort="a.nome_usu" width="25%">USUÁRIO <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="a.tp_problema" width="20%">TIPO MSG <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="ar.resp_id" width="20%">STATUS <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="a.dataenv_pro" width="20%">DATA <span class="span_sort"></span></th>
                        <th class="thTitle" width="15%">AÇÕES</th>
                    </thead>
                    <tbody class="tbodyProd">

                    </tbody>
                </table>
                <span class="paginacao"></span>

                <span class="registShow"></span>
            </div>
            <div class="dataProds">
                
            </div>
        </section>

        <div class="myModalAdd" id="myModalAdd">
            <div class="modalAddContent">
                <span class="closeModalAdd">&times;</span>
                <div class="showAddModal">
                    <?php
                        if(isset($_GET['id_atd'])):
                            if(is_numeric($_GET['id_atd'])):
                                $sel = $conn->prepare("SELECT * FROM atendimento WHERE id_atd={$_GET['id_atd']}");
                                $sel->execute();
                                if($sel->rowCount() > 0):
                                    $res = $sel->fetchAll();
                                    $data = substr($res[0]['dataenv_pro'],8,2) . "/" . substr($res[0]['dataenv_pro'],5,2) . "/" . substr($res[0]['dataenv_pro'],0,4) . " às " . substr($res[0]['dataenv_pro'],-8);?>
                                    <h1 class="titleAtend">ATENDIMENTO ONLINE</h1>
                                    <h3 class="dateAtend">Data da mensagem: <?= $data; ?></h3>

                                    <div class="tp_problAtend">
                                        <h3 class="tt_pAtend">Tipo problema:<h3>
                                        <h4 class="ttt_pAtend"><?= $res[0]['tp_problema']; ?></h4>
                                    </div>

                                    <div class="usuAtend">
                                        <b>Nome: </b><?= $res[0]['nome_usu']; ?><br/>
                                        <b>Email: </b><?= $res[0]['email_usu']; ?>
                                    </div>

                                    <div class="msgAtend">
                                        <h4 class="tt_msgAtend">Mensagem:</h4>
                                        <p class="p_msgAtend">
                                            <?= $res[0]['desc_problema']; ?>
                                        </p>
                                    </div>

                                    <div class="respAtend">
                                        <?php
                                            $sel2 = $conn->prepare("SELECT f.funcionario_nome, a.registro_resp, resp_atend FROM atend_resposta AS a JOIN funcionario AS f ON a.funcionario_id=f.funcionario_id WHERE id_atd={$_GET['id_atd']}");
                                            $sel2->execute();
                                            if($sel2->rowCount() == 0):?>
                                                <h4 class="tt_respAtend">Responder:</h4>
                                                <form class="respAtendOnline formInserir">
                                                    <input type="hidden" name="id_atd" value="<?= $res[0]['id_atd']; ?>"/>
                                                    <textarea name="resp_atd" class="textRespAtend" placeholder="Escreva sua resposta aqui..."></textarea><br/>
                                                    <button type="submit" class="btnRespAtend">ENVIAR</button>
                                                    <div class="help-block"></div>
                                                </form>
                                                <?php
                                            else:
                                                $res2 = $sel2->fetchAll();
                                                $data = substr($res2[0]['registro_resp'],8,2) . "/" . substr($res2[0]['registro_resp'],5,2) . "/" . substr($res2[0]['registro_resp'],0,4) . " às " . substr($res2[0]['registro_resp'],-8);
                                                ?>
                                                <h4 class="tt_respAtend">Esta mensagem já foi respondida pelo(a) <?= $res2[0]['funcionario_nome']; ?> em <?= $data; ?></h4>
                                                <p class="p_respAtend"><?= $res2[0]['resp_atend']; ?></p>
                                                <?php
                                            endif;
                                        ?>
                                    </div>
                                    <?php
                                endif;
                            endif;
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= base_url(); ?>js/util.js"></script>
    <script src="<?= base_url_adm(); ?>js/admin.js"></script>
    <script src="<?= base_url_adm(); ?>js/atendimento.js"></script>
    <?php
        if(isset($_GET['id_atd'])):
            if(is_numeric($_GET['id_atd'])):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>