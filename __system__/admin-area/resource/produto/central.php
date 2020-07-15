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
    <title>e.conomize | Central de produtos</title>
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
            <h3 class="titleAdm">GERENCIADOR DE PRODUTOS</h3>
            <div id="conteudo">

            </div>
            <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar produto</button>
            <div class="divSearch">
                <form class="formSearch">
                    <label for="searchProd">Procure: </label>
                    <input type="text" class="inputSearch" id="searchProd"/>
                    <div class="divResetSearch"></div>
                </form>
            </div>
            <div class="divEcoTable">
                <table width="80%" class="tableView tableProdConfigAdm" align="center">
                    <thead>
                        <th class="thTitle" width="10%">IMAGEM</th>
                        <th class="thTitle sort" data-sort="produto_nome" width="30%">NOME <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="produto_tamanho" width="25%">VOLUME <span class="span_sort"></span></th>
                        <th class="thTitle sort" data-sort="marca_nome" width="20%">MARCA <span class="span_sort"></span></th>
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
                    <div class="divCadProduto">
                        <form class="formInserir formInserirProdutos" enctype="multipart/form-data">
                            <div class="divAddCadProduto">
                                <div style="margin-bottom:60px;">
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE PRODUTO</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                            <td><input type="text" class="selectConfigArm" name="nome_produto[]" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>MARCA</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="marca_produto[]">
                                                    <option value="*000*"> -- Selecione a marca: --</option>
                                                    <?php
                                                        $sel = $conn->prepare("SELECT * FROM marca_prod");
                                                        $sel->execute();
                                                        if($sel->rowCount() > 0):
                                                            $results = $sel->fetchAll();
                                                            foreach($results as $k => $v):?>
                                                                <option value="<?= $v['marca_id'] ?>"><?= $v['marca_nome']; ?></option>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>CATEGORIA</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="categoria_produto[]">
                                                    <option value="*000*"> -- Selecione a categoria: --</option>
                                                    <?php
                                                        $sel = $conn->prepare("SELECT * FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id");
                                                        $sel->execute();
                                                        if($sel->rowCount() > 0):
                                                            $results = $sel->fetchAll();
                                                            foreach($results as $k => $v):?>
                                                                <option value="<?= $v['categ_id'] ?>"><?= $v['depart_nome'] . " / " . $v['subcateg_nome'] . " / " . $v['categ_nome']; ?></option>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                                            <td>
                                                <img class="imgUpload" src=""/><br/>
                                                <label for="imagem_produto" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Carregar imagem</label>
                                                <input type="file" class="selectConfigArm" accept="image/*" id="imagem_produto" name="imagem_produto[]"/>
                                                <br/><br/>
                                                <small>* Caso não escolha nenhuma imagem, irá ser carregada uma padrão</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCRIÇÃO</b></td>
                                            <td><textarea name="descricao_produto[]" class="selectConfigArm" cols="30" rows="10"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>VOLUME</b></td>
                                            <td><input type="text" class="selectConfigArm" name="produto_tamanho[]" size="60"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadProduto">Adicionar mais produtos</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertProduto"><i class="fas fa-save"></i> Cadastrar</button>
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
                    <div class="divCadProduto">
                        <form class="formUpdateProdutos" enctype="multipart/form-data">
                            <div class="divAddCadProduto">
                                <div style="margin:25px 0;">
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR PRODUTO</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                            <td><input type="hidden" id="prod_idUpd" name="id_produto_upd"><input type="text" class="selectConfigArm" id="prod_nomeUpd" name="nome_produto_upd" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>MARCA</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="marca_produto_upd" id="prod_marcaUpd">
                                                    
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>CATEGORIA</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="categoria_produto_upd" id="prod_categUpd">
                                                    
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                                            <td>
                                                <img class="imgUpload" src=""/><br/>
                                                <label for="imagem_produtoEdit" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Alterar imagem</label>
                                                <input type="file" class="selectConfigArm" accept="image/*" id="imagem_produtoEdit" name="imagem_produto_upd"/>
                                                <br/><br/>
                                                <small>* Caso não escolha nenhuma imagem, será mantida a atual</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCRIÇÃO</b></td>
                                            <td><textarea name="descricao_produto_upd"  id="prod_descUpd" class="selectConfigArm" cols="30" rows="10"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>VOLUME</b></td>
                                            <td><input type="text" id="prod_tamUpd" class="selectConfigArm" name="produto_tamanho_upd" size="60"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnUpdateProduto"><i class="fas fa-save"></i> Editar</button>
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
    <script src="<?= base_url_adm(); ?>js/produto.js"></script>
    <script>
        var c = 1;
        $('.addCadProduto').click(function(e) {
            e.preventDefault();
            $('.divAddCadProduto').append(`
            <div class="newAdd">
                <table class="tableSectionConfigArm" width="80%" align="center">
                    <tr align="center">
                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE PRODUTO</h2></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                        <td><input type="text" class="selectConfigArm" name="nome_produto[]" size="60"></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>MARCA</b></td>
                        <td>
                            <select class="selectConfigArm" name="marca_produto[]">
                                <option value="*000*"> -- Selecione a marca: --</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM marca_prod");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['marca_id'] ?>"><?= $v['marca_nome']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>CATEGORIA</b></td>
                        <td>
                            <select class="selectConfigArm" name="categoria_produto[]">
                                <option value="*000*"> -- Selecione a categoria: --</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM categ AS c JOIN subcateg AS s ON c.subcateg_id=s.subcateg_id JOIN departamento AS d ON s.depart_id=d.depart_id");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['categ_id'] ?>"><?= $v['depart_nome'] . " / " . $v['subcateg_nome'] . " / " . $v['categ_nome']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>IMAGEM</b></td>
                        <td>
                            <img class="imgUpload" src=""/><br/>
                            <label for="imagem_produto` + c + `" class="selectConfigArm labelFile"><i class="fas fa-upload"></i> Carregar imagem</label>
                            <input type="file" class="selectConfigArm" accept="image/*" id="imagem_produto` + c + `" name="imagem_produto[]"/>
                            <br/><br/>
                            <small>* Caso não escolha nenhuma imagem, irá ser carregada uma padrão</small>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCRIÇÃO</b></td>
                        <td><textarea name="descricao_produto[]" class="selectConfigArm" cols="30" rows="10"></textarea></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>VOLUME</b></td>
                        <td><input type="text" class="selectConfigArm" name="produto_tamanho[]" size="60"></td>
                    </tr>
                </table>
                <div class="btnRemove">
                    <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                </div>
            </div>
            `);
            mask();
            uploadImg();
            c++;
        });

        // Remover o div anterior
        $('.divAddCadProduto').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });
        
        mask();
        insertProduto();
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