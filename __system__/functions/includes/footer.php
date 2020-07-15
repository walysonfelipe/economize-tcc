<div class="svg-container">
    <div class="header-hero">
        <h1>Um mercado online<br/> como você nunca viu antes</h1>
        <p>e.conomize seu tempo. e.conomize seu dinheiro.</p>
    </div>
    <div class="header-visuals">
        <div class="Iphone">
            <img src="<?= Project::baseUrl(); ?>style/img/e-light-icon.png" alt="e.conomize"/>
        </div>
    </div>
</div>
<div class="footerList">
    <ul class="footer-nav">
        <li><h6 class="titleListFooter">SAIBA MAIS</h6></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>ajuda/institucional">Institucional</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>ajuda/horario-armazem">Entregas</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>ajuda/subcidades">Armazéns e Subcidades</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>ajuda/como-comprar">Como comprar</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>ajuda/pereciveis-congelados">Perecíveis e Congelados</a></li>
    </ul>
    <ul class="footer-nav ftNav2">
        <li><h6 class="titleListFooter">DEPARTAMENTOS</h6></li>
        <?php
            if (isset($listDepartment) && count($listDepartment) > 0):
                $i = [];
                if (count($listDepartment) > 5) $t = 6;
                else $t = count($listDepartment);

                for ($c = 0; $c < $t; $c++):
                    $perm = false;
                    while ($perm !== true):
                        $rand = rand(0, (count($listDepartment) - 1));
                        
                        if (!in_array($rand, $i)):
                            array_push($i, $rand);
                            $perm = true;
                        endif;
                    endwhile;?>
                    <li><a class="linkListFooter" href="<?= Project::baseUrlPhp() . $listDepartment[$i[$c]]['depart_url']; ?>"><?= Project::formatFirstName($listDepartment[$i[$c]]['depart_nome']); ?></a></li>
                    <?php
                endfor;
            endif;
        ?>
    </ul>
    <ul class="footer-nav ftNav3">
        <li><h6 class="titleListFooter">SUPORTE</h6></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>suporte/politica-de-privacidade">Política de Privacidade</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>suporte/trocas-devolucoes">Trocas e devoluções</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>suporte/duvida-frequente">Dúvidas Frequentes</a></li>
        <li><a class="linkListFooter" href="<?= Project::baseUrlPhp(); ?>suporte/atendimento">Atendimento online</a></li>
    </ul>
</div>
<div class="socialMediaDiv">
    <h6 class="titleSocialMedia">FIQUE ANTENADO</h6>
    <ul class="listSocialMedia">
        <li><a class="linkListSocialMedia" target="_blank" href="https://www.facebook.com/economizebrazil/"><i class="fab fa-facebook-square"></i></a></li>
        <li><a class="linkListSocialMedia" target="_blank" href="https://www.twitter.com/economizebrazil/"><i class="fab fa-twitter-square"></i></a></li>
        <li><a class="linkListSocialMedia" target="_blank" href="https://www.instagram.com/economizebrazil/"><i class="fab fa-instagram"></i></a></li>
    </ul>
</div>