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
    <title>e.conomize | Central de usuários</title>
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
            <h3 class="titleAdm">GERENCIADOR DE USUÁRIOS</h3>
            <div id="conteudo">

            </div>
            <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar usuário</button>
            <div class="divSearch">
                <form class="formSearch">
                    <label for="searchUsuario">Procure: </label>
                    <input type="text" class="inputSearch" id="searchUsuario"/>
                    <div class="divResetSearch"></div>
                </form>
            </div>
            <div class="divEcoTable">
                <table width="80%" class="tableView tableProdConfigAdm" align="center">
                    <thead>
                        <th class="thTitle sort" data-sort="u.usu_first_name" width="20%">NOME <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="u.usu_sexo" width="15%">SEXO <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="u.usu_cidade" width="15%">CIDADE <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="t.tpu_usu_nome" width="15%">TIPO <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="u.usu_registro" width="20%">REGISTRO <span class="span_sort"></span></th>
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
                    <div class="divCadProduto divCadMarca">
                        <form class="formInserir formInserirMarca">
                            <div class="divAddCadMarca">
                                <div style="margin-bottom:60px;">
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRAR MARCA</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                            <td><input type="text" class="selectConfigArm" name="marca_nome[]" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCONTO (%)</b></td>
                                            <td><input type="text" placeholder="(Opcional)" class="selectConfigArm porcent" name="marca_promocao[]" size="60"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadMarca">Adicionar mais marcas</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertMarca"><i class="fas fa-save"></i> Cadastrar</button>
                                <div class="help-block"></div>
                            </div>
                        </form>
                    </div>
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
    <script src="<?= base_url_adm(); ?>js/usuario.js"></script>
    <script>
        $('.addCadMarca').click(function(e) {
            e.preventDefault();
            $('.divAddCadMarca').append(`
                <div class="newAdd">
                    <table class="tableSectionConfigArm" width="80%" align="center">
                        <tr align="center">
                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRAR MARCA</h2></td>
                        </tr>
                        <tr>
                            <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                            <td><input type="text" class="selectConfigArm" name="marca_nome[]" size="60"></td>
                        </tr>
                        
                        <tr>
                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCONTO (%)</b></td>
                            <td><input type="text" placeholder="(Opcional)" class="selectConfigArm porcent" name="marca_promocao[]" size="60"></td>
                        </tr>
                    </table>
                    <div class="btnRemove">
                        <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            `);
            mask();
        });

        // Remover o div anterior
        $('.divAddCadMarca').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });
        
        insertMarca();
        mask();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IM"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>