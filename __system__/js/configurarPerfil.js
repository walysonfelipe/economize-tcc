function showTelefones() {
    $.ajax({
        dataType: 'json',
        type: 'post',
        data: 'show_tel=1',
        url: BASE_URL + 'functions/configurarPerfil',
        beforeSend: function() {
            $(".divTelefones").html(loadingRes("Buscando telefone(s)..."));
        },
        success: function(json) {
            $(".divTelefones").html(loadingRes("Importando telefone(s)..."));
            $('.divTelefones').html(``);
            for(var i = 0; json['tel'].length > i; i++) {
                $('.divTelefones').append(`
                    <span class="specialSpan"><b>Número:</b> ` + json['tel'][i].tel_num + ` 
                    <b>| Tipo:</b> ` + json['tel'][i].tpu_tel_nome + `</span><br/>
                `);
            }
            adicionaInputsMudarTelefone();
            adicionaInputsAddTelefone();
        }
    });
}

function mudarTelefone() {
    $('#formMudaTelefone').submit(function() {
        var dado = $(this).serialize();

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/configurarPerfil',
            beforeSend: function() {
                clearErrors();
                $("#btnSaveMudarTelefone").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    $("#btnSaveMudarTelefone").siblings(".help-block").html(loadingRes("Mudando telefone(s)..."));
                    clearErrors();
                    Toast.fire({
                        type: 'success',
                        title: 'Dados alterados com sucesso'
                    });
                    showTelefones();
                    $('.divMudarTelefone').html(``);
                } else {
                    $("#btnSaveMudarTelefone").siblings(".help-block").html(json["error"]);
                }
            }
        });

        return false;
    });
}

function removeInputsTelefone() {
    $('.cancelarMudarTel').click(function(e) {
        $('.divMudarTelefone').html(``);
    });
}

function deletarTelefone() {
    $('.deletaTelefone').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Deseja mesmo excuir este número de telefone?",
            text: "Uma vez deletando este número de telefone, será perdido permanentemente",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#9C45EB",
            confirmButtonText: "Sim, excluir"
        }).then((result) => {
            if(result.value) {
                var dado = "deletaTel=" + $(this).attr("id-tel");
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: dado,
                    url: BASE_URL + 'functions/configurarPerfil',
                    success: function(json) {
                        if(json['status']) {
                            Toast.fire({
                                type: 'success',
                                title: "Telefone deletado"
                            });
                            showTelefones();
                            $('.divMudarTelefone').html(``);
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: json['error']
                            });
                        }
                    }
                });
            }
        });
    });
}

function addTelefone() {
    $('#formAddTelefone').submit(function() {
        var dado = $(this).serialize();

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/configurarPerfil',
            beforeSend: function() {
                clearErrors();
                $("#btnSaveAddTelefone").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    $("#btnSaveAddTelefone").siblings(".help-block").html(loadingRes("Adicionando telefone..."));
                    clearErrors();
                    Toast.fire({
                        type: 'success',
                        title: 'Telefone adicionado com sucesso'
                    });
                    showTelefones();
                    $('.divMudarTelefone').html(``);
                } else {
                    $("#btnSaveAddTelefone").siblings(".help-block").html(json["error"]);
                }
            }
        });

        return false;
    });
}

function adicionaInputsAddTelefone() {
    $('.addTelefone').click(function(e) {
        e.preventDefault();
        $('.divMudarEndereco').html(``);
        $('.divMudarSenha').html(``);
        $('.l-mainCad').css({'height':'auto'});

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: 'add_tel=1',
            url: BASE_URL + 'functions/configurarPerfil',
            success: function(json) {
                if(json['status']) {
                    $('.divMudarTelefone').html(`
                        <button class="cancelarMudarTel"><i class="far fa-times-circle"></i></button>
                        <h4 class="titleChangePass"><i class="fas fa-mobile-alt"></i> ADICIONE TELEFONE</h4>
                        <form id="formAddTelefone">
                            <div class="sectionLabelInputChangePass">
                                <div>
                                    <label for="inputAddTel">Número</label><br/>
                                    <input type="text" name="tel_num" class="sp_celphones" id="inputAddTel"/><br/>
                                </div>
                            </div>
                            <div class="sectionLabelInputChangePass">
                                <div>
                                    <label>Tipo de telefone</label>
                                    <select name="tipo_tel" class="selAddTel"></select>
                                </div>
                            </div>
                            <button class="btnSaveMudarSenha btnSaveAddTelefone" id="btnSaveAddTelefone" type="submit"><i class="fas fa-save"></i> SALVAR</button>
                            <div class="help-block"></div>
                        </form>
                    `);

                    for(var c = 0; c < json['tipo_tel'].length; c++) {
                        $(`.selAddTel`).append(`
                            <option value="` + json['tipo_tel'][c].tpu_tel_id + `">` + json['tipo_tel'][c].tpu_tel_nome + `</option>
                        `);
                    }
                    
                    removeInputsTelefone();
                    addTelefone();
                    mask();
                } else {
                    Toast.fire({
                        type: 'error',
                        title: 'Máximo de telefones atingido'
                    });
                }
            }
        });
    });
}

