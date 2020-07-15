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
    <title>e.conomize | Central de departamentos</title>
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
            <div id="conteudo">
                <h3 class="titleAdm">GERENCIADOR DE DEPARTAMENTOS</h3>
                <div id="conteudo">

                </div>
                <div class="divSearch">
                    <form class="formSearch">
                        <label for="searchDepart">Procure: </label>
                        <input type="text" class="inputSearch" id="searchDepart"/>
                        <div class="divResetSearch"></div>
                    </form>
                </div>
                <div class="divEcoTable">
                    <table width="80%" class="tableView tableProdConfigAdm" align="center">
                        <thead>
                            <th class="thTitle sort" data-sort="depart_nome" width="40%">DEPARTAMENTO <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="depart_desc" width="45%">DESCRIÇÃO <span class="span_sort"></span></th>
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
            </div>
        </section>

        <div class="myModalUpd" id="myModalUpd">
            <div class="modalUpdContent">
                <span class="closeModalUpd">&times;</span>
                <div class="showUpdModal">
                    <div class="divCadDepart">
                        <form class="formUpdateDepart">
                            <div class="divUpdCadDepart">
                                <div style="margin:25px 0;">
                                <table class="tableSectionConfigArm" width="80%" align="center">
                                    <tr align="center">
                                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR DEPARTAMENTO</h2></td>
                                    </tr>
                                    <tr>
                                        <input type="hidden" id="depart_idUpd" name="depart_idUpd">
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                        <td><input type="text" class="selectConfigArm" id="depart_nomeUpd" name="depart_nomeUpd" size="60"></td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCRIÇÃO</b></td>
                                        <td><input type="text" class="selectConfigArm porcent" id="depart_descUpd" name="depart_descUpd" size="60"></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnUpdateDepart"><i class="fas fa-save"></i> Editar</button>
                                <div class="help-block"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= base_url(); ?>js/util.js"></script>
    <script src="<?= base_url_adm(); ?>js/admin.js"></script>
    <script src="<?= base_url_adm(); ?>js/departamento.js"></script>
</body>
</html>