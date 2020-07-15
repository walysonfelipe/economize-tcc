<?php
    use \Model\Admin;

    if (Admin::checkLogin()) {
        header("Location: " . Project::baseUrlAdmPhp() . "dashboard");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Login Admstr</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link href="<?= Project::baseUrlAdm(); ?>style/admin.css" rel="stylesheet"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="l-wrapperLogin backgroundAdmLogin">
        <div class="modalAdmLogin">   
            <h2>Login</h2> 
            <form id="form-login-adm">
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" name="funcionario_cpf" id="funcionario_cpf" class="placeholder-shown cpf" placeholder=" " value="797.500.610-63"/>
                        <label class="labelFieldCad" for="funcionario_cpf"><strong><i class="fas fa-id-card"></i> CPF</strong></label>
                    </div>
                    <div class="help-block"></div><br/>
                </div>
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="password" name="funcionario_senha" id="funcionario_senha" class="placeholder-shown" placeholder=" " value="laranja123"/>
                        <label class="labelFieldCad" for="funcionario_senha"><strong><i class="fas fa-unlock"></i> SENHA</strong></label>
                    </div>
                    <div class="help-block"></div><br/>
                </div>

                <div class="outsideSecInputRecaptcha">
                    <div class="g-recaptcha" data-sitekey="6Lc-kMMUAAAAAAftO2zb4a9HLpwdlGeHtOipQMcr"></div>
                </div>

                <button type="submit" class="btnSend" id="btnLogin">ENTRAR</button>
                <div class="help-block"></div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrlAdm(); ?>js/login.js"></script>
</body>
</html>