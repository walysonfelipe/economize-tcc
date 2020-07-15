<?php use \Model\Admin; ?>

<header class="l-header">
    <h2 class="user_perf">
        <a href="<?= Project::baseUrlAdmPhp(); ?>user"><i class="far fa-user-circle"></i></a>
    </h2>

    <h2 class="logout">
        <a href="<?= Project::baseUrlAdmPhp(); ?>functions/logout"><i class="fas fa-sign-out-alt"></i></a>
    </h2>

    <h2 class="notification btnModalNot">
        <i class="fas fa-bell"></i>
        <div class="numNot"></div>
    </h2>

        <div class="modalNotContent" id="myModalNot">
            <div class="headerNot" id="headerNot">
                <span class="bHeaderNot" id="bHeaderNot">Notificações</span>
                <span class="aNotMarca" func-id="<?= $_SESSION[Admin::SESSION]['funcionario_id'] ?>" id="aNotMarca">Marcar todas como lidas</span>
            </div>
            <div class="showNotModal" id="showNotModal">

            </div>
        </div>

    <div class="divLinkCompanyNameAdm">
        <a class="linkCompanyNameAdm" href="<?= Project::baseUrlAdmPhp(); ?>dashboard">
            <img src="<?= Project::baseUrl(); ?>style/img/banner/logoPadrao.png" alt="e.conomize - Mercado digital" title="e.conomize - Mercado digital">
        </a>
    </div>
</header>
<section class="l-menu">
    <!-- <h1 class="tituloAdminPage">
        <a class="linkAdmDash" href="<?= Project::baseUrlPhp(); ?>admin_area/dashboard">
            PAINEL
        </a>
    </h1> -->

<!-- novo menu -->

    <nav class="menuNavigation">
        <div class="item">
            <input type="checkbox" id="check1">
            <label for="check1"><i class="fas fa-warehouse"></i>&nbsp; ARMAZÉM</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>armazem/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>armazem/central?fnc=IPA">ADICIONAR PRODUTO</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>armazem/central?fnc=IPA">REGISTRAR ARMAZÉM</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check2">
            <label for="check2"><i class="fas fa-headset"></i>&nbsp; ATENDIMENTO ONLINE</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>atendimento/central">GERENCIADOR</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check3">
            <label for="check3"><i class="fas fa-ad"></i>&nbsp; BANNERS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>banner/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>banner/central?fnc=IB">INSERIR BANNER</a></li>
            </ul>
        </div>
        <!-- <div class="item">
            <input type="checkbox" id="check4">
            <label for="check4"><i class="fas fa-shopping-basket"></i>&nbsp; COMPRAS</label>
            <ul>
                <li><a href="compra/central">GERENCIADOR</a></li>
            </ul>
        </div> -->
        <div class="item">
            <input type="checkbox" id="check5">
            <label for="check5"><i class="fas fa-ticket-alt"></i>&nbsp; CUPONS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>cupom/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>cupom/central?fnc=IC">INSERIR CUPOM</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check6">
            <label for="check6"><i class="far fa-building"></i>&nbsp; DEPARTAMENTOS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>departamento/central">GERENCIADOR</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check7">
            <label for="check7"><i class="far fa-question-circle"></i>&nbsp; DÚVIDAS FREQUENTES</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>duvida-frequente/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>duvida-frequente/central?fnc=IDF">INSERIR DÚVIDA FREQUENTE</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check8">
            <label for="check8"><i class="fas fa-truck"></i>&nbsp; ENTREGAS <span class="notifEnt"></span></label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>entrega/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>horarios/central">HORÁRIOS</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>horarios/central?fnc=IH">INSERIR HORÁRIO</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check9">
            <label for="check9"><i class="fas fa-pallet"></i>&nbsp; FORNECEDORES</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>fornecedor/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>fornecedor/central?fnc=IF">INSERIR FORNECEDOR</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check10">
            <label for="check10"><i class="fas fa-walking"></i>&nbsp; FUNCIONÁRIOS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>funcionario/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>funcionario/central?fnc=IF">INSERIR FUNCIONÁRIO</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>setor/central">SETORES</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check11">
            <label for="check11"><i class="fas fa-mail-bulk"></i>&nbsp; POSTAGENS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>postagem/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>postagem/central?fnc=IP">INSERIR POSTAGEM</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check12">
            <label for="check12"><i class="fas fa-trademark"></i>&nbsp; PRODUTOS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>produto/central">GERENCIADOR</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>produto/central?fnc=IP">INSERIR PRODUTO</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>categoria/central?fnc=IC">INSERIR CATEGORIA</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>subcategoria/central?fnc=IS">INSERIR SUBCATEGORIA</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>marca/central?fnc=IM">INSERIR MARCA</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check13">
            <label for="check13"><i class="fas fa-file-alt"></i>&nbsp; RELATÓRIOS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>relatorio/central">ESPECÍFICO</a></li>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>relatorio/geral">GERAL</a></li>
            </ul>
        </div>
        <div class="item">
            <input type="checkbox" id="check14">
            <label for="check14"><i class="fas fa-users"></i>&nbsp; USUÁRIOS</label>
            <ul>
                <li><a href="<?= Project::baseUrlAdmPhp(); ?>usuario/central">GERENCIADOR</a></li>
            </ul>
        </div>
    </nav>
</section>