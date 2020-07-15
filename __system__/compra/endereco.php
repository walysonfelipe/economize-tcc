<?php
    use Model\User;
?>
<ul class="progress-tracker progress-tracker--word progress-tracker--word-left progress-tracker--center anim-ripple-large">
    <li class="progress-step is-complete compCart">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 1</h4>
            <i class="fas fa-shopping-cart"></i> CARRINHO
        </span>
    </li>
    <li class="progress-step is-active">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 2</h4>
            <i class="fas fa-map-marker-alt"></i> ENDEREÇO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 3</h4>
            <i class="far fa-clock"></i> AGENDAMENTO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 4</h4>
            <i class="far fa-credit-card"></i> PAGAMENTO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 5</h4>
            <i class="fas fa-file-alt"></i> EXTRATO
        </span>
    </li>
</ul>
<h2 align="center" class="tituloOfertas"><i class="fas fa-truck"></i> ENDEREÇO DA ENTREGA</h2>
<div class="divAgend">
    <h2>Confirme ou mude o endereço para fazermos a entrega!</h2>
    <form id="endereco_entrega">
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" CEP" class="cep placeholder-shown" id="usu_cep" 
                value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][0] : $_SESSION[User::SESSION]['usu_cep']; ?>" name="usu_cep"/>
                <label class="labelFieldCad"><strong>CEP</strong></label>
            </div>
            <span class="answer-cep"></span>
            <div class="help-block"></div><br/>
        </div>
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" Logradouro" class="placeholder-shown" id="usu_end" value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][1] : $_SESSION[User::SESSION]['usu_end']; ?>" name="usu_end"/>
                <label class="labelFieldCad"><strong>LOGRADOURO</strong></label>
            </div>
            <div class="help-block"></div><br/>
        </div>
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" Número" class="placeholder-shown" id="usu_num" value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][2] : $_SESSION[User::SESSION]['usu_num']; ?>" name="usu_num"/>
                <label class="labelFieldCad"><strong>NÚMERO</strong></label>
            </div>
            <div class="help-block"></div><br/>
        </div>
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" Complemento" class="placeholder-shown" id="usu_complemento" value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][3] : $_SESSION[User::SESSION]['usu_complemento']; ?>" name="usu_complemento"/>
                <label class="labelFieldCad"><strong>COMPLEMENTO</strong></label>
            </div>
            <div class="help-block"></div><br/>
        </div>
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" Bairro" class="placeholder-shown" id="usu_bairro" value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][4] : $_SESSION[User::SESSION]['usu_bairro']; ?>" name="usu_bairro"/>
                <label class="labelFieldCad"><strong>BAIRRO</strong></label>
            </div>
            <div class="help-block"></div><br/>
        </div>
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" Cidade" class="placeholder-shown" id="usu_cidade" value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][5] : $_SESSION[User::SESSION]['usu_cidade']; ?>" name="usu_cidade"/>
                <label class="labelFieldCad"><strong>CIDADE</strong></label>
            </div>
            <div class="help-block"></div><br/>
        </div>
        <div class="outsideSecInputCad">
            <div class="field -md">
                <input type="text" placeholder=" Estado" class="placeholder-shown" id="usu_uf" value="<?= isset($_SESSION['end_agend']) ? $_SESSION['end_agend'][6] : $_SESSION[User::SESSION]['usu_uf']; ?>" name="usu_uf"/>
                <label class="labelFieldCad"><strong>ESTADO</strong></label>
            </div>
            <div class="help-block"></div><br/>
        </div>
        <div class="btnSendCad">
            <button class="btnSubCad" type="submit" id="btn-cad">CONFIRMAR</button>
            <div class="help-block"><p style="opacity:0;">A</p></div>
        </div>
    </form>
</div>

<script>
    mask();
    $("#usu_cep").keyup(function(){
        if($(this).val().length == 9) {
            $.ajax({
                url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
                dataType: 'json',
                beforeSend: function() {
                    $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;` + loadingResSmall(`Buscando...`));
                    $("#usu_end").val(``);
                    $("#usu_complemento").val(``);
                    $("#usu_bairro").val(``);
                    $("#usu_uf").val(``);
                    $("#usu_cidade").val(``);
                },
                success: function(resposta) {
                    if(resposta.erro) {
                        $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;<small style="color:#A94442;" class="smallAnswer">Endereço inexistente</small>`);
                    } else {
                        $(".answer-cep").html(``);
                        $("#usu_end").val(resposta.logradouro);
                        $("#usu_complemento").val(resposta.complemento);
                        $("#usu_bairro").val(resposta.bairro);
                        $("#usu_uf").val(resposta.uf);
                        $("#usu_cidade").val(resposta.localidade);
                        $("#usu_num").focus();
                    }
                }
            });
        } else {
            $(".answer-cep").html(``);
            $("#usu_end").val(``);
            $("#usu_complemento").val(``);
            $("#usu_bairro").val(``);
            $("#usu_uf").val(``);
            $("#usu_cidade").val(``);
        }
    });

    $('.is-complete').css({'cursor': 'pointer'});
    $('.compCart').click(function(e) {
        e.preventDefault();
        
        buscaCarrinho();
    });

    $("#endereco_entrega").submit(function() {
        $.ajax({
            dataType: 'json',
            type: 'post',
            data: $(this).serialize(),
            url: BASE_URL + 'functions/agendamento',
            beforeSend: function() {
                $('#btn-cad').siblings('.help-block').html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    buscaAgendamento();
                } else {
                    if(json['armazem']) {
                        showErrors(json["error_list"]);
                    } else {
                        clearErrors();
                        Swal.fire({
                            title: "E.conomize informa:",
                            text: "O armazém que está comprando não faz entrega em sua cidade, desculpe-nos!",
                            type: "error",
                            confirmButtonColor: "#9C45EB",
                            confirmButtonText: "Ok, cancelar",
                            footer: '<a href="' + BASE_URL + 'ajuda/subcidades">Veja as subcidades deste armazém</a>'
                        });
                    }
                    
                }
            }
        });
        return false;
    });
</script>