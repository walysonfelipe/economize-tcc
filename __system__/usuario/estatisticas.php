<?php
    use Model\User;
    User::checkLoginAndRedirect();

    $sql = new Sql();

    // Pegando os últimos 12 mêses
    $end = Date("n"); // mês sem zero à esquerda
    $beg = ($end < 12) ? ($end + 1) : 1;
    $year = Date("Y");
    $q = "";

    if ($end < 12) {
        $years = 2;
        $label = (Date("Y") - 1) . " e " . Date("Y");
        $last = Date("Y") - 1;

        $q .= "AND (";
        $mes = $beg - 1;

        for ($i = 0; $i < 12; $i++) {
            if ($mes === 12 || isset($new)) {
                if (isset($new)) {
                    $mes++;
                } else {
                    $mes = 1;
                    $new = true;
                }

                if ($i < 11) {
                    $q .= "(MONTH(compra_registro) = $mes AND YEAR(compra_registro) = $year) OR ";
                } else {
                    $q .= "(MONTH(compra_registro) = $mes AND YEAR(compra_registro) = $year)";
                }
            } else {
                $mes++;
                
                $q .= "(MONTH(compra_registro) = $mes AND YEAR(compra_registro) = $last) OR ";
            }
        }

        $q .= ")";
    } else {
        $years = 1;
        $label = Date("Y");
    }

    $c = $beg;
    $months = ``;
    $i = 1;
    while ($i <= 12) {
        switch ($c) {
            case 1:
                $months .= '"Jan", ';
                break;
            case 2:
                $months .= '"Fev", ';
                break;
            case 3:
                $months .= '"Mar", ';
                break;
            case 4:
                $months .= '"Abr", ';
                break;
            case 5:
                $months .= '"Mai", ';
                break;
            case 6:
                $months .= '"Jun", ';
                break;
            case 7:
                $months .= '"Jul", ';
                break;
            case 8:
                $months .= '"Ago", ';
                break;
            case 9:
                $months .= '"Set", ';
                break;
            case 10:
                $months .= '"Out", ';
                break;
            case 11:
                $months .= '"Nov", ';
                break;
            case 12:
                $months .= '"Dez", ';
        }

        if ($c != 12)
            $int[] = $c++;
        else {
            $int[] = $c;
            $c = 1;
        }
        $i++;
    }

    $months = trim($months, ",");
    
    if ($years == 2) {
        $results = $sql->select("SELECT COUNT(compra_id) AS qtd_mes, SUM(compra_total) AS tot_mes, MONTH(compra_registro) AS mes FROM compra WHERE usu_id = :usu_id AND (YEAR(compra_registro) = $year OR YEAR(compra_registro) = $last) $q GROUP BY MONTH(compra_registro)", [
            ":usu_id" => $_SESSION[User::SESSION]['usu_id']
        ]);
        $title = $year . "/" . $last;
    } else {
        $results = $sql->select("SELECT COUNT(compra_id) AS qtd_mes, SUM(compra_total) AS tot_mes, MONTH(compra_registro) AS mes FROM compra WHERE usu_id = :usu_id AND (YEAR(compra_registro) = $year) GROUP BY MONTH(compra_registro)", [
            ":usu_id" => $_SESSION[User::SESSION]['usu_id']
        ]);
        $title = $year;
    }

    $rows = count($results);
    $qtd_mes = ``;
    $tot_mes = ``;
    
    if ($rows >= 12) {
        foreach ($results as $k => $v) {
            $qtd_mes .= '"' . $v['qtd_mes'] . '", ';
            $tot_mes .= '"' . $v['tot_mes'] . '", ';
        }
    } else {
        if ($rows > 0) {
            $c = 1;
            foreach ($results as $k => $v) {
                $month[$c] = $v['mes'];
                $qtd[$c] = $v['qtd_mes'];
                $tot[$c] = $v['tot_mes'];
                $c++;
            }

            foreach($int as $k => $v) {
                if ($k = array_search($v, $month)) {
                    $qtd_mes .= '"' . $qtd[$k] . '", ';
                    $tot_mes .= '"' . $tot[$k] . '", ';
                } else {
                    $qtd_mes .= '"0", ';
                    $tot_mes .= '"0", ';
                }
            }
        } else {
            $c = $rows;
            while ($c < 12) {
                $qtd_mes .= '"0", ';
                $tot_mes .= '"0", ';
                $c++;
            }
        }
    }

    $qtd_mes = trim($qtd_mes, ",");
    $tot_mes = trim($tot_mes, ",");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <title>e.conomize | Minhas estatísticas</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>style/css/minified-main.css">
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css">
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

        <div class="l-mainCad">
            <h2 align="center" class="tituloOfertas"><i class="fas fa-chart-line"></i> MINHAS ESTATÍSTICAS</h2>
            <center>
                <div id="chart-container">
                    <canvas class="chartDiv line-chart"></canvas>
                </div>
            </center>
        </div>
		
		<?php
            include('__system__/functions/includes/modal.php');
		?>
		
		<div class="l-footer" id="footer">
        <?php
            include('__system__/functions/includes/footer.php');
		?>
		</div>
        <div class="l-footerBottomCad" id="footerBottom">
		<?php
            include('__system__/functions/includes/bottomFooter.html');
        ?>
		</div>
    </div>

	<script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
	<script src="<?= Project::baseUrl(); ?>style/libraries/chart.min.js"></script>
	<script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script>
        var ctx = document.getElementsByClassName("line-chart");
        var chartGraph = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?= $months; ?>],
                datasets: [
                    {
                        label: "Compras",
                        data: [<?= $qtd_mes; ?>],
                        borderWidth: 2,
                        borderColor: '#9C45EB',
                        backgroundColor: 'transparent'
                    }, {
                        label: "Gastos",
                        data: [<?= $tot_mes; ?>],
                        borderWidth: 2,
                        borderColor: '#333',
                        backgroundColor: 'transparent'
                    }
                ]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: "Últimos 12 meses (<?= $title; ?>) de <?= $_SESSION[User::SESSION]['usu_first_name']; ?> no e.conomize"
                },
                labels: {
                    fontStyle: "bold"
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            <?php
                                if (!$rows):?>
                                    max: 5,
                                    stepSize: 1,
                                    <?php
                                endif;
                            ?>
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>