var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataFunc(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/funcionario',
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
                    for(var i = 0; json['funcionarios'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['funcionarios'][i].funcionario_nome + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].funcionario_cpf + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].funcionario_datanasc + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].funcionario_registro + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].setor_nome + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">- NÃO HÁ FUNCIONÁRIOS CADASTRADOS -</th>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` funcionários
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataFunc(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataFunc(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataFunc(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataFunc(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function insertFuncionario() {
    $('.formInserirFuncionario').submit(function(e) {
        e.preventDefault();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/funcionario',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend() {
                clearErrors();
                $("#btnInsertFuncionario").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Funcionário(s) cadastrado(s) com sucesso!",
                        text: "Deseja continuar cadastrando funcionário(s)?",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Continuar",
                        cancelButtonColor: "#999",
                        cancelButtonText: "Sair"
                    }).then((result) => {
                        if(result.value) {
                            mostraModalAdd();
                        } else {
                            modalAdd.style.display = "none";
                        }
                    });
                    dataFunc(page, qtd_result);
                } else {
                    $("#btnInsertFuncionario").siblings(".help-block").html(json['error']);
                }
            }
        });
        return false;
    });
}

function ordenarFuncSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchFunc').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchFunc').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/funcionario',
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

                for(var i = 0; json['funcionarios'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td>` + json['funcionarios'][i].funcionario_nome + `</td>
                            <td class="tdCenter">` + json['funcionarios'][i].funcionario_cpf + `</td>
                            <td class="tdCenter">` + json['funcionarios'][i].funcionario_datanasc + `</td>
                            <td class="tdCenter">` + json['funcionarios'][i].funcionario_registro + `</td>
                            <td class="tdCenter">` + json['funcionarios'][i].setor_nome + `</td>
                            <td class="tdCenter">
                                <button class="myBtnUpd btnEditFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-times"></i></button>
                            </td>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` funcionários
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarFuncSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarFuncSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarFuncSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarFuncSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarFunc(page, qtd_result) {
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

        if($('#searchFunc').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchFunc').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/funcionario',
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

                    for(var i = 0; json['funcionarios'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['funcionarios'][i].funcionario_nome + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].funcionario_cpf + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].funcionario_datanasc + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].funcionario_registro + `</td>
                                <td class="tdCenter">` + json['funcionarios'][i].setor_nome + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-times"></i></button>
                                </td>
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` funcionários
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarFuncSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarFuncSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarFuncSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarFuncSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchFuncSec(page, qtd_result) {
    if($('#searchFunc').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchFunc",  $('#searchFunc').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/funcionario',
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
                        for(var i = 0; json['funcionarios'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td>` + json['funcionarios'][i].funcionario_nome + `</td>
                                    <td class="tdCenter">` + json['funcionarios'][i].funcionario_cpf + `</td>
                                    <td class="tdCenter">` + json['funcionarios'][i].funcionario_datanasc + `</td>
                                    <td class="tdCenter">` + json['funcionarios'][i].funcionario_registro + `</td>
                                    <td class="tdCenter">` + json['funcionarios'][i].setor_nome + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnUpd btnEditFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` funcionários
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchFuncSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchFuncSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchFuncSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchFuncSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataFunc(1, qtd_result);
    }
}

function searchFunc(page, qtd_result) {
    $('#searchFunc').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchFunc",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/funcionario',
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
                            for(var i = 0; json['funcionarios'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td>` + json['funcionarios'][i].funcionario_nome + `</td>
                                        <td class="tdCenter">` + json['funcionarios'][i].funcionario_cpf + `</td>
                                        <td class="tdCenter">` + json['funcionarios'][i].funcionario_datanasc + `</td>
                                        <td class="tdCenter">` + json['funcionarios'][i].funcionario_registro + `</td>
                                        <td class="tdCenter">` + json['funcionarios'][i].setor_nome + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnUpd btnEditFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelFunc btnProductConfigAdm" id-funcionario="` + json['funcionarios'][i].funcionario_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
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
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` funcionários
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchFuncSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchFuncSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchFuncSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchFuncSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataFunc(1, qtd_result);
        }
    });
}

dataFunc(page, qtd_result);
searchFunc(1, qtd_result);
ordenarFunc(1, qtd_result);