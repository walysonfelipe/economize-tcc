var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataEntrega(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/entrega',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="6" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                if(!json['empty']) {
                    $('.tbodyProd').html("");
                    for(var i = 0; json['entregas'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td class="tdCenter">` + json['entregas'][i].compra_hash + `</td>
                                <td class="tdCenter">` + json['entregas'][i].status_id + `</td>
                                <td class="tdCenter">` + json['entregas'][i].entrega_horario + `</td>
                                <td class="tdCenter">` + json['entregas'][i].entrega_cidade + ` - ` + json['entregas'][i].entrega_uf + `</td>
                                <td class="tdCenter">` + json['entregas'][i].armazem_nome + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-eye"></i></button>
                                    <button class="myBtnUpd btnEditEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-edit"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    modalView();
                    modalUpd();
                    viewEntrega();
                    updEntrega();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">- NÃO HÁ ENTREGAS CADASTRADAS -</th>
                        </tr>
                    `);
                }
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` entregas
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataEntrega(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataEntrega(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataEntrega(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataEntrega(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function searchEntregaSec(page, qtd_result) {
    if($('#searchEnt').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchEnt",  $('#searchEnt').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/entrega',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="6" class="thNoData">
                            - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                        </th>
                    </tr>
                `);
            },
            success: function(json) {
                if(json['status']) {
                    if(!json['empty']) {
                        $('.tbodyProd').html("");
                        for(var i = 0; json['entregas'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td class="tdCenter">` + json['entregas'][i].compra_hash + `</td>
                                    <td class="tdCenter">` + json['entregas'][i].status_id + `</td>
                                    <td class="tdCenter">` + json['entregas'][i].entrega_horario + `</td>
                                    <td class="tdCenter">` + json['entregas'][i].entrega_cidade + ` - ` + json['entregas'][i].entrega_uf + `</td>
                                    <td class="tdCenter">` + json['entregas'][i].armazem_nome + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnView btnViewEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-eye"></i></button>
                                        <button class="myBtnUpd btnEditEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-edit"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        modalView();
                        modalUpd();
                        viewEntrega();
                        updEntrega();
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="6" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` entregas
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="dataEntrega(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="dataEntrega(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="dataEntrega(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="dataEntrega(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataEntrega(1, qtd_result);
    }
}

function searchEntrega(page, qtd_result) {
    $('#searchEnt').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchEnt",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/entrega',
                beforeSend: function() {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">
                                - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                            </th>
                        </tr>
                    `);
                },
                success: function(json) {
                    if(json['status']) {
                        if(!json['empty']) {
                            $('.tbodyProd').html("");
                            for(var i = 0; json['entregas'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td class="tdCenter">` + json['entregas'][i].compra_hash + `</td>
                                        <td class="tdCenter">` + json['entregas'][i].status_id + `</td>
                                        <td class="tdCenter">` + json['entregas'][i].entrega_horario + `</td>
                                        <td class="tdCenter">` + json['entregas'][i].entrega_cidade + ` - ` + json['entregas'][i].entrega_uf + `</td>
                                        <td class="tdCenter">` + json['entregas'][i].armazem_nome + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnView btnViewEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-eye"></i></button>
                                            <button class="myBtnUpd btnEditEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            modalView();
                            modalUpd();
                            viewEntrega();
                            updEntrega();
                        } else {
                            $('.tbodyProd').html(`
                                <tr>
                                    <th colspan="6" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                                </tr>
                            `);
                        }
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                            </tr>
                        `);
                    }
                    $('.registShow').html(`
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` entregas
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="dataEntrega(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="dataEntrega(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="dataEntrega(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="dataEntrega(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataEntrega(1, qtd_result);
        }
    });
}

function ordenarEntregaSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchEnt').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchEnt').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/entrega',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="6" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                $('.tbodyProd').html("");
                for(var i = 0; json['entregas'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td class="tdCenter">` + json['entregas'][i].compra_hash + `</td>
                            <td class="tdCenter">` + json['entregas'][i].status_id + `</td>
                            <td class="tdCenter">` + json['entregas'][i].entrega_horario + `</td>
                            <td class="tdCenter">` + json['entregas'][i].entrega_cidade + ` - ` + json['entregas'][i].entrega_uf + `</td>
                            <td class="tdCenter">` + json['entregas'][i].armazem_nome + `</td>
                            <td class="tdCenter">
                                <button class="myBtnView btnViewEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-eye"></i></button>
                                <button class="myBtnUpd btnEditEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                    `);
                }
                modalView();
                modalUpd();
                viewEntrega();
                updEntrega();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` entregas
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarEntregaSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarEntregaSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarEntregaSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarEntregaSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarEntrega(page, qtd_result) {
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

        if($('#searchEnt').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchEnt').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/entrega',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="6" class="thNoData">
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

                    for(var i = 0; json['entregas'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td class="tdCenter">` + json['entregas'][i].compra_hash + `</td>
                                <td class="tdCenter">` + json['entregas'][i].status_id + `</td>
                                <td class="tdCenter">` + json['entregas'][i].entrega_horario + `</td>
                                <td class="tdCenter">` + json['entregas'][i].entrega_cidade + ` - ` + json['entregas'][i].entrega_uf + `</td>
                                <td class="tdCenter">` + json['entregas'][i].armazem_nome + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-eye"></i></button>
                                    <button class="myBtnUpd btnEditEnt btnProductConfigAdm" id-entrega="` + json['entregas'][i].entrega_id + `"><i class="fa fa-edit"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    modalView();
                    modalUpd();
                    viewEntrega();
                    updEntrega();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` entregas
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarEntregaSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarEntregaSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarEntregaSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarEntregaSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function viewEntrega() {
    $(".btnViewEnt").click(function(e) {
        e.preventDefault();
        var dado = "getEnt_id=" + $(this).attr("id-entrega");
        $.ajax({
            dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/entrega',
                beforeSend: function() {
                    $('.showViewModal').html(`
                        <p align="center"><i class='fa fa-circle-notch fa-spin'></i> &nbsp;Buscando dados...</p>
                    `);
                },
                success: function(json) {
                    $('.showViewModal').html(`
                        <div class="modalViewLeft">
                            <div class="headerShowPurch">
                                Código: <b>` + json['compra']['hash'] + `</b><br/>
                                Valor total: <b>R$` + json['compra']['total'] + `</b><br/>
                                Data realizada: <b>` + json['compra']['registro'] + `</b><br/>
                                Usuário: <b>` + json['usuario']['nome'] + `</b><br/>
                                CPF: <b>` + json['usuario']['cpf'] + `</b><br/>
                                Armazém: <b>` + json['compra']['armazem'] + `</b><br/>
                                Status: <b>` + json['compra']['status'] + `</b><br/>
                                Meio de pagamento: <b>` + json['compra']['forma_pag'] + `</b> 
                                <span class="linkPayment"></span><br/>
                                <a href="` + BASE_URL + `usuario/nota-fiscal?compra=` + json['compra']['id'] + `">Gerar PDF</a>
                            </div>

                            <div class="shippingShowPurch">
                                <h3 class="itCart">Endereço de entrega</h3>

                                Agendamento: <b>` + json['end']['horario'] + `</b><br/>
                                <a href="` + BASE_URL4 + `relatorio/entregas-horario?datetime=` + json['end']['horario_sql'] + `">Gerar PDF de todas as entregas deste horário</a><br/>
                                CEP: <b>` + json['end']['cep'] + `</b><br/>
                                Logradouro: <b>` + json['end']['log'] + `, ` + json['end']['num'] + ((json['end']['complemento'] != '') ? ` - ` + json['end']['complemento'] : `` ) + `</b><br/>
                                Bairro: <b>` + json['end']['bairro'] + `</b><br/>
                                Localidade: <b>` + json['end']['cidade'] + ` - ` + json['end']['uf'] + `</b><br/>
                            </div>

                            <div class="funcRemet"></div>
                        </div>
                        <div class="modalViewRight">
                            <div class="cartItems">
                                <h4 class="itCart">Itens do carrinho</h4>
                            </div>
                        </div>
                    `);
                    
                    for(var i = 0; i < json['produto_id'].length; i++) {
                        $('.cartItems').append(`
                            <p class="p_prodCart">
                                entrega: <b>` + json['produto_nome'][i] + `</b><br/>
                                Quantidade: <b>` + json['produto_qtd'][i] + `</b><br/>
                            </p>
                        `);
                    }

                    if(json['funcionario_nome']) {
                        $('.funcRemet').html(`
                            <h3>Remetente</h3>
                        `);
                        for(var i = 0; i < json['funcionario_nome'].length; i++) {
                            $('.funcRemet').append(`
                                <p>
                                    Nome: <b>` + json['funcionario_nome'][i] + `</b><br/>
                                    CPF: <b>` + json['funcionario_cpf'][i] + `</b>
                                </p>
                            `);
                        }
                    }
                }
        });
    });
}

function updEntrega() {
    $(".btnEditEnt").click(function(e) {
        e.preventDefault();
        clearErrors();
        var dado = "updEnt_id=" + $(this).attr("id-entrega");
        var entrega_id = $(this).attr("id-entrega");

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL4 + 'functions/entrega',
            beforeSend: function() {
                $('.entrega_idUpd').val("");
                $('#funcionario_entrega').html("");
                $('.excluiFunc').html('');
            },
            success: function(json) {
                $('.entrega_idUpd').val(entrega_id);
                for(var i = 0; i < json['funcionarios'].length; i++) {
                    $('#funcionario_entrega').append(`
                        <option value="` + json['funcionarios'][i].funcionario_id + `">` + json['funcionarios'][i].funcionario_nome + ` / ` + json['funcionarios'][i].funcionario_cpf + `</option>
                    `);
                }

                $('#status_idUpd').val(json['status_compra']['status_id']).attr("selected", true);

                if(json['funcionario_entrega']) {
                    $('.excluiFunc').append(`
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>REMETENTE(S)</b></td>
                        <td class="funcsEntrega"></td>
                    `);
                    for(var c = 0; c < json['funcionario_entrega'].length; c++) {
                        $('.funcsEntrega').append(`
                            <p class="paragConfigArm">
                                ` + json['funcionario_entrega'][c].funcionario_nome + ` - ` + json['funcionario_entrega'][c].funcionario_cpf +  ` &nbsp;&nbsp;&nbsp;
                                <button class="delFuncEntrega" id-funcionario="` + json['funcionario_entrega'][c].funcionario_id + `" id-entrega="` + json['funcionario_entrega'][c].entrega_id + `" type="button"><i class="far fa-times-circle"></i></button>
                            </p>
                        `);
                    }
                }

                updateEntrega();
            }
        });
    });
}

function updateEntrega() {
    $('.formUpdateEntrega').submit(function(e) {
        e.preventDefault();
        var forEnt = $(this).serialize();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/entrega',
            type: 'POST',
            data: forEnt,
            beforeSend() {
                clearErrors();
                $("#btnUpdateEntrega").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Entregador adicionado com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if(result.value) {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataEntrega(page, qtd_result);
                        } else {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataEntrega(page, qtd_result);
                        }
                    });
                } else {
                    $("#btnUpdateEntrega").siblings(".help-block").html(json['error_del']);
                }
            }
        });
    });

    $('.delFuncEntrega').click(function(e) {
        e.preventDefault();
        var dados = new FormData();
        dados.append("entrega_idDel", $(this).attr("id-entrega"));
        dados.append("funcionario_idDel", $(this).attr("id-funcionario"));

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/entrega',
            type: 'POST',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend() {
                clearErrors();
                $("#btnUpdateEntrega").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Entregador deletado com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if(result.value) {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataEntrega(page, qtd_result);
                        } else {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataEntrega(page, qtd_result);
                        }
                    });
                } else {
                    $("#btnUpdateEntrega").siblings(".help-block").html(json['error']);
                }
            }
        });
    });
}

dataEntrega(page, qtd_result);
searchEntrega(1, qtd_result);
ordenarEntrega(1, qtd_result);