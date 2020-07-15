<?php
    use Model\Storage;

    $sql = new Sql();

    $horariosArm = $sql->select("SELECT * FROM dados_horario_entrega d JOIN horarios_entrega h ON d.dados_horario = h.hora_id JOIN armazem a ON d.dados_armazem = a.armazem_id WHERE a.armazem_id = :arm_id ORDER BY h.dia", [
        ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
    ]);

    $horariosSub = $sql->select("SELECT * FROM cidade c JOIN subcidade s ON s.cid_id = c.cid_id JOIN estado e ON s.est_id = e.est_id JOIN armazem a ON c.cid_id = a.cidade_id WHERE a.armazem_id = :arm_id", [
        ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
    ]);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>e.conomize | Entregas</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>/style/css/minified-main.css"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css"/>
</head>
<body>
    <div class="l-wrapper_FiltroPesq">
        <div class="l-topNavFiltroPesq" id="topNav">
        <?php
            include('__system__/functions/includes/topNav.php');
        ?>
        </div>

        <nav class="l-headerNav" id="headerNav">
        <?php
            include('__system__/functions/includes/header.php');
        ?>
        </nav>

        <div class="l-bottomNav" id="bottomNav">
        <?php
            include('__system__/functions/includes/bottom.php');
        ?>
        </div>

        <div class="l-mainFiltroPesq">
            <img class="imageOnLeft" src="<?= Project::baseUrl(); ?>style/img/banner/map.png" alt="Equipe e.conomize">
            <div class="rightDiv">
                <h2 class="defaultTitle">ENTREGAS E.CONOMIZE</h2>
                <p class="paragOnRight"> Através da maneira e.conomize&#174 de fazer comércio pela internet, com velocidade e praticidade, fazer compras online se tornou algo trivial. Parte da nossa proposta é proporcionar comodidade para nossos clientes, e isso passa muito pela estrutura 100% digital que desenvolvemos, onde ocorre todos os processos necessários para um consumo dinâmico e flexível por parte dos clientes. Com isso, a entrega em domicílio é etapa fundamental nesse sistema e tem um funcionamento voltado a maximizar o tempo e favorecer as diversas rotinas que os usuários possuem.
                </p>
            </div>
            <br>

            <img class="imageOnRight" src="<?= Project::baseUrl(); ?>style/img/banner/schedule.png" alt="Equipe e.conomize">
            <div class="leftDiv">
                <h2 class="defaultTitle">HORÁRIOS DE ENTREGA</h2>
                <p class="paragOnLeft">Ao agendar uma compra, você terá de escolher um horário de preferência para que ela seja entregue no endereço, previamente, definido. Contudo, há algumas restrições nessa escolha. Os horários disponíveis são do dia atual e do dia seguinte da compra, sendo que, os horários do dia seguinte só estarão disponíveis caso haja somente um ou nenhum horário para o dia atual. Caso a sua cidade seja uma "subcidade", ela terá horários próprios para entrega, ou seja, não obrigatoriamente os horários serão iguais aos da cidade do seu armazém. Se tiver dúvida sobre "Armazéns e Subcidades" <a href="<?= Project::baseUrlPhp(); ?>ajuda/subcidades">clique aqui</a>.</p>
            </div>
            <div class="l-horarios">
                <h3 class="itinerarioTitle">ITINERÁRIO do <?= $_SESSION[Storage::SESSION]['arm_nome'];?></h3>
                <hr class="bottomTitle">
                <div class="table100 ver6 divTableArm">
                    <table align='center'>
                        <tr class="row100 head">
                            <th class="column100" colspan='8' style="text-align:center;">   
                                    <?= $_SESSION[Storage::SESSION]['arm'] .' | <small>Cidade sede</small>'; ?>
                            </th>
                        </tr>
                <?php
                    if (count($horariosArm) > 0) {
                        $dia_sem = 0;
                        $dia_semana = [];
                        $hora = [];
                        $c = -1;
                        foreach ($horariosArm as $v) {
                            $dia = Project::dayOfWeek($v['dia']);

                            if ($dia_sem != $v['dia']) {
                                array_push($dia_semana, $dia);
                                $dia_sem = $v['dia'];
                                $c++;
                            }

                            $hora[$c][] = substr($v['hora'], 0, 2) . "h" . substr($v['hora'], 3, 2);
                        }

                        if (!empty($dia_semana)) {
                            foreach ($dia_semana as $k => $v) {
                                echo '
                                    <tr class="row100">
                                        <td class="columnTitle">' . $v . '</td>
                                ';
                                asort($hora[$k]);
                                foreach ($hora[$k] as $key => $val) {
                                    echo '
                                        <td class="column100 column1">' . $val . '</td>
                                    ';
                                }
                                echo '
                                    </tr>
                                ';
                            }
                        }
                        echo '
                            </table>
                        </div>
                        ';
                    } else {
                        echo '<h2 style="text-align:center;">Não há horários disponíveis</h2>';
                    }

                    //HORÁRIOS DAS SUBCIDADES
                    if (count($horariosSub) > 0) {
                        $sub = "";
                        foreach ($horariosSub as $v) {
                            if ($sub != $v['subcid_nome']) {
                                $subcidades[] = '
                                    <div class="table100 ver6 divTableArm">
                                        <table align="center">
                                            <tr class="row100 head">
                                                <th class="column100" colspan="8" style="text-align:center;">
                                                    ' . $v['subcid_nome'] . ' - ' . $v['est_uf'] . ' | <small>Subcidade de ' . $_SESSION[Storage::SESSION]['arm'] . '</small>
                                                </th>
                                            </tr>
                                ';
                                $sub_fecho[] = '
                                        </table>
                                    </div>
                                ';
                                $subcid[] = $v;
                                $sub = $v['subcid_nome'];
                            }
                        }
                    }

                    if (isset($subcidades)) {
                        foreach ($subcidades as $k => $v) {
                            echo $v;
                            //BUSCA OS HORÁRIOS DE CADA SUBCIDADE
                            $results = $sql->select("SELECT * FROM subcidade s JOIN dados_horario_subcidade d ON d.dados_subcidade = s.subcid_id JOIN horarios_entrega h ON d.dados_horario = h.hora_id WHERE s.subcid_id = :sub_id ORDER BY h.dia", [
                                ":sub_id" => $subcid[$k]['subcid_id']
                            ]);

                            if (count($results) > 0) {
                                $dia_sem = 0;
                                $dia_semana = [];
                                $hora = [];
                                $c = -1;
                                foreach ($results as $v) {
                                    $dia = Project::dayOfWeek($v['dia']);
        
                                    if ($dia_sem != $v['dia']) {
                                        array_push($dia_semana, $dia);
                                        $dia_sem = $v['dia'];
                                        $c++;
                                    }
                                    $hora[$c][] = substr($v['hora'], 0, 2) . "h" . substr($v['hora'], 3, 2);
                                }

                                if (!empty($dia_semana)) {
                                    foreach ($dia_semana as $k_d => $v_d) {
                                        echo '
                                            <tr class="row100">
                                                <td class="columnTitle">' . $v_d . '</td>
                                        ';
                                        asort($hora[$k_d]);
                                        foreach ($hora[$k_d] as $key => $val) {
                                            echo '
                                                <td class="column100 column1">' . $val . '</td>
                                            ';
                                        }
                                        echo '
                                            </tr>
                                        ';
                                    }
                                } else {
                                    echo '
                                        <tr class="row100">
                                            <th colspan="8">- Sem horários disponíveis -</th>
                                        </tr>
                                    ';
                                }
                            } else {
                                echo '
                                    <tr class="row100">
                                        <th colspan="8">- Sem horários disponíveis -</th>
                                    </tr>
                                ';
                            }
                            echo $sub_fecho[$k];
                        }
                    } else {
                        echo '
                            <h2 style="text-align:center;">Não há subcidades para ' . $_SESSION[Storage::SESSION]['arm'] . '</h2>
                        ';
                    }
                ?>
            </div>
        </div>
        
        <?php
            include('__system__/functions/includes/modal.php');
        ?>

        <div class="l-footerFiltroPesq" id="footer">
        <?php
            include('__system__/functions/includes/footer.php');
        ?>
        </div>
        <div class="l-footerBottomFiltroPesq" id="footerBottom">
        <?php
            include('__system__/functions/includes/bottomFooter.html');
        ?>
        </div>
    </div>

    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
</body>
</html>