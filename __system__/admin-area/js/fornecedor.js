var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataFornecedor(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/fornecedor',
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
                    for(var i = 0; json['fornecedores'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['fornecedores'][i].fornecedor_nome + `</td>
                                <td class="tdCenter">` + json['fornecedores'][i].fornecedor_responsavel_nome + `</td>
                                <td class="tdCenter">` + json['fornecedores'][i].fornecedor_cnpj + `</td>
                                <td class="tdCenter">` + json['fornecedores'][i].fornecedor_data_registro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    
                    deleteFornecedor();
                    modalUpd();
                    updFornecedor();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- NÃO HÁ FORNECEDORES CADASTRADOS -</th>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` fornecedores
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataFornecedor(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataFornecedor(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataFornecedor(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataFornecedor(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function updFornecedor() {
    $(".btnEditFornecedor").click(function(e) {
        e.preventDefault();
        clearErrors();
        var dado = "updFornecedor_id=" + $(this).attr("id-fornecedor");
        $.ajax({
            dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/fornecedor',
                beforeSend: function() {
                    $('#funcionario_idUpd').val("");
                    $('#fornecedor_nomeUpd').val("");
                    $('#fornecedor_responsavel_nomeUpd').val("");
                    $('#fornecedor_cnpjUpd').val("");
                },
                success: function(json) {
                    $('#funcionario_idUpd').val(json['fornecedor']['funcionario_id']);

                    $('#fornecedor_nomeUpd').val(json['fornecedor']['fornecedor_nome']);

                    $('#fornecedor_responsavel_nomeUpd').val(json['fornecedor']['fornecedor_responsavel_nome']);

                    $('#fornecedor_cnpjUpd').val(json['fornecedor']['fornecedor_cnpj']);
                    updateFornecedor();
                }
        });
    });
}

function updateFornecedor() {
    $('.formUpdateFornecedor').submit(function(e) {
        e.preventDefault();
        var formFornecedor = $(this).serialize();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/fornecedor',
            type: 'POST',
            data: formFornecedor,
            beforeSend() {
                clearErrors();
                $("#btnUpdateFornecedor").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Fornecedor editado com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if(result.value) {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataFornecedor(page, qtd_result);
                        } else {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataFornecedor(page, qtd_result);
                        }
                    });
                } else {
                    $("#btnUpdateFornecedor").siblings(".help-block").html(json['error']);
                }
            }
        });
    });
}

function insertFornecedor() {
    $('.formInserirFornecedor').submit(function(e) {
        e.preventDefault();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/fornecedor',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend() {
                clearErrors();
                $("#btnInsertFornecedor").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Fornecedor(es) cadastrado(s) com sucesso!",
                        text: "Deseja continuar cadastrando fornecedor(es)?",
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
                    dataFornecedor(page, qtd_result);
                } else {
                    $("#btnInsertFornecedor").siblings(".help-block").html(json['error']);
                }
            }
        });
        return false;
    });
}

function deleteFornecedor() {
    $('.btnDelFornecedor').click(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: "Deseja mesmo excuir este fornecedor?",
            text: "Uma vez feito, não haverá volta! (Qualquer relação que há com este fornecedor, será perdida)",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#A94442",
            confirmButtonText: "Sim, excluir"
        }).then((result) => {
            if(result.value) {
                var dado = "delFornecedor_id=" + $(this).attr("id-fornecedor");
                $.ajax({
                    dataType: 'json',
                    url: BASE_URL4 + 'functions/fornecedor',
                    type: 'POST',
                    data: dado,
                    success: function(json) {
                        if(json['status']) {
                            Swal.fire({
                                title: "Fornecedor excluido com sucesso!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#494949",
                                confirmButtonText: "Ok"
                            });
                            dataFornecedor(page, qtd_result);
                        } else {
                            Swal.fire({
                                title: "Ocorreu um erro ao excluir fornecedor!",
                                text: json['error_del'],
                                type: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#494949",
                                confirmButtonText: "Ok"
                            });
                        }
                    }
                });
            }
        });
    });
}

function ordenarFornecedorSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchFornecedor').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchFornecedor').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/fornecedor',
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

                for(var i = 0; json['fornecedores'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td>` + json['fornecedores'][i].fornecedor_nome + `</td>
                            <td class="tdCenter">` + json['fornecedores'][i].fornecedor_responsavel_nome + `</td>
                            <td class="tdCenter">` + json['fornecedores'][i].fornecedor_cnpj + `</td>
                            <td class="tdCenter">` + json['fornecedores'][i].fornecedor_data_registro + `</td>
                            <td class="tdCenter">
                                <button class="myBtnUpd btnEditFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
                
                deleteFornecedor();
                modalUpd();
                updFornecedor();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` fornecedores
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarFornecedorSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarFornecedorSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarFornecedorSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarFornecedorSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarFornecedor(page, qtd_result) {
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

        if($('#searchFornecedor').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchFornecedor').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/fornecedor',
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

                    for(var i = 0; json['fornecedores'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['fornecedores'][i].fornecedor_nome + `</td>
                                <td class="tdCenter">` + json['fornecedores'][i].fornecedor_responsavel_nome + `</td>
                                <td class="tdCenter">` + json['fornecedores'][i].fornecedor_cnpj + `</td>
                                <td class="tdCenter">` + json['fornecedores'][i].fornecedor_data_registro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    
                    deleteFornecedor();
                    modalUpd();
                    updFornecedor();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` fornecedores
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarFornecedorSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarFornecedorSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarFornecedorSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarFornecedorSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchFornecedorSec(page, qtd_result) {
    if($('#searchFornecedor').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchFornecedor",  $('#searchFornecedor').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/fornecedor',
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
                        for(var i = 0; json['fornecedores'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td>` + json['fornecedores'][i].fornecedor_nome + `</td>
                                    <td class="tdCenter">` + json['fornecedores'][i].fornecedor_responsavel_nome + `</td>
                                    <td class="tdCenter">` + json['fornecedores'][i].fornecedor_cnpj + `</td>
                                    <td class="tdCenter">` + json['fornecedores'][i].fornecedor_data_registro + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnUpd btnEditFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        
                        deleteFornecedor();
                        modalUpd();
                        updFornecedor();
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` fornecedores
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchFornecedorSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchFornecedorSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchFornecedorSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchFornecedorSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataFornecedor(1, qtd_result);
    }
}

function searchFornecedor(page, qtd_result) {
    $('#searchFornecedor').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchFornecedor",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/fornecedor',
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
                            for(var i = 0; json['fornecedores'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td>` + json['fornecedores'][i].fornecedor_nome + `</td>
                                        <td class="tdCenter">` + json['fornecedores'][i].fornecedor_responsavel_nome + `</td>
                                        <td class="tdCenter">` + json['fornecedores'][i].fornecedor_cnpj + `</td>
                                        <td class="tdCenter">` + json['fornecedores'][i].fornecedor_data_registro + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnUpd btnEditFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelFornecedor btnProductConfigAdm" id-fornecedor="` + json['fornecedores'][i].fornecedor_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            
                            deleteFornecedor();
                            modalUpd();
                            updFornecedor();
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
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` fornecedores
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchFornecedorSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchFornecedorSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchFornecedorSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchFornecedorSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataFornecedor(1, qtd_result);
        }
    });
}

dataFornecedor(page, qtd_result);
searchFornecedor(1, qtd_result);
ordenarFornecedor(1, qtd_result);