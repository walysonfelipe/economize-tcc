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
    <title>e.conomize | Central de promoções</title>
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
            <h3 class="titleAdm">GERENCIADOR DE PROMOÇÕES PERSONALIZÁVEIS</h3>
            <div id="conteudo">
            
            </div>
            <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar promoção</button>
        </section>
        <footer class="l-footer">
        </footer>

        <div class="myModalAdd" id="myModalAdd">
            <div class="modalAddContent">
                <span class="closeModalAdd">&times;</span>
                <div class="showAddModal">
                    <div class="divCadProduto divCadPromocao">
                        <form class="formInserir formInserirPromocao">
                            <div class="divAddCadPromocao">
                                <div style="margin-bottom:60px;">
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRAR PROMOÇÃO PERSONALIZADA</h2></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>TÍTULO</b></td>
                                            <td><input type="text" class="selectConfigArm" maxlength="40" name="promo_nome" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>SUBTÍTULO</b></td>
                                            <td>
                                                <input type="text" class="selectConfigArm" maxlength="100" name="promo_subtit" size="60"><br/>
                                                <small>* Máximo de 100 caracteres</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DESCONTO (%)</b></td>
                                            <td><input type="text" class="selectConfigArm porcent" name="promo_desconto" size="60"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>STATUS</b></td>
                                            <td>
                                                <select class="selectConfigArm" name="promo_status">
                                                    <option value="*000*"> -- Selecione o status: -- </option>
                                                    <option value="1">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>DATA DE EXPIRAÇÃO</b></td>
                                            <td><input type="text" placeholder=" (Opcional)" class="selectConfigArm datetime" name="promo_expira" size="60"></td>
                                        </tr>
                                    </table>
                                    <table class="tableSectionConfigArm" width="80%" align="center">
                                        <tr align="center">
                                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">ADICIONAR PRODUTO À PROMOÇÃO</h2></td>
                                        </tr>
                                        <tr class="trArm">
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>ARMAZÉM</b></td>
                                            <td>
                                                <select class="selectConfigArm armazemPromo" name="armazem_id[]">
                                                    <option value="*000*"> -- Selecione o armazém: --</option>
                                                    <?php
                                                        $sel = $conn->prepare("SELECT a.armazem_id, a.armazem_nome, c.cid_nome, e.est_uf FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id ORDER BY a.armazem_nome");
                                                        $sel->execute();
                                                        if($sel->rowCount() > 0):
                                                            while($v = $sel->fetch( PDO::FETCH_ASSOC )):?>
                                                                <option value="<?= $v['armazem_id'] ?>"><?= $v['armazem_nome'] . "/" . $v['cid_nome'] . " - " . $v['est_uf']; ?></option>
                                                                <?php
                                                            endwhile;
                                                        endif;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="prodTr">
                                            <td align="center" style="text-align:center;color:#9C45EB;"><b>PRODUTO</b></td>
                                            <td>
                                                <div class="inpProd"></div>
                                                <select class="selectConfigArm produtoPromo" name="produto_id[]">
                                                    <option value="*000*"> -- Selecione o produto: --</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadPromocao">Adicionar mais produtos</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertPromocao"><i class="fas fa-save"></i> Cadastrar</button>
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
    <script src="<?= base_url_adm(); ?>js/promocao.js"></script>
    <script>
        $('.addCadPromocao').click(function(e) {
            e.preventDefault();
            $('.divAddCadPromocao').append(`
                <div class="newAdd">
                    <table class="tableSectionConfigArm" width="80%" align="center">
                        <tr align="center">
                            <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">ADICIONAR PRODUTO À PROMOÇÃO</h2></td>
                        </tr>
                        <tr class="trArm">
                            <td align="center" style="text-align:center;color:#9C45EB;"><b>ARMAZÉM</b></td>
                            <td>
                                <select class="selectConfigArm armazemPromo" name="armazem_id[]">
                                    <option value="*000*"> -- Selecione o armazém: --</option>
                                    <?php
                                        $sel = $conn->prepare("SELECT a.armazem_id, a.armazem_nome, c.cid_nome, e.est_uf FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id ORDER BY a.armazem_nome");
                                        $sel->execute();
                                        if($sel->rowCount() > 0):
                                            while($v = $sel->fetch( PDO::FETCH_ASSOC )):?>
                                                <option value="<?= $v['armazem_id'] ?>"><?= $v['armazem_nome'] . "/" . $v['cid_nome'] . " - " . $v['est_uf']; ?></option>
                                                <?php
                                            endwhile;
                                        endif;
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="prodTr">
                            <td align="center" style="text-align:center;color:#9C45EB;"><b>PRODUTO</b></td>
                            <td>
                                <div class="inpProd"></div>
                                <select class="selectConfigArm produtoPromo" name="produto_id[]">
                                    <option value="*000*"> -- Selecione o produto: --</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <div class="btnRemove">
                        <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            `);
            changeArm();
        });

        // Remover o div anterior
        $('.divAddCadPromocao').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });
        
        insertPromocao();
        mask();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IPP"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>