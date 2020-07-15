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
    <title>e.conomize | Central de banners promocionais</title>
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
                <h3 class="titleAdm">GERENCIADOR DE BANNERS PROMOCIONAIS</h3>
                <div id="conteudo">

                </div>
                <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar banner</button>
                <div class="divSearch">
                    <form class="formSearch">
                        <label for="searchBanner">Procure: </label>
                        <input type="text" class="inputSearch" id="searchBanner"/>
                        <div class="divResetSearch"></div>
                    </form>
                </div>
                <div class="divEcoTable">
                    <table width="80%" class="tableView tableProdConfigAdm" align="center">
                        <thead>
                            <th class="thTitle" width="35%">BANNER</th>
                            <th class="thTitle sort" data-sort="banner_nome" width="30%">NOME <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="banner_status" width="20%">STATUS <span class="span_sort"></span></th>
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

        <div class="myModalAdd" id="myModalAdd">
            <div class="modalAddContent">
                <i class="closeModalAdd fas fa-times"></i>
                <div class="showAddModal">
                    <div class="divCadBanner">
                        <form class="formInserir formInserirBanner" enctype="multipart/form-data">
                            <div class="divAddCadBanner">
                                <div style="margin-bottom:60px;">
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE BANNER PROMOCIONAL</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                            <td><input type="text" placeholder=" (Opcional)" class="selectConfigArm" name="banner_nome[]" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>STATUS</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="banner_status[]">
                                                    <option value="*000*"> -- Selecione o status: --</option>
                                                    <option value="1">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                                            <td>
                                                <img class="imgUpload" src=""/><br/>
                                                <label for="banner_path" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Carregar imagem</label>
                                                <input type="file" class="selectConfigArm" accept="image/*" id="banner_path" name="banner_path[]"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadBanner">Adicionar mais banners</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertBanner"><i class="fas fa-save"></i> Cadastrar</button>
                                <div class="help-block"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                    <div class="divCadBanner">
                        <form class="formUpdateBanner" enctype="multipart/form-data">
                            <div class="divUpdCadBanner">
                                <div style="margin:25px 0;">
                                <table class="tableSectionConfigArm" width="80%" align="center">
                                    <tr align="center">
                                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR BANNER PROMOCIONAL</h2></td>
                                    </tr>
                                    <tr>
                                        <input type="hidden" id="banner_idUpd" name="banner_idUpd"/>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                        <td><input type="text" placeholder=" (Opcional)" class="selectConfigArm" id="banner_nomeUpd" name="banner_nomeUpd" size="60"></td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>STATUS</b></td>
                                        <td>
                                            <select class="selectConfigArm" id="banner_statusUpd" name="banner_statusUpd">
                                                <option id="status01" value="*000*"> -- Selecione o status: --</option>
                                                <option id="status1" value="1">Ativado</option>
                                                <option id="status0" value="0">Desativado</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                                        <td>
                                            <img class="imgUpload" src=""/><br/>
                                            <label for="banner_pathUpd" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Alterar imagem</label>
                                            <input type="file" class="selectConfigArm" accept="image/*" id="banner_pathUpd" name="banner_pathUpd"/>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnUpdateBanner"><i class="fas fa-save"></i> Editar</button>
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
    <script src="<?= base_url_adm(); ?>js/banner.js"></script>
    <script>
        var c = 0;
        $('.addCadBanner').click(function(e) {
            e.preventDefault();
            $('.divAddCadBanner').append(`
            <div class="newAdd">
                <table class="tableSectionConfigArm" width="80%" align="center">
                    <tr align="center">
                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE BANNER PROMOCIONAL</h2></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                        <td><input type="text" class="selectConfigArm" name="banner_nome[]" size="60"></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>STATUS</b></td>
                        <td>
                            <select class="selectConfigArm" name="banner_status[]">
                                <option value="*000*"> -- Selecione o status: --</option>
                                <option value="1">Ativado</option>
                                <option value="0">Desativado</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                        <td>
                            <img class="imgUpload" src=""/><br/>
                            <label for="banner_path` + c + `" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Carregar imagem</label>
                            <input type="file" class="selectConfigArm" accept="image/*" id="banner_path` + c + `" name="banner_path[]"/>
                        </td>
                    </tr>
                </table>
                <div class="btnRemove">
                    <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                </div>
            </div>
            `);

            uploadImg();
            c++;
        });

        // Remover o div anterior
        $('.divAddCadBanner').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });
        
        uploadImg();
        insertBanner();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IB"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>