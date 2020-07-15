var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataPost(page, qtd_result) {
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
        url: BASE_URL4 + 'functions/postagem',
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
                    for(var i = 0; json['postagens'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td><img class="imgProd" style="width:100%;" src="` + BASE_URL2 + `img/postagem/` + json['postagens'][i].post_img + `"/></td>
                                <td class="tdCenter">` + json['postagens'][i].post_title + `</td>
                                <td class="tdCenter">` + json['postagens'][i].post_text + `</td>
                                <td class="tdCenter">` + json['postagens'][i].post_registro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-eye"></i></button>
                                    <button class="myBtnUpd btnEditPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    deleteProduto();
                    // modalView();
                    modalUpd();
                    viewProduto();
                    updProduto();
                    uploadImg();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- NÃO HÁ POSTAGENS CADASTRADOS -</th>
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
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` postagens
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataPost(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataPost(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataPost(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataPost(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function searchPostSec(page, qtd_result) {
    if($('#searchPost').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchPost",  $('#searchPost').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/postagem',
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
                        for(var i = 0; json['postagens'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td><img class="imgProd" style="width:100%;" src="` + BASE_URL2 + `img/postagem/` + json['postagens'][i].post_img + `"/></td>
                                    <td class="tdCenter">` + json['postagens'][i].post_title + `</td>
                                    <td class="tdCenter">` + json['postagens'][i].post_text + `</td>
                                    <td class="tdCenter">` + json['postagens'][i].post_registro + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnView btnViewPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-eye"></i></button>
                                        <button class="myBtnUpd btnEditPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        deleteProduto();
                        // modalView();
                        modalUpd();
                        viewProduto();
                        updProduto();
                        uploadImg();
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
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` postagens
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchPostSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchPostSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchPostSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchPostSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataPost(1, qtd_result);
    }
}

function searchPost(page, qtd_result) {
    $('#searchPost').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchPost",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/postagem',
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
                            for(var i = 0; json['postagens'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td><img class="imgProd" style="width:100%;" src="` + BASE_URL2 + `img/postagem/` + json['postagens'][i].post_img + `"/></td>
                                        <td class="tdCenter">` + json['postagens'][i].post_title + `</td>
                                        <td class="tdCenter">` + json['postagens'][i].post_text + `</td>
                                        <td class="tdCenter">` + json['postagens'][i].post_registro + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnView btnViewPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-eye"></i></button>
                                            <button class="myBtnUpd btnEditPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            deleteProduto();
                            // modalView();
                            modalUpd();
                            viewProduto();
                            updProduto();
                            uploadImg();
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
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` postagens
                    `);
    
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchPostSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchPostSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchPostSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchPostSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataPost(1, qtd_result);
        }
    });
}

function ordenarPostSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchPost').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchPost').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/postagem',
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

                for(var i = 0; json['postagens'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td><img class="imgProd" style="width:100%;" src="` + BASE_URL2 + `img/postagem/` + json['postagens'][i].post_img + `"/></td>
                            <td class="tdCenter">` + json['postagens'][i].post_title + `</td>
                            <td class="tdCenter">` + json['postagens'][i].post_text + `</td>
                            <td class="tdCenter">` + json['postagens'][i].post_registro + `</td>
                            <td class="tdCenter">
                                <button class="myBtnView btnViewPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-eye"></i></button>
                                <button class="myBtnUpd btnEditPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
                deleteProduto();
                // modalView();
                modalUpd();
                viewProduto();
                updProduto();
                uploadImg();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` postagens
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarPostSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarPostSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarPostSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarPostSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarPost(page, qtd_result) {
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

        if($('#searchPost').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchPost').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/postagem',
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

                    for(var i = 0; json['postagens'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td><img class="imgProd" style="width:100%;" src="` + BASE_URL2 + `img/postagem/` + json['postagens'][i].post_img + `"/></td>
                                <td class="tdCenter">` + json['postagens'][i].post_title + `</td>
                                <td class="tdCenter">` + json['postagens'][i].post_text + `</td>
                                <td class="tdCenter">` + json['postagens'][i].post_registro + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnView btnViewPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-eye"></i></button>
                                    <button class="myBtnUpd btnEditPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelPost btnProductConfigAdm" id-postagem="` + json['postagens'][i].post_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    deleteProduto();
                    // modalView();
                    modalUpd();
                    viewProduto();
                    updProduto();
                    uploadImg();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` postagens
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarPostSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarPostSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarPostSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarPostSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function viewProduto() {
    $(".btnViewProd").click(function(e) {
        e.preventDefault();
        var dado = "getProd_id=" + $(this).attr("id-produto");
        $.ajax({
            dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/produto',
                beforeSend: function() {
                    $('.showViewModal').html(`
                        <p align="center"><i class='fa fa-circle-notch fa-spin'></i> &nbsp;Buscando dados...</p>
                    `);
                },
                success: function(json) {
                    $('.showViewModal').html(`
                        <div class="modalViewLeft">
                            <img class="imgProdModal" src="` + BASE_URL3 + json['produto']['produto_img'] + `"/>
                        </div>
                        <div class="modalViewRight">
                            <div class="infView">
                                <span class="marcaProdView">` + json['produto']['marca_nome'] + `</span>
                                <h2 class="nomeProdView">
                                    ` + json['produto']['produto_nome'] + `<br/>
                                    <span class="volProdView">` + json['produto']['produto_tamanho'] + `</span>
                                </h2>
                            </div>
                            <div class="categView">
                                <p class="categProdView">
                                    ` + json['produto']['depart_nome'] + ` / 
                                    ` + json['produto']['subcateg_nome'] + ` / 
                                    ` + json['produto']['categ_nome'] + `
                                </p>
                            </div>
                            <div class="descView">
                                <h4 class="descTitleView">Descrição:</h4>
                                <p>
                                    ` + json['produto']['produto_descricao'] + `
                                </p>
                            </div>
                        </div>
                    `);
                }
        });
    });
}

function updProduto() {
    $(".btnEditProd").click(function(e) {
        e.preventDefault();
        clearErrors();
        var dado = "updProd_id=" + $(this).attr("id-produto");
        $.ajax({
            dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/produto',
                beforeSend: function() {
                    $('#prod_idUpd').val("");
                    $('#prod_nomeUpd').val("");
                    $('#prod_marcaUpd').html("");
                    $('#prod_categUpd').html("");
                    $('#prod_descUpd').val("");
                    $('#prod_tamUpd').val("");
                },
                success: function(json) {
                    $('#prod_idUpd').val(json['produto']['produto_id']);
                    $('#prod_nomeUpd').val(json['produto']['produto_nome']);
                    $('#prod_marcaUpd').html(`
                        <option value="*000*"> -- Selecione a marca: --</option>
                    `);
                    for(var i = 0; i < json['marca_prod'].length; i++) {
                        if(json['marca_prod'][i].marca_id != json['produto']['produto_marca']) {
                            $('#prod_marcaUpd').append(`
                                <option value="` + json['marca_prod'][i].marca_id + `">` + json['marca_prod'][i].marca_nome + `</option>
                            `);
                        } else {
                            $('#prod_marcaUpd').append(`
                                <option selected value="` + json['marca_prod'][i].marca_id + `">` + json['marca_prod'][i].marca_nome + `</option>
                            `);
                        }
                    }

                    $('#prod_categUpd').html(`
                        <option value="*000*"> -- Selecione a categoria: --</option>
                    `);
                    for(var i = 0; i < json['categ_prod'].length; i++) {
                        if(json['categ_prod'][i].categ_id != json['produto']['produto_categ']) {
                            $('#prod_categUpd').append(`
                                <option value="` + json['categ_prod'][i].categ_id + `">` + json['categ_prod'][i].depart_nome + ` / ` + json['categ_prod'][i].subcateg_nome + ` / ` + json['categ_prod'][i].categ_nome + `</option>
                            `);
                        } else {
                            $('#prod_categUpd').append(`
                                <option selected value="` + json['categ_prod'][i].categ_id + `">` + json['categ_prod'][i].depart_nome + ` / ` + json['categ_prod'][i].subcateg_nome + ` / ` + json['categ_prod'][i].categ_nome + `</option>
                            `);
                        }
                    }

                    $('.imgUpload').attr("src", BASE_URL3 + json['produto']['produto_img']);

                    $('#prod_descUpd').val(json['produto']['produto_descricao']);
                    $('#prod_tamUpd').val(json['produto']['produto_tamanho']);

                    updateProduto();
                }
        });
    });
}

function insertPost() {
    $('.formInserirPost').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/postagem',
            type: 'POST',
            data: formData,
            beforeSend() {
                clearErrors();
                $("#btnInsertPost").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    dataPost(page, qtd_result);
                    Swal.fire({
                        title: "Postagem(ns) cadastrada(s) com sucesso!",
                        text: "Deseja continuar cadastrando postagem(ns)?",
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
                    $("#btnInsertPost").siblings(".help-block").html(json['error']);
                }
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function () {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
            return myXhr;
            }
        });
    });
}

function uploadImg() {
    $("input[type=file]").on("change", function(e){
        e.preventDefault();
        var input = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return;

        if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);

            reader.onload = function() {
                input.siblings(".imgUpload").attr('src', this.result);
            }
        }
    });
}

function updateProduto() {
    $('.formUpdateProdutos').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/produto',
            type: 'POST',
            data: formData,
            beforeSend() {
                clearErrors();
                $("#btnUpdateProduto").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Produto editado com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if(result.value) {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataPost(page, qtd_result);
                        } else {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataPost(page, qtd_result);
                        }
                    });
                } else {
                    $("#btnUpdateProduto").siblings(".help-block").html(json['error']);
                }
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function () {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
            return myXhr;
            }
        });
    });
}

function deleteProduto() {
    $('.btnDelProd').click(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: "Deseja mesmo excuir este produto?",
            text: "Uma vez feito, não haverá volta! (Qualquer relação que há com esse produto, será também deletado)",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#A94442",
            confirmButtonText: "Sim, excluir"
        }).then((result) => {
            if(result.value) {
                var dado = "delProd_id=" + $(this).attr("id-produto");
                $.ajax({
                    dataType: 'json',
                    url: BASE_URL4 + 'functions/produto',
                    type: 'POST',
                    data: dado,
                    success: function(json) {
                        if(json['status']) {
                            Swal.fire({
                                title: "Produto excluido com sucesso!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#494949",
                                confirmButtonText: "Ok"
                            });
                            dataPost(page, qtd_result);
                        } else {
                            Swal.fire({
                                title: "Ocorreu um erro ao excluir produto!",
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

dataPost(page, qtd_result);
searchPost(1, qtd_result);
ordenarPost(1, qtd_result);