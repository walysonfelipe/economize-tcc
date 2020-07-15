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
    <title>e.conomize | Central de categorias</title>
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
            <h3 class="titleAdm">GERENCIADOR DE CATEGORIAS</h3>
            <div id="conteudo">

            </div>
            <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar categoria</button>
            <div class="divSearch">
                <form class="formSearch">
                    <label for="searchCateg">Procure: </label>
                    <input type="text" class="inputSearch" id="searchCateg"/>
                    <div class="divResetSearch"></div>
                </form>
            </div>
            <div class="divEcoTable">
                <table width="80%" class="tableView tableProdConfigAdm" align="center">
                    <thead>
                        <th class="thTitle sort" data-sort="c.categ_nome" width="30%">NOME <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="s.subcateg_nome" width="35%">SUBCAT / DEPART <span class="span_sort"></span></th>
                        <th class="thTitle" width="20%">QTD PROD.</th>
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
                <i class="closeModalAdd fas fa-times"></i>
                <div class="showAddModal">
                    <div class="divCadProduto divCadCateg">
                        <form class="formInserir formInserirCateg">
                            <div class="divAddCadCateg">
                                <div style="margin-bottom:60px;">
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRAR CATEGORIA</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>CATEGORIA</b></td>
                                            <td><input type="text" class="selectConfigArm" name="categ_nome[]" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>SUBCATEGORIA</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="subcateg_id[]">
                                                    <option value="*000*"> -- Selecione o subcategoria: --</option>
                                                    <?php
                                                        $sel = $conn->prepare("SELECT * FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id");
                                                        $sel->execute();
                                                        if($sel->rowCount() > 0):
                                                            $results = $sel->fetchAll();
                                                            foreach($results as $k => $v):?>
                                                                <option value="<?= $v['subcateg_id'] ?>"><?= $v['depart_nome'] . " / " . $v['subcateg_nome']; ?></option>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadCateg">Adicionar mais categorias</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertCateg"><i class="fas fa-save"></i> Cadastrar</button>
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
    <script src="<?= base_url_adm(); ?>js/categoria.js"></script>
    <script>
        $('.addCadCateg').click(function(e) {
            e.preventDefault();
            $('.divAddCadCateg').append(`
                <div class="newAdd">
                    <table class="tableSectionConfigArm" width="80%" align="center">
                        <tr align="center">
                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRAR CATEGORIA</h2></td>
                        </tr>
                        <tr>
                            <td align="center" style="text-align:center;color:#9C45EB;"><b>CATEGORIA</b></td>
                            <td><input type="text" class="selectConfigArm" name="categ_nome[]" size="60"></td>
                        </tr>
                        <tr>
                            <td align="center" style="text-align:center;color:#9C45EB;"><b>SUBCATEGORIA</b></td>
                            <td>
                                <select class="selectConfigArm" name="subcateg_id[]">
                                    <option value="*000*"> -- Selecione o subcategoria: --</option>
                                    <?php
                                        $sel = $conn->prepare("SELECT * FROM subcateg AS s JOIN departamento AS d ON s.depart_id=d.depart_id");
                                        $sel->execute();
                                        if($sel->rowCount() > 0):
                                            $results = $sel->fetchAll();
                                            foreach($results as $k => $v):?>
                                                <option value="<?= $v['subcateg_id'] ?>"><?= $v['depart_nome'] . " / " . $v['subcateg_nome']; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <div class="btnRemove">
                        <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            `);
        });

        // Remover o div anterior
        $('.divAddCadCateg').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });
        
        insertCateg();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IC"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>