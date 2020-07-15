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
    <title>e.conomize | Central de entregas</title>
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
            <h3 class="titleAdm">GERENCIADOR DE ENTREGAS</h3>
            <div id="conteudo">

            </div>
            <div class="divSearch">
                <form class="formSearch">
                    <label for="searchEnt">Procure: </label>
                    <input type="text" class="inputSearch" id="searchEnt"/>
                    <div class="divResetSearch"></div>
                </form>
            </div>
            <div class="divEcoTable">
                <table width="80%" class="tableView tableProdConfigAdm" align="center">
                    <thead>
                        <th class="thTitle sort" data-sort="c.compra_hash" width="20%">HASH <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="c.status_id" width="15%">STATUS <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="e.entrega_horario" width="15%">HORÁRIO <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="e.entrega_cidade" width="15%">CIDADE <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="a.armazem_nome" width="20%">ARMAZÉM <span class="span_sort"></span></th>
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
        <footer class="l-footer">
        </footer>
    </div>

    <div class="myModalView" id="myModalView">
        <div class="modalViewContent">
            <span class="closeModalView">&times;</span>
            <div class="showViewModal">

            </div>
        </div>
    </div>

    <div class="myModalUpd" id="myModalUpd">
        <div class="modalUpdContent">
            <span class="closeModalUpd">&times;</span>
            <div class="showUpdModal">
                <div class="divCadProduto">
                    <form class="formUpdateEntrega" enctype="multipart/form-data">
                        <div class="divUpdCadEntrega">
                            <div style="margin:25px 0;">
                                <table class="tableSectionConfigArm" width="80%" align="center">
                                    <tr align="center">
                                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR ENTREGA</h2></td>
                                    </tr>
                                    <tr class="excluiFunc"></tr>
                                    <tr>
                                        <input type="hidden" class="entrega_idUpd" name="entrega_idUpd"/>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>ADICIONAR FUNCIONÁRIO</b></td>
                                        <td><select class="selectConfigArm" id="funcionario_entrega" name="funcionario_entrega"></select></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="divSubmit" align="center">
                            <button type="submit" id="btnUpdateEntrega"><i class="fas fa-save"></i> Adicionar funcionário</button>
                            <div class="help-block"></div>
                        </div>
                    </form>
                    <form class="formChangeStatus">
                        <div style="margin:25px 0;">
                            <table class="tableSectionConfigArm" width="80%" align="center">
                                <tr align="center">
                                    <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR STATUS</h2></td>
                                </tr>
                                <tr>
                                    <input type="hidden" class="entrega_idUpd" name="entrega_idUpd"/>
                                    <td align="center" style="text-align:center;color:#9C45EB;"><b>EDITAR STATUS</b></td>
                                    <td>
                                        <select class="selectConfigArm" id="status_idUpd" name="status_idUpd">
                                            <?php
                                                $sel = $conn->prepare("SELECT * FROM status_compra");
                                                $sel->execute();
                                                while($row = $sel->fetch( PDO::FETCH_ASSOC )):?>
                                                    <option value="<?= $row['status_id']; ?>"><?= $row['status_nome'] ?></option>
                                                    <?php
                                                endwhile;
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnUpdateStatusEntrega"><i class="fas fa-save"></i> Editar status</button>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= base_url(); ?>js/mask.js"></script>
    <script src="<?= base_url(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= base_url(); ?>js/util.js"></script>
    <script src="<?= base_url_adm(); ?>js/admin.js"></script>
    <script src="<?= base_url_adm(); ?>js/entrega.js"></script>
</body>
</html>