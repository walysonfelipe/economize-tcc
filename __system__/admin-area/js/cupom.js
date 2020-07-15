var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataCupons(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/cupom',
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
                    for(var i = 0; json['cupons'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['cupons'][i].cupom_codigo + `</td>
                                <td class="tdCenter">` + json['cupons'][i].cupom_desconto_porcent + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    deleteCupom();
                    modalUpd();
                    updCupom();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- NÃO HÁ CUPONS CADASTRADOS -</th>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` cupons
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataCupons(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataCupons(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataCupons(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataCupons(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function updCupom() {
    $(".btnEditCupom").click(function(e) {
        e.preventDefault();
        clearErrors();
        var dado = "updCupom_id=" + $(this).attr("id-cupom");
        $.ajax({
            dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/cupom',
                beforeSend: function() {
                    $('#cupom_idUpd').val("");
                    $('#cupom_codigoUpd').val("");
                    $('#cupom_desconto_porcentUpd').val("");
                },
                success: function(json) {
                    $('#cupom_idUpd').val(json['cupom']['cupom_id']);

                    $('#cupom_codigoUpd').val(json['cupom']['cupom_codigo']);

                    $('#cupom_desconto_porcentUpd').val(json['cupom']['cupom_desconto_porcent']);
                    updateCupom();
                }
        });
    });
}

function updateCupom() {
    $('.formUpdateCupom').submit(function(e) {
        e.preventDefault();
        var formCupom = $(this).serialize();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/cupom',
            type: 'POST',
            data: formCupom,
            beforeSend() {
                clearErrors();
                $("#btnUpdateCupom").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Cupom editado com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if(result.value) {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataCupons(page, qtd_result);
                        } else {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataCupons(page, qtd_result);
                        }
                    });
                } else {
                    $("#btnUpdateCupom").siblings(".help-block").html(json['error']);
                }
            }
        });
    });
}

function insertCupom() {
    $('.formInserirCupom').submit(function(e) {
        e.preventDefault();
        var formCupom = $(this).serialize();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/cupom',
            type: 'POST',
            data: formCupom,
            beforeSend() {
                clearErrors();
                $("#btnInsertCupom").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                dataCupons(page, qtd_result);
                
                if(json['status']) {
                    Swal.fire({
                        title: "Cupom(ns) cadastrado(s) com sucesso!",
                        text: "Deseja continuar cadastrando Cupom(ns)?",
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
                } else {
                    $("#btnInsertCupom").siblings(".help-block").html(json['error']);
                }
            }
        });
    });
}

function deleteCupom() {
    $('.btnDelCupom').click(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: "Deseja mesmo excuir este cupom?",
            text: "Uma vez feito, não haverá volta!",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#A94442",
            confirmButtonText: "Sim, excluir"
        }).then((result) => {
            if(result.value) {
                var dado = "delCupom_id=" + $(this).attr("id-cupom");
                $.ajax({
                    dataType: 'json',
                    url: BASE_URL4 + 'functions/cupom',
                    type: 'POST',
                    data: dado,
                    success: function(json) {
                        if(json['status']) {
                            Swal.fire({
                                title: "Cupom excluido com sucesso!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#494949",
                                confirmButtonText: "Ok"
                            });
                            dataCupons(page, qtd_result);
                        } else {
                            Swal.fire({
                                title: "Ocorreu um erro ao excluir cupom!",
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

function ordenarCuponsSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchCupom').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchCupom').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/cupom',
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

                for(var i = 0; json['cupons'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td>` + json['cupons'][i].cupom_codigo + `</td>
                            <td class="tdCenter">` + json['cupons'][i].cupom_desconto_porcent + `</td>
                            <td class="tdCenter">
                                <button class="myBtnUpd btnEditCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
                deleteCupom();
                modalUpd();
                updCupom();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` cupons
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarCuponsSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarCuponsSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarCuponsSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarCuponsSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarCupons(page, qtd_result) {
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

        if($('#searchCupom').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchCupom').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/cupom',
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

                    for(var i = 0; json['cupons'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['cupons'][i].cupom_codigo + `</td>
                                <td class="tdCenter">` + json['cupons'][i].cupom_desconto_porcent + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    deleteCupom();
                    modalUpd();
                    updCupom();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` cupons
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarCuponsSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarCuponsSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarCuponsSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarCuponsSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchCuponsSec(page, qtd_result) {
    if($('#searchCupom').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchCupom",  $('#searchCupom').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/cupom',
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
                        for(var i = 0; json['cupons'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td>` + json['cupons'][i].cupom_codigo + `</td>
                                    <td class="tdCenter">` + json['cupons'][i].cupom_desconto_porcent + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnUpd btnEditCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        deleteCupom();
                        modalUpd();
                        updCupom();
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` cupons
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchCuponsSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchCuponsSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchCuponsSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchCuponsSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataCupons(1, qtd_result);
    }
}

function searchCupons(page, qtd_result) {
    $('#searchCupom').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchCupom",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/cupom',
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
                            for(var i = 0; json['cupons'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td>` + json['cupons'][i].cupom_codigo + `</td>
                                        <td class="tdCenter">` + json['cupons'][i].cupom_desconto_porcent + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnUpd btnEditCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelCupom btnProductConfigAdm" id-cupom="` + json['cupons'][i].cupom_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            deleteCupom();
                            modalUpd();
                            updCupom();
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
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` cupons
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchCuponsSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchCuponsSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchCuponsSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchCuponsSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataCupons(1, qtd_result);
        }
    });
}

dataCupons(page, qtd_result);
searchCupons(1, qtd_result);
ordenarCupons(1, qtd_result);