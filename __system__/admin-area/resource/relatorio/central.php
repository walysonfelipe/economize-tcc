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
    <title>e.conomize | Central de relatórios</title>
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
            <div class="divEcoTable">
                <form id="formGeraRelatorio" action="especifico" method="post">
                    <div class="divGerarRelatorio">
                        <div style="margin:25px 15%;width:70%;">
                            <table class="tableSectionConfigArm" width="80%" align="center">
                                <tr align="center">
                                    <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:16px;">GERAR RELATÓRIO</h2></td>
                                </tr>
                                <tr>
                                    <td align="center" style="text-align:center;color:#9C45EB;"><b>DATA</b></td>
                                    <td>
                                        <input type="radio" id="typeDateRelatDay" value="day" name="typeDateRelat" class="selTypeDate" checked size="60"/> <label for="typeDateRelatDay">Dia</label>
                                        <input type="radio" id="typeDateRelatMonth" value="month" name="typeDateRelat" class="selTypeDate" size="60"/> <label for="typeDateRelatMonth">Mês</label>
                                        <input type="radio" id="typeDateRelatYear" value="year" name="typeDateRelat" class="selTypeDate" size="60"/> <label for="typeDateRelatYear">Ano</label><br/>
                                        <div class="divTypeDate">
                                            <input type="text" class="selectConfigArm date" placeholder="dd/mm/aaaa" name="dayRelat" id="dayRelat" size="60"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="text-align:center;color:#9C45EB;"><b>ARMAZÉM(NS)</b></td>
                                    <td>
                                        <?php
                                            $sel = $conn->prepare("SELECT a.armazem_nome, c.cid_nome, e.est_uf, a.armazem_id FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id");
                                            $sel->execute();
                                            while($row = $sel->fetch( PDO::FETCH_ASSOC )):?>
                                                <input type="checkbox" name="<?= $row['armazem_id']; ?>" id="<?= $row['armazem_id']; ?>"> <label for="<?= $row['armazem_id']; ?>"><?= $row['armazem_nome']; ?> | <?= $row['cid_nome']; ?> - <?= $row['est_uf']; ?></label>
                                                <?php
                                            endwhile;
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="divSubmit" align="center">
                        <button type="submit" id="btnUpdateGerarRelat"><i class="fas fa-file-alt"></i> &nbsp;Gerar</button>
                        <div class="help-block"></div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script src="<?= base_url(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= base_url(); ?>js/mask.js"></script>
    <script src="<?= base_url(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= base_url(); ?>js/util.js"></script>
    <script src="<?= base_url_adm(); ?>js/admin.js"></script>
    <script src="<?= base_url_adm(); ?>js/relatorio.js"></script>
</body>
</html>