function adicionaInputsMudarTelefone() {
    $('.mudarTelefone').click(function(e) {
        e.preventDefault();
        $('.divMudarEndereco').html(``);
        $('.divMudarSenha').html(``);
        $('.l-mainCad').css({'height':'auto'});

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: 'show_tel=1',
            url: BASE_URL + 'functions/configurarPerfil',
            success: function(json) {
                $('.divMudarTelefone').html(`
                    <button class="cancelarMudarTel"><i class="far fa-times-circle"></i></button>
                    <h4 class="titleChangePass"><i class="fas fa-mobile-alt"></i> MUDE SEU(S) TELEFONE(S)</h4>
                    <form id="formMudaTelefone">
                        
                    </form>
                `);
                for(var i = 0; json['tel'].length > i; i++) {
                    var t = i + 1;
                    $('#formMudaTelefone').append(`
                        <div class="sectionLabelInputChangePass">
                            <input type="hidden" name="tel_id[]" value="` + json['tel'][i].tel_id + `"/>
                            <button id-tel="` + json['tel'][i].tel_id + `" class="deletaTelefone btnDel"><i class="far fa-times-circle"></i></button>
                            <div>
                                <label for="inpTel` + t + `">` + t + `º Número</label><br/>
                                <input type="text" id="inpTel` + t + `" name="telefone[]" class="sp_celphones" value="` + json['tel'][i].tel_num + `"/><br/>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label>Tipo de telefone</label>
                                <select name="tipo_tel[]" class="selChangeTel` + i + `"></select>
                            </div>
                        </div><br/>
                    `);
                    for(var c = 0; json['tipo_tel'].length > c; c++) {
                        if(json['tel'][i].tpu_tel == json['tipo_tel'][c].tpu_tel_id) {
                            $(`.selChangeTel` + i).append(`
                                <option value="` + json['tipo_tel'][c].tpu_tel_id + `" selected>` + json['tipo_tel'][c].tpu_tel_nome + `</option>
                            `);
                        } else {
                            $(`.selChangeTel` + i).append(`
                                <option value="` + json['tipo_tel'][c].tpu_tel_id + `">` + json['tipo_tel'][c].tpu_tel_nome + `</option>
                            `);
                        }
                    }
                }
                $('#formMudaTelefone').append(`
                    <button class="btnSaveMudarSenha btnSaveMudarTelefone" id="btnSaveMudarTelefone" type="submit"><i class="fas fa-save"></i> SALVAR</button>
                    <div class="help-block"></div>
                `);
                deletarTelefone();
                mudarTelefone();
                removeInputsTelefone();
                mask();
            }
        });
    });
}

function mudarSenha() {
    $('#formMudarSenha').submit(function() {
        var dado = $(this).serialize();
        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/configurarPerfil',
            beforeSend: function() {
                clearErrors();
                $("#btnSaveMudarSenha").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    $("#btnSaveMudarSenha").siblings(".help-block").html(loadingRes("Mudando senha..."));
                    clearErrors();
                    Toast.fire({
                        type: 'success',
                        title: 'Senha foi mudada'
                    });
                    $('.divMudarSenha').html(``);
                } else {
                    showErrorsAdmin(json["error_list"]);
                }
            }
        });

        return false;
    });
}

function adicionaButtonMudarSenha() {
    $('.cancelarMudarSenha').click(function(e) {
        $('.divMudarSenha').html(`
        
        `);
        adicionaInputsMudarSenha();
    });
}

function adicionaInputsMudarSenha() {
    $('.mudarSenha').click(function(e) {
        e.preventDefault();
        $('.divMudarTelefone').html(``);
        $('.divMudarEndereco').html(``);
        $('.l-mainCad').css({'height':'auto'});

        $('.divMudarSenha').html(`
            <button class="cancelarMudarSenha"><i class="far fa-times-circle"></i></button>
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
        `);
        adicionaButtonMudarSenha();
        mudarSenha();
    });
}

function removeInputEnd() {
    $('.cancelarMudarEnd').click(function(e) {
        e.preventDefault();
        $('.divMudarEndereco').html(``);
        $('.l-mainCad').css({'height':'auto'});
    });
}

function showEndereco() {
    $.ajax({
        data: 'mudarEnd=1',
        dataType: 'json',
        type: 'post',
        url: BASE_URL + 'functions/configurarPerfil',
        success: function(json) {
            $('.divMostraEndereco').html(`
                <span class="specialSpanEnd">
                ` + json['end'][1] + `, ` + json['end'][2] + `
                ` + json['end'][3] + `
                </span><br/>
                <span class="specialSpanEnd">
                    ` + json['end'][0] + `
                </span><br/>
                <span class="specialSpanEnd">
                ` + json['end'][4] + `, ` + json['end'][5] + ` - ` + json['end'][6] + `
                </span>
                <span class="editIconEnd">
                    <button class="mudarEndereco">
                        <i class="fas fa-edit"></i>
                    </button>
                </span>
            `);
            adicionaInputsMudarEndereco();
        }
    });
}

