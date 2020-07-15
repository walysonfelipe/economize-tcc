<?php
    use \Model\Admin;
    Admin::checkLoginAndRedirect();

    $firstName = explode(" ", $_SESSION[Admin::SESSION]['funcionario_nome']);
    $age = Project::descobrirIdade(
        Project::formatDateToSql(
            $_SESSION[Admin::SESSION]['funcionario_datanasc']
        )
    );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | <?= $firstName[0]; ?> - Meu perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link href="<?= Project::baseUrlAdm(); ?>style/admin.css" rel="stylesheet"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
</head>
<body>
    <div class="l-wrapper">
        <?php
            require 'functions/includes/menu.php';
        ?>
        <section id="conteudo" class="l-main">
            <div>
                <h3 class="dashTitle">Perfil</h3>
                <div class="divEcoTable">
                    <div class="divPerf">
                        <p>Nome: <b><?= $_SESSION[Admin::SESSION]['funcionario_nome']; ?></b></p>
                        <p>Email: <b><?= $_SESSION[Admin::SESSION]['funcionario_email']; ?></b></p>
                        <p>CPF: <b><?= $_SESSION[Admin::SESSION]['funcionario_cpf']; ?></b></p>

                        <p>Data de nascimento: <b><?= $_SESSION[Admin::SESSION]['funcionario_datanasc']; ?></b></p>
                        <p>Idade: <b><?= $age; ?></b></p>
                        <p>Setor: <b><?= $_SESSION[Admin::SESSION]['setor_nome']; ?></b></p>
                        <p>Registro: <b><?= $_SESSION[Admin::SESSION]['funcionario_registro']; ?></b></p>

                        <!-- <p><button class="mudarSenha">Mudar senha</button></p> -->

                        <div class="divMudarSenha">
                            <!-- <button class="cancelarMudarSenha"><i class="far fa-times-circle"></i></button> -->
                            <h4 class="titleChangePass"><i class="fas fa-unlock"></i> MUDE A SENHA</h4>
                            <div class="divInputSenha">
                                <form id="formMudarSenha">
                                    <div class="sectionLabelInputChangePass">
                                        <div>
                                            <label for="senha_atual">Senha atual</label>
                                            <input type="password" id="senha_atual" name="senha_atual"/>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="sectionLabelInputChangePass">
                                        <div>
                                            <label for="senha_nova">Nova senha</label>
                                            <input type="password" id="senha_nova" name="senha_nova"/>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="sectionLabelInputChangePass">
                                        <div>
                                            <label for="senha_nova_confirme">Confirme a senha</label>
                                            <input type="password" id="senha_nova_confirme" name="senha_nova_confirme"/>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <button class="btnSaveMudarSenha" id="btnSaveMudarSenha" type="submit"><i class="fas fa-save"></i> SALVAR</button>
                                    <div class="help-block"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrlAdm(); ?>js/admin.js"></script>
    <script src="<?= Project::baseUrlAdm(); ?>js/configUser.js"></script>
</body>
</html>