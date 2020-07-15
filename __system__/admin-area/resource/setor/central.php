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
    <title>e.conomize | Central de setores</title>
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
                <h3 class="titleAdm">GERENCIADOR DE SETORES</h3>
                <div id="conteudo">

                </div>
                <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar setor</button>
                <div class="divSearch">
                    <form class="formSearch">
                        <label for="searchSetor">Procure: </label>
                        <input type="text" class="inputSearch" id="searchSetor"/>
                        <div class="divResetSearch"></div>
                    </form>
                </div>
                <div class="divEcoTable">
                    <table width="80%" class="tableView tableProdConfigAdm" align="center">
                        <thead>
                            <th class="thTitle sort" data-sort="setor_nome" width="55%">NOME <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="setor_permicao" width="25%">PERMIÇÕES <span class="span_sort"></span></th>
                            <th class="thTitle" width="20%">AÇÕES</th>
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
                    <div class="divCadProduto divCadSetor">
                        <form class="formInserir formInserirSetor">
                            <div class="divSetor">
                                <div style="margin-bottom:60px;">
                                    <div>
                                        <table class="tableSectionConfigArm" width="80%" align="center">
                                            <tr align="center">
                                                <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE SETORES</h2></td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                                <td>
                                                    <input type="text" class="selectConfigArm" name="setor_nome[]"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>PERMIÇÕES DE DADOS</b></td>
                                                <td>
                                                    <input type="checkbox" value="a" class="" id="per_adc" name="setor_permicao"/> 
                                                    <label for="per_adc">ADICIONAR</label> <input type="checkbox" value="e" class="" id="per_edit" name="setor_permicao"/> <label for="per_edit">EDITAR</label> <input type="checkbox" value="d" class="" id="per_del" name="setor_permicao"/> <label for="per_del">DELETAR</label>
                                                    <br/>
                                                    <input type="checkbox" value="r" class="" id="per_res" name="setor_permicao"/> <label for="per_res">RESPONDER MENSAGENS</label> <input type="checkbox" value="g" class="" id="per_ger" name="setor_permicao"/> <label for="per_ger">GERAR RELATÓRIOS</label>
                                                    <br/><br/>
                                                    <small>* A permição de 'ler os dados' é colocada indiretamente.</small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadSetor">Adicionar mais setores</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertSetor"><i class="fas fa-save"></i> Cadastrar</button>
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
                    <div class="divCadFornecedor">
                        <form class="formUpdateFornecedor">
                            <div class="divUpdCadFornecedor">
                                <div style="margin:25px 0;">
                                <table class="tableSectionConfigArm" width="80%" align="center">
                                    <tr align="center">
                                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">EDITAR FORNECEDOR</h2></td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                        <td>
                                            <input type="hidden" name="fornecedor_idUpd" id="fornecedor_idUpd"/>
                                            <input type="text" class="selectConfigArm" name="fornecedor_nomeUpd" id="fornecedor_nomeUpd"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME RESPONSÁVEL</b></td>
                                        <td>
                                            <input type="text" class="selectConfigArm" name="fornecedor_responsavel_nomeUpd" id="fornecedor_responsavel_nomeUpd"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="text-align:center;color:#9C45EB;"><b>CNPJ</b></td>
                                        <td>
                                            <input type="text" class="selectConfigArm cnpj" name="fornecedor_cnpjUpd" id="fornecedor_cnpjUpd"/>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnUpdateFornecedor"><i class="fas fa-save"></i> Editar</button>
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
    <script src="<?= base_url_adm(); ?>js/setor.js"></script>
    <script>
        $('.addCadSetor').click(function(e) {
            e.preventDefault();
            $('.divSetor').append(`
            <div class="newAdd">
                <table class="tableSectionConfigArm" width="80%" align="center">
                    <tr align="center">
                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE SETORES</h2></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                        <td>
                            <input type="text" class="selectConfigArm" name="setor_nome[]"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>PERMIÇÕES DE DADOS</b></td>
                        <td>
                            <input type="checkbox" value="a" class="" id="per_adc" name="setor_permicao"/> 
                            <label for="per_adc">ADICIONAR</label> <input type="checkbox" value="e" class="" id="per_edit" name="setor_permicao"/> <label for="per_edit">EDITAR</label> <input type="checkbox" value="d" class="" id="per_del" name="setor_permicao"/> <label for="per_del">DELETAR</label>
                            <br/>
                            <input type="checkbox" value="r" class="" id="per_res" name="setor_permicao"/> <label for="per_res">RESPONDER MENSAGENS</label> <input type="checkbox" value="g" class="" id="per_ger" name="setor_permicao"/> <label for="per_ger">GERAR RELATÓRIOS</label>
                            <br/><br/>
                            <small>* A permição de 'ler os dados' é colocada indiretamente.</small>
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
        $('.divCadSetor').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });

        // insertSetor();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IS"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>