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
    <title>e.conomize | Central de postagens</title>
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
                <h3 class="titleAdm">GERENCIADOR DE POSTAGENS</h3>
                <div id="conteudo">

                </div>
                <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar postagem</button>
                <div class="divSearch">
                    <form class="formSearch">
                        <label for="searchPost">Procure: </label>
                        <input type="text" class="inputSearch" id="searchPost"/>
                        <div class="divResetSearch"></div>
                    </form>
                </div>
                <div class="divEcoTable">
                    <table width="80%" class="tableView tableProdConfigAdm" align="center">
                        <thead>
                            <th class="thTitle" width="15%">IMAGEM</th>
                            <th class="thTitle sort" data-sort="post_title" width="25%">TÍTULO <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="post_text" width="27%">TEXTO <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="post_registro" width="18%">REGISTRO <span class="span_sort"></span></th>
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
                    <div class="divCadProduto divCadPost">
                        <form class="formInserir formInserirPost">
                            <div class="divAddCadPost">
                                <div style="margin-bottom:60px;">
                                    <div>
                                        <table class="tableSectionConfigArm" width="80%" align="center">
                                            <tr align="center">
                                                <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE POSTAGENS</h2></td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                                                <td>
                                                    <img class="imgUpload" src=""/><br/>
                                                    <label for="post_img" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Carregar imagem</label>
                                                    <input type="file" class="selectConfigArm" accept="image/*" id="post_img" name="post_img[]"/>
                                                    <br/><br/>
                                                    <small>* Caso não escolha nenhuma imagem, irá ser carregada uma padrão</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>TÍTULO</b></td>
                                                <td>
                                                    <textarea type="text" class="selectConfigArm" maxlength="255" name="post_title[]"></textarea>
                                                    <br/><br/>
                                                    <small>* Máx. 255 caracteres</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>TEXTO</b></td>
                                                <td>
                                                    <textarea type="text" class="selectConfigArm" name="post_text[]"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>ENVIO</b></td>
                                                <td>
                                                    <select name="post_envio[]" class="selectConfigArm">
                                                        <option value="0" selected>SOMENTE NO SISTEMA</option>
                                                        <option value="1">NO SISTEMA E PELO EMAIL</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadPost">Adicionar mais postagens</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertPost"><i class="fas fa-save"></i> Cadastrar</button>
                                <div class="help-block"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="myModalUpd" id="myModalUpd">
            <div class="modalUpdContent">
                <span class="closeModalUpd">&times;</span>
                <div class="showUpdModal">
                    <div class="divCadDuvida">
                        <form class="formUpdateDuvida">
                            <div class="divUpdCadDuvida">
                                <div style="margin:25px 0;">
                                <table class="tableSectionConfigArm" width="80%" align="center">
                                    <tr align="center">
                                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR DÚVIDA FREQUENTE</h2></td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>PERGUNTA</b></td>
                                        <td>
                                            <input type="hidden" id="duvida_idUpd" name="duvida_idUpd"/>
                                            <textarea type="text" class="selectConfigArm" name="duvida_perguntaUpd" id="duvida_perguntaUpd"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>RESPOSTA</b></td>
                                        <td>
                                            <textarea type="text" class="selectConfigArm" name="duvida_respostaUpd" id="duvida_respostaUpd"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnUpdateDuvida"><i class="fas fa-save"></i> Editar</button>
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
    <script src="<?= base_url_adm(); ?>js/postagem.js"></script>
    <script>
        var c = 1;
        $('.addCadPost').click(function(e) {
            e.preventDefault();
            $('.divAddCadPost').append(`
            <div class="newAdd">
                <table class="tableSectionConfigArm" width="80%" align="center">
                    <tr align="center">
                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE POSTAGENS</h2></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                        <td>
                            <img class="imgUpload" src=""/><br/>
                            <label for="post_img` + c + `" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Carregar imagem</label>
                            <input type="file" class="selectConfigArm" accept="image/*" id="post_img` + c + `" name="post_img[]"/>
                            <br/><br/>
                            <small>* Caso não escolha nenhuma imagem, irá ser carregada uma padrão</small>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>TÍTULO</b></td>
                        <td>
                            <textarea type="text" class="selectConfigArm" maxlength="255" name="post_title[]"></textarea>
                            <br/><br/>
                            <small>* Máx. 255 caracteres</small>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>TEXTO</b></td>
                        <td>
                            <textarea type="text" class="selectConfigArm" name="post_text[]"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>ENVIO</b></td>
                        <td>
                            <select name="post_envio[]" class="selectConfigArm">
                                <option value="0" selected>SOMENTE NO SISTEMA</option>
                                <option value="1">NO SISTEMA E PELO EMAIL</option>
                            </select>
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
        $('.divAddCadPost').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });
        
        insertPost();
        uploadImg();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IP"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>