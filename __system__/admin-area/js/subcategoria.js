var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataSubcateg(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/subcategoria',
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
                    for(var i = 0; json['subcategorias'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['subcategorias'][i].subcateg_nome + `</td>
                                <td class="tdCenter">` + json['subcategorias'][i].depart_nome + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    insertSubcateg();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- NÃO HÁ SUBCATEGORIAS CADASTRADAS -</th>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` subcategorias
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataSubcateg(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataSubcateg(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataSubcateg(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataSubcateg(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function insertSubcateg() {
    $('.formInserirSubcateg').submit(function(e) {
        e.preventDefault();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/subcategoria',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend() {
                clearErrors();
                $("#btnInsertSubcateg").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Subcategoria(s) cadastrada(s) com sucesso!",
                        text: "Deseja continuar cadastrando subcategoria(s)?",
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
                    $("#btnInsertSubcateg").siblings(".help-block").html(json['error']);
                }
            }
        });
        return false;
    });
}

function ordenarSubcategSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchSubcateg').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchSubcateg').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/subcategoria',
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

                for(var i = 0; json['subcategorias'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td>` + json['subcategorias'][i].subcateg_nome + `</td>
                            <td class="tdCenter">` + json['subcategorias'][i].depart_nome + `</td>
                            <td class="tdCenter">
                                <button class="myBtnUpd btnEditSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
                insertSubcateg();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` subcategorias
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarSubcategSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarSubcategSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarSubcategSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarSubcategSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarSubcateg(page, qtd_result) {
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

        if($('#searchSubcateg').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchSubcateg').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/subcategoria',
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

                    for(var i = 0; json['subcategorias'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['subcategorias'][i].subcateg_nome + `</td>
                                <td class="tdCenter">` + json['subcategorias'][i].depart_nome + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    insertSubcateg();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` subcategorias
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarSubcategSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarSubcategSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarSubcategSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarSubcategSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchSubcategSec(page, qtd_result) {
    if($('#searchSubcateg').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchSubcateg",  $('#searchSubcateg').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/subcategoria',
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
                        for(var i = 0; json['subcategorias'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td>` + json['subcategorias'][i].subcateg_nome + `</td>
                                    <td class="tdCenter">` + json['subcategorias'][i].depart_nome + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnUpd btnEditSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        insertSubcateg();
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` subcategorias
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchSubcategSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchSubcategSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchSubcategSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchSubcategSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataSubcateg(1, qtd_result);
    }
}

function searchSubcateg(page, qtd_result) {
    $('#searchSubcateg').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchSubcateg",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/subcategoria',
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
                            for(var i = 0; json['subcategorias'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td>` + json['subcategorias'][i].subcateg_nome + `</td>
                                        <td class="tdCenter">` + json['subcategorias'][i].depart_nome + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnUpd btnEditSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelSubcateg btnProductConfigAdm" id-subcategoria="` + json['subcategorias'][i].subcateg_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            insertSubcateg();
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
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` subcategorias
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchSubcategSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchSubcategSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchSubcategSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchSubcategSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataSubcateg(1, qtd_result);
        }
    });
}

dataSubcateg(page, qtd_result);
searchSubcateg(1, qtd_result);
ordenarSubcateg(1, qtd_result);