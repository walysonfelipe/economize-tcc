var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataUsuario(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/usuario',
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
                    for(var i = 0; json['usuarios'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['usuarios'][i].usu_nome + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].usu_sexo + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].usu_cidade + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].tpu_usu_nome + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].usu_registro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-eye"></i></button>
                                    <button class="myBtnUpd btnEditUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    insertMarca();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">- NÃO HÁ USUÁRIOS CADASTRADOS -</th>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` usuários
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataUsuario(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataUsuario(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataUsuario(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataUsuario(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function insertMarca() {
    $('.formInserirMarca').submit(function(e) {
        e.preventDefault();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/marca',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend() {
                clearErrors();
                $("#btnInsertMarca").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Marca(s) cadastrada(s) com sucesso!",
                        text: "Deseja continuar cadastrando marca(s)?",
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
                    $("#btnInsertMarca").siblings(".help-block").html(json['error']);
                }
            }
        });
        return false;
    });
}

function ordenarUsuarioSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchUsuario').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchUsuario').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/usuario',
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

                for(var i = 0; json['usuarios'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td>` + json['usuarios'][i].usu_nome + `</td>
                            <td class="tdCenter">` + json['usuarios'][i].usu_sexo + `</td>
                            <td class="tdCenter">` + json['usuarios'][i].usu_cidade + `</td>
                            <td class="tdCenter">` + json['usuarios'][i].tpu_usu_nome + `</td>
                            <td class="tdCenter">` + json['usuarios'][i].usu_registro + `</td>
                            <td class="tdCenter">
                                <button class="myBtnView btnViewUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-eye"></i></button>
                                <button class="myBtnUpd btnEditUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
                insertMarca();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` usuários
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarUsuarioSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarUsuarioSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarUsuarioSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarUsuarioSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarUsuario(page, qtd_result) {
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

        if($('#searchUsuario').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchUsuario').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/usuario',
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

                    for(var i = 0; json['usuarios'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td>` + json['usuarios'][i].usu_nome + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].usu_sexo + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].usu_cidade + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].tpu_usu_nome + `</td>
                                <td class="tdCenter">` + json['usuarios'][i].usu_registro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-eye"></i></button>
                                    <button class="myBtnUpd btnEditUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    insertMarca();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="6" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` usuários
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarUsuarioSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarUsuarioSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarUsuarioSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarUsuarioSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchUsuarioSec(page, qtd_result) {
    if($('#searchUsuario').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchUsuario",  $('#searchUsuario').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/usuario',
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
                        for(var i = 0; json['usuarios'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td>` + json['usuarios'][i].usu_nome + `</td>
                                    <td class="tdCenter">` + json['usuarios'][i].usu_sexo + `</td>
                                    <td class="tdCenter">` + json['usuarios'][i].usu_cidade + `</td>
                                    <td class="tdCenter">` + json['usuarios'][i].tpu_usu_nome + `</td>
                                    <td class="tdCenter">` + json['usuarios'][i].usu_registro + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnView btnViewUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-eye"></i></button>
                                        <button class="myBtnUpd btnEditUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        insertMarca();
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` usuários
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchUsuarioSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchUsuarioSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchUsuarioSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchUsuarioSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataUsuario(1, qtd_result);
    }
}

function searchUsuario(page, qtd_result) {
    $('#searchUsuario').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchUsuario",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/usuario',
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
                            for(var i = 0; json['usuarios'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td>` + json['usuarios'][i].usu_nome + `</td>
                                        <td class="tdCenter">` + json['usuarios'][i].usu_sexo + `</td>
                                        <td class="tdCenter">` + json['usuarios'][i].usu_cidade + `</td>
                                        <td class="tdCenter">` + json['usuarios'][i].tpu_usu_nome + `</td>
                                        <td class="tdCenter">` + json['usuarios'][i].usu_registro + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnView btnViewUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-eye"></i></button>
                                            <button class="myBtnUpd btnEditUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelUsuario btnProductConfigAdm" id-usuario="` + json['usuarios'][i].usu_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            insertMarca();
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
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` usuários
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchUsuarioSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchUsuarioSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchUsuarioSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchUsuarioSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataUsuario(1, qtd_result);
        }
    });
}

dataUsuario(page, qtd_result);
searchUsuario(1, qtd_result);
ordenarUsuario(1, qtd_result);