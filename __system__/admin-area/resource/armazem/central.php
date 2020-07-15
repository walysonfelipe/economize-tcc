<?php
    require_once '__system__/functions/connection/conn.php';
    if(!isset($_SESSION['inf_func']['funcionario_id'])) {
        header("Location: " . base_url_adm_php() . "login");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Central de armazéns</title>
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
            <h3 class="titleAdm">GERENCIADOR DE ARMAZÉNS</h3>
            <div id="conteudo">

            </div>
            
            <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Registrar armazém</button>
            <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar produto à armazém</button>
        </section>

        <div class="myModalAdd" id="myModalAdd">
            <div class="modalAddContent">
                <i class="closeModalAdd fas fa-times"></i>
                <div class="showAddModal">
                    <div class="divCadProduto divCadProdutoArmazem">
                        <form class="formInserir formInserirProdutoArmazem">
                            <div class="divProdutoArmazem">
                                <div>
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE PRODUTOS POR ARMAZÉM</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>ARMAZÉM</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="armazem[]">
                                                    <option value="*000*"> -- Selecione o armazém: --</option>
                                                    <?php
                                                        $sel = $conn->prepare("SELECT * FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id");
                                                        $sel->execute();
                                                        if($sel->rowCount() > 0):
                                                            $results = $sel->fetchAll();
                                                            foreach($results as $k => $v):?>
                                                                <option value="<?= $v['armazem_id'] ?>"><?= $v['armazem_nome'] . "/" . $v['cid_nome'] . " - " . $v['est_uf']; ?></option>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>PRODUTO</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="produto[]">
                                                    <option value="*000*"> -- Selecione o produto: --</option>
                                                    <?php
                                                        $sel = $conn->prepare("SELECT * FROM produto");
                                                        $sel->execute();
                                                        if($sel->rowCount() > 0):
                                                            $results = $sel->fetchAll();
                                                            foreach($results as $k => $v):?>
                                                                <option value="<?= $v['produto_id'] ?>"><?= $v['produto_nome'] . "/" . $v['produto_tamanho']; ?></option>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>QUANTIDADE</b></td>
                                            <td><input type="text" class="selectConfigArm qtd_prod" name="produto_qtd[]"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>PREÇO UNITÁRIO</b></td>
                                            <td><input type="text" class="selectConfigArm money" name="produto_preco[]" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCONTO (%)</b></td>
                                            <td><input type="text" class="selectConfigArm porcent" name="produto_desconto_porcent[]" placeholder=" (Opcional)" size="60"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadProdutoArmazem">Adicionar mais produtos</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertProdutoArmazem"><i class="fas fa-save"></i> Cadastrar</button>
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
    <script src="<?= base_url_adm(); ?>js/armazem.js"></script>
    <script>
        $('.addCadProdutoArmazem').click(function(e) {
            e.preventDefault();
            $('.divProdutoArmazem').append(`
            <div class="newAdd">
                <table class="tableSectionConfigArm" width="80%" align="center">
                    <tr align="center">
                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE PRODUTOS POR ARMAZÉM</h2></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>ARMAZÉM</b></td>
                        <td>
                            <select class="selectConfigArm" name="armazem[]">
                                <option value="*000*"> -- Selecione o armazém: --</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['armazem_id'] ?>"><?= $v['armazem_nome'] . "/" . $v['cid_nome'] . " - " . $v['est_uf']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>PRODUTO</b></td>
                        <td>
                            <select class="selectConfigArm" name="produto[]">
                                <option value="*000*"> -- Selecione o produto: --</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM produto");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['produto_id'] ?>"><?= $v['produto_nome'] . "/" . $v['produto_tamanho']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>QUANTIDADE</b></td>
                        <td><input type="text" class="selectConfigArm qtd_prod" name="produto_qtd[]"></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>PREÇO UNITÁRIO</b></td>
                        <td><input type="text" class="selectConfigArm money" name="produto_preco[]" size="60"></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCONTO (%)</b></td>
                        <td><input type="text" class="selectConfigArm porcent" name="produto_desconto_porcent[]" placeholder=" (Opcional)" size="60"></td>
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
        $('.divCadProduto').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });

        mask();
        insertProdutoArmazem();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IPA"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            elseif($_GET['fnc'] == "IA"):?>
                <script>
                    carregar('<?= base_url_adm_php(); ?>armazem/inserir_armazem');
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>