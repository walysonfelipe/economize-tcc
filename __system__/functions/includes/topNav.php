<?php
    use Model\Storage;
?>
<div class="topNavCity">
    <h6 class="linkMenuTopNavCityMobile linkArm" id="myBtnArmazemMobile" title="Veja os armazéns disponíveis">
        <i class="fas fa-globe-americas"></i> <span class="armName"><?= isset($_SESSION[Storage::SESSION]['arm_cm']) ? $_SESSION[Storage::SESSION]['arm_cm'] : $_SESSION[Storage::SESSION]['arm']; ?></span>
    </h6>
</div>
<div class="companyNameSpace">
    <h1 class="comapanyName"><a class="linkCompanyName" href="<?= Project::baseUrlPhp(); ?>"><img src="<?= Project::baseUrl(); ?>style/img/banner/logoPadrao.png" alt="e.conomize - Mercado digital" title="e.conomize - Mercado digital"></a></h1>
</div>
<div class="searchSpace">
    <div class="searchBoxHeader" id="searchBoxHeader">
        <form class="formPesquisaHeader" method="get" action="<?= Project::baseUrlPhp(); ?>pesquisa">
            <input class="pesquisaTxtHeader" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ; ?>" type="text" name="q" placeholder=" Clique e pesquise" title="Pesquise por produtos">
            <button class="pesquisaBtnHeader" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>
<ul class="menuTopNav">
    <li class="celulaTopNavCity linkArm" id="myBtnArmazem" title="Veja os armazéns disponíveis">
        <a class="linkMenuTopNavCity" href="#">
            <span class="armName"><?= isset($_SESSION[Storage::SESSION]['arm_cm']) ? $_SESSION[Storage::SESSION]['arm_cm'] : $_SESSION[Storage::SESSION]['arm']; ?></span>
        </a>
    </li>
    <li class="celulaTopNav">
        <a class="linkMenuTopNav" href="<?= Project::baseUrlPhp(); ?>compra/procedimento">
            <div class="leftBack">
                <i class="fas fa-shopping-cart"></i>
            </div>
            CARRINHO
        </a>
    </li>
    <li class="celulaTopNav" id="myBtn2">
        <a class="linkMenuTopNav" href="#">
            <div class="leftBack">
                <i class="far fa-user-circle"></i>
            </div>
            <span class="s_login">ENTRAR</span>
        </a>
    </li>
</ul>
<ul class="menuTopNavMobile">
  <li class="celulaTopNavMobile"><a class="linkMenuTopNavMobile" href="#" id="myBtn"><i class="far fa-user-circle"></i></a></li>
  <li class="celulaTopNavMobile"><a class="linkMenuTopNavMobile" href="<?= Project::baseUrlPhp(); ?>compra/procedimento"><i class="fas fa-shopping-cart"></i></a></li>
</ul>