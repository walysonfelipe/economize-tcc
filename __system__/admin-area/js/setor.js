var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataSetor(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/setor',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="3" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                if(!json['empty']) {
                    $('.tbodyProd').html("");
                    for(var i = 0; json['setores'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['setores'][i].setor_nome + `</td>
                                <td class="tdCenter">` + json['setores'][i].setor_permicao + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="3" class="thNoData">- NÃO HÁ SETORES CADASTRADOS -</th>
                        </tr>
                    `);
                }
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="3" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` setores
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataSetor(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataSetor(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataSetor(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataSetor(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function ordenarSetorSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchSetor').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchSetor').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/setor',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="3" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                $('.tbodyProd').html("");

                for(var i = 0; json['setores'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td>` + json['setores'][i].setor_nome + `</td>
                            <td class="tdCenter">` + json['setores'][i].setor_permicao + `</td>
                            <td class="tdCenter">
                                <button class="myBtnUpd btnEditSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="3" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` setores
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarSetorSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarSetorSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarSetorSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarSetorSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarSetor(page, qtd_result) {
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

        if($('#searchSetor').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchSetor').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/setor',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="3" class="thNoData">
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

                    for(var i = 0; json['setores'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['setores'][i].setor_nome + `</td>
                                <td class="tdCenter">` + json['setores'][i].setor_permicao + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="3" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` setores
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarSetorSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarSetorSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarSetorSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarSetorSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchSetorSec(page, qtd_result) {
    if($('#searchSetor').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchSetor",  $('#searchSetor').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/setor',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="3" class="thNoData">
                            - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                        </th>
                    </tr>
                `);
            },
            success: function(json) {
                if(json['status']) {
                    if(!json['empty']) {
                        $('.tbodyProd').html("");
                        for(var i = 0; json['setores'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td>` + json['setores'][i].setor_nome + `</td>
                                    <td class="tdCenter">` + json['setores'][i].setor_permicao + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnUpd btnEditSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="3" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="3" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` setores
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchSetorSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchSetorSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchSetorSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchSetorSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataSetor(1, qtd_result);
    }
}

function searchSetor(page, qtd_result) {
    $('#searchSetor').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchSetor",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/setor',
                beforeSend: function() {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="3" class="thNoData">
                                - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                            </th>
                        </tr>
                    `);
                },
                success: function(json) {
                    if(json['status']) {
                        if(!json['empty']) {
                            $('.tbodyProd').html("");
                            for(var i = 0; json['setores'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td>` + json['setores'][i].setor_nome + `</td>
                                        <td class="tdCenter">` + json['setores'][i].setor_permicao + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnUpd btnEditSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelSetor btnProductConfigAdm" id-setor="` + json['setores'][i].setor_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                        } else {
                            $('.tbodyProd').html(`
                                <tr>
                                    <th colspan="3" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                                </tr>
                            `);
                        }
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="3" class="thNoData">- OCORREU UM ERRO -</th>
                            </tr>
                        `);
                    }
                    $('.registShow').html(`
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` setores
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchSetorSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchSetorSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchSetorSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchSetorSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataSetor(1, qtd_result);
        }
    });
}

dataSetor(page, qtd_result);
searchSetor(1, qtd_result);
ordenarSetor(1, qtd_result);