var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataAtend(page, qtd_result) {
    var dados = new FormData();
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
		cache: false,
		contentType: false,
		processData: false,
        url: BASE_URL4 + 'functions/atendimento',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="5" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                if(!json['empty']) {
                    $('.tbodyProd').html("");
                    for(var i = 0; json['atendimentos'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td class="tdCenter">` + json['atendimentos'][i].nome_usu + `</td>
                                <td class="tdCenter">` + json['atendimentos'][i].tp_problema + `</td>
                                <td class="tdCenter">` + json['atendimentos'][i].resp_id + `</td>
                                <td class="tdCenter">` + json['atendimentos'][i].dataenv_pro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewAtend btnProductConfigAdm" id-atendimento="` + json['atendimentos'][i].id_atd + `"><i class="fa fa-eye"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    viewAtend();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- NÃO HÁ MENSAGENS CADASTRADAS -</th>
                        </tr>
                    `);
                }
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` mensagens
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataAtend(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataAtend(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataAtend(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataAtend(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function enviaRespAtend() {
    $('.respAtendOnline').submit(function(e) {
        e.preventDefault();
        var dado = $(this).serialize();

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL4 + 'functions/atendimento',
            beforeSend: function() {
                $(".respAtendOnline .help-block").html(`
                    <p style="color:#333;text-align:center;">
                        <i class='fa fa-circle-notch fa-spin'></i> &nbsp; Verificando...
                    </p>
                `);
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Toast.fire({
                        type: "success",
                        title: "Resposta enviada com sucesso"
                    });
                    mostraModalAdd();
                    // modalAdd.style.display = "none";
                } else {
                    $(".respAtendOnline .help-block").html(json['error']);
                }
                dataAtend(page, qtd_result)
            }
        });
        return false;
    });
}

function viewAtend() {
    $('.btnViewAtend').click(function(e) {
        e.preventDefault();
        var dado = "showAtend=" + $(this).attr("id-atendimento");

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL4 + 'functions/atendimento',
            beforeSend: function() {
                $(".showAddModal").html(`<h1><i class='fa fa-circle-notch fa-spin'></i></h1>`);
                modalAdd.style.display = "block";
            },
            success: function(json) {
                if(json['mensagem']) {
                    $(".showAddModal").html(`
                        <h1 class="titleAtend">ATENDIMENTO ONLINE</h1>
                        <h3 class="dateAtend">Data da mensagem: ` + json['mensagem']['dataenv_pro'] + `</h3>

                        <div class="tp_problAtend">
                            <h3 class="tt_pAtend">Tipo problema:<h3>
                            <h4 class="ttt_pAtend">` + json['mensagem']['tp_problema'] + `</h4>
                        </div>

                        <div class="usuAtend">
                            <b>Nome: </b>` + json['mensagem']['nome_usu'] + `<br/>
                            <b>Email: </b>` + json['mensagem']['email_usu'] + `
                        </div>

                        <div class="msgAtend">
                            <h4 class="tt_msgAtend">Mensagem:</h4>
                            <p class="p_msgAtend">
                                ` + json['mensagem']['desc_problema'] + `
                            </p>
                        </div>

                        <div class="respAtend">
                            ` + ((json['resposta']) ? `
                                <h4 class="tt_respAtend">Esta mensagem já foi respondida pelo(a) ` + json['resposta']['funcionario_nome'] + ` em ` + json['resposta']['registro_resp'] + `</h4>
                                <p class="p_respAtend">` + json['resposta']['resp_atend'] + `</p>`
                             : `
                                <h4 class="tt_respAtend">Responder:</h4>
                                <form class="respAtendOnline formInserir">
                                    <input type="hidden" name="id_atd" value="` + json['mensagem']['id_atd'] + `"/>
                                    <textarea name="resp_atd" class="textRespAtend" placeholder="Escreva sua resposta aqui..."></textarea><br/>
                                    <button type="submit" class="btnRespAtend">ENVIAR</button>
                                    <div class="help-block"></div>
                                </form>
                             `) + `
                        </div>
                    `);

                    enviaRespAtend();
                }
            }
        });
    });
}

function ordenarAtendimentoSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchAtend').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchAtend').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/atendimento',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="5" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                $('.tbodyProd').html("");

                for(var i = 0; json['atendimentos'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td class="tdCenter">` + json['atendimentos'][i].nome_usu + `</td>
                            <td class="tdCenter">` + json['atendimentos'][i].tp_problema + `</td>
                            <td class="tdCenter">` + json['atendimentos'][i].resp_id + `</td>
                            <td class="tdCenter">` + json['atendimentos'][i].dataenv_pro + `</td>
                            <td class="tdCenter">
                                <button class="myBtnView btnViewAtend btnProductConfigAdm" id-atendimento="` + json['atendimentos'][i].id_atd + `"><i class="fa fa-eye"></i></button>
                            </td>
                        </tr>
                    `);
                }
                viewAtend();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` mensagens
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarAtendimentoSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarAtendimentoSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarAtendimentoSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarAtendimentoSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarAtendimento(page, qtd_result) {
    $('.sort').click(function(e) {
        e.preventDefault();

        var elementoPai = $(this).parent();
        var elementosFilho = elementoPai.find(".span_sort");
        elementosFilho.html("");

        var sort = $(this).find(".span_sort");
        
        var tipoSort = $(this).attr("data-sort");

        var dados = new FormData();
        dados.append("data_sort",  $(this).attr("data-sort"));
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        if($('#searchAtend').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchAtend').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/atendimento',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">
                            - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                        </th>
                    </tr>
                `);
            },
            success: function(json) {
                if(json['status']) {
                    $('.tbodyProd').html("");
                    if(json['sort'] == "up") {
                        sort.html(` &nbsp;&nbsp;<i class="fas fa-sort-up"></i>`);
                    } else if(json['sort'] == "down") {
                        sort.html(` &nbsp;&nbsp;<i class="fas fa-sort-down"></i>`);
                    }

                    for(var i = 0; json['atendimentos'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td class="tdCenter">` + json['atendimentos'][i].nome_usu + `</td>
                                <td class="tdCenter">` + json['atendimentos'][i].tp_problema + `</td>
                                <td class="tdCenter">` + json['atendimentos'][i].resp_id + `</td>
                                <td class="tdCenter">` + json['atendimentos'][i].dataenv_pro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewAtend btnProductConfigAdm" id-atendimento="` + json['atendimentos'][i].id_atd + `"><i class="fa fa-eye"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    viewAtend();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` mensagens
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarAtendimentoSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarAtendimentoSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarAtendimentoSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarAtendimentoSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchAtendimentoSec(page, qtd_result) {
    if($('#searchAtend').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchAtend",  $('#searchAtend').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/atendimento',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">
                            - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                        </th>
                    </tr>
                `);
            },
            success: function(json) {
                if(json['status']) {
                    if(!json['empty']) {
                        $('.tbodyProd').html("");
                        for(var i = 0; json['atendimentos'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td class="tdCenter">` + json['atendimentos'][i].nome_usu + `</td>
                                    <td class="tdCenter">` + json['atendimentos'][i].tp_problema + `</td>
                                    <td class="tdCenter">` + json['atendimentos'][i].resp_id + `</td>
                                    <td class="tdCenter">` + json['atendimentos'][i].dataenv_pro + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnView btnViewAtend btnProductConfigAdm" id-atendimento="` + json['atendimentos'][i].id_atd + `"><i class="fa fa-eye"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        viewAtend();
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="5" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` mensagens
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchAtendimentoSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchAtendimentoSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchAtendimentoSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchAtendimentoSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataAtend(1, qtd_result);
    }
}

function searchAtendimento(page, qtd_result) {
    $('#searchAtend').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchAtend",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/atendimento',
                beforeSend: function() {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">
                                - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                            </th>
                        </tr>
                    `);
                },
                success: function(json) {
                    if(json['status']) {
                        if(!json['empty']) {
                            $('.tbodyProd').html("");
                            for(var i = 0; json['atendimentos'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td class="tdCenter">` + json['atendimentos'][i].nome_usu + `</td>
                                        <td class="tdCenter">` + json['atendimentos'][i].tp_problema + `</td>
                                        <td class="tdCenter">` + json['atendimentos'][i].resp_id + `</td>
                                        <td class="tdCenter">` + json['atendimentos'][i].dataenv_pro + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnView btnViewAtend btnProductConfigAdm" id-atendimento="` + json['atendimentos'][i].id_atd + `"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            viewAtend();
                        } else {
                            $('.tbodyProd').html(`
                                <tr>
                                    <th colspan="5" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                                </tr>
                            `);
                        }
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                            </tr>
                        `);
                    }
                    $('.registShow').html(`
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` mensagens
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchAtendimentoSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchAtendimentoSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchAtendimentoSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchAtendimentoSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataAtend(1, qtd_result);
        }
    });
}

dataAtend(page, qtd_result);
searchAtendimento(1, qtd_result);
ordenarAtendimento(1, qtd_result);
enviaRespAtend();