function mudarEndereco() {
    $('#formMudaEndereco').submit(function() {
        var dado = $(this).serialize();
        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/configurarPerfil',
            beforeSend: function() {
                clearErrors();
                $("#btnSaveMudarEndereco").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    $("#btnSaveMudarEndereco").siblings(".help-block").html(loadingRes("Mudando endereço..."));
                    clearErrors();
                    Toast.fire({
                        type: 'success',
                        title: 'Endereço foi mudado'
                    });
                    showEndereco();
                    $('.divMudarEndereco').html(``);
                    $('.l-mainCad').css({'height':'auto'});
                } else {
                    showErrorsAdmin(json["error_list"]);
                }
            }
        });

        return false;
    });
}

function adicionaInputsMudarEndereco() {
    $('.mudarEndereco').click(function(e) {
        e.preventDefault();
        $('.divMudarTelefone').html(``);
        $('.divMudarSenha').html(``);

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: 'mudarEnd=1',
            url: BASE_URL + 'functions/configurarPerfil',
            success: function(json) {
                $('.divMudarEndereco').html(`
                    <button class="cancelarMudarEnd"><i class="far fa-times-circle"></i></button>
                    <h4 class="titleChangePass"><i class="fas fa-map-marker-alt"></i> ATUALIZE SEU ENDEREÇO</h4>
                    <form id="formMudaEndereco">
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_cep">CEP</label>
                                <input type="text" class="cep" id="end_cep" name="end_cep" value="` + json['end'][0] + `"/>
                                <span class="answer-cep"></span>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_log">Logradouro</label>
                                <input type="text" id="end_log" name="end_log" value="` + json['end'][1] + `"/>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_num">Número</label>
                                <input type="text" id="end_num" name="end_num" value="` + json['end'][2] + `"/>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_comp">Complemento</label>
                                <input type="text" id="end_comp" name="end_comp" value="` + json['end'][3] + `"/>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_bairro">Bairro</label>
                                <input type="text" id="end_bairro" name="end_bairro" value="` + json['end'][4] + `"/>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_cid">Cidade</label>
                                <input type="text" id="end_cid" name="end_cid" value="` + json['end'][5] + `"/>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="sectionLabelInputChangePass">
                            <div>
                                <label for="end_uf">Estado</label>
                                <input type="text" id="end_uf" name="end_uf" value="` + json['end'][6] + `"/>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <button class="btnSaveMudarSenha btnSaveMudarEndereco" id="btnSaveMudarEndereco" type="submit"><i class="fas fa-save"></i> SALVAR</button>
                        <div class="help-block"></div>
                    </form>
                `);

                removeInputEnd();
                mudarEndereco();
                mask();
                $('.l-mainCad').css({'height':'640px'});

                $("#end_cep").keyup(function(){
                    if($(this).val().length == 9) {
                        $.ajax({
                            url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
                            dataType: 'json',
                            beforeSend: function() {
                                $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;` + loadingResSmall(`Buscando...`));
                                $("#end_log").val(``);
                                $("#end_comp").val(``);
                                $("#end_bairro").val(``);
                                $("#end_uf").val(``);
                                $("#end_cidade").val(``);
                            },
                            success: function(resposta) {
                                if(resposta.erro) {
                                    $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;<small style="color:#A94442;" class="smallAnswer">Endereço inexistente</small>`);
                                } else {
                                    $(".answer-cep").html(``);
                                    $("#end_log").val(resposta.logradouro);
                                    $("#end_comp").val(resposta.complemento);
                                    $("#end_bairro").val(resposta.bairro);
                                    $("#end_uf").val(resposta.uf);
                                    $("#end_cidade").val(resposta.localidade);
                                    $("#end_num").focus();
                                }
                            }
                        });
                    } else {
                        $(".answer-cep").html(``);
                        $("#end_log").val(``);
                        $("#end_comp").val(``);
                        $("#end_bairro").val(``);
                        $("#end_uf").val(``);
                        $("#end_cidade").val(``);
                    }
                });
            }
        });
    });
}

$('.usuMailMkt').change(function(e) {
    e.preventDefault();
    Toast.fire({
        title: loadingRes("Alterando os dados...")
    });

    if ($(this).is(':checked')) {
        var dado = "mailmkt=1";
    } else {
        var dado = "mailmkt=0";
    }

    $.ajax({
        type: 'post',
        dataType: 'json',
        data: dado,
        url: BASE_URL + 'functions/configurarPerfil',
        beforeSend: function() {
            Toast.fire({
                title: loadingRes("Alterando os dados...")
            });
        },
        success: function(json) {
            Toast.fire({
                type: 'success',
                title: "Dado alterado com sucesso"
            });
        }
    })
})

showTelefones();
adicionaInputsMudarSenha();
adicionaInputsMudarEndereco();