page = 1;
max_links = 3;

const returnBegin = () => {
    $('html, body').animate({scrollTop: 130}, 'slow');
}

const writeProducts = (json = []) => {
    returnBegin()

    if (json['empty']) {
        $('.divShowProdFilter').html(`
            <div class="divTable">
                <center>
                    <img src="${BASE_URL2}style/img/banner/cart.png" class="imgEmptyCart" alt="Não houve resposta para o que você buscou!" title="Não houve resposta para o que você buscou!">
                </center>
            </div>
        `);
    } else {
        var produtos = [];
        for (let i = 0; json['produtos']['data'].length > i; i++) {
            if (json['produtos']['data'][i].produto_desconto_porcent || json['produtos']['data'][i].promo_desconto) {
                produtos[i] = `
                    <div class="prodFilter">
                        <div class='btnFavoriteFilter btnFavorito` + json['produtos']['data'][i].produto_id + `'>
                            <i class="far fa-heart addFavorito" id="` + json['produtos']['data'][i].produto_id + `"></i>
                        </div>
                        <a class="linksProdCarousel" id-produto="` + json['produtos']['data'][i].produto_id + `">
                            <img src='` + BASE_URL2 + `admin-area/img-produtos/` + json['produtos']['data'][i].produto_img + `'/>
                            <p class="divProdPromo">-` + ((json['produtos']['data'][i].promo_desconto != null) ? json['produtos']['data'][i].promo_desconto : json['produtos']['data'][i].produto_desconto_porcent) + `%</p>
                            <div class='divisorFilter'></div>
                            <h5 class='titleProdFilter'>` + json['produtos']['data'][i].produto_nome + ` - `  + json['produtos']['data'][i].produto_tamanho + `</h5>
                            <p class='priceProdFilter'><span class="divProdPrice1">R$` + json['produtos']['data'][i].produto_preco + `</span> R$` + json['produtos']['data'][i].produto_desconto + `</p>
                        </a>
                        <div>
                `;
                if (!json['produtos']['data'][i].empty) {
                    produtos[i] += `
                                <form class="formBuy">
                                    <input type="hidden" value="` + json['produtos']['data'][i].produto_id + `" name="id_prod"/>
                                    <input type="number" min="0" max="20" value="` + json['produtos']['data'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                </form>
                            </div>
                        </div>
                    `;
                } else {
                    produtos[i] += `
                                <span class="esgotQtd">ESGOTADO</span>
                                <form class="formBuy">
                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                </form>
                            </div>
                        </div>
                    `;
                }
            } else {
                produtos[i] = `
                    <div class="prodFilter">
                        <div class='btnFavoriteFilter btnFavorito` + json['produtos']['data'][i].produto_id + `'>
                            <i class="far fa-heart addFavorito" id="` + json['produtos']['data'][i].produto_id + `"></i>
                        </div>
                        <a class="linksProdCarousel" id-produto="` + json['produtos']['data'][i].produto_id + `">
                            <img src='` + BASE_URL2 + `admin-area/img-produtos/` + json['produtos']['data'][i].produto_img + `'/>
                            <div class='divisorFilter'></div>
                            <h5 class='titleProdFilter'>` + json['produtos']['data'][i].produto_nome + ` - `  + json['produtos']['data'][i].produto_tamanho + `</h5>
                            <p class='priceProdFilter'>R$ ` + json['produtos']['data'][i].produto_preco + `</p>
                        </a>
                        <div>
                `;
                if (!json['produtos']['data'][i].empty) {
                    produtos[i] += `
                                <form class="formBuy">
                                    <input type="hidden" value="` + json['produtos']['data'][i].produto_id + `" name="id_prod"/>
                                    <input type="number" min="0" max="20" value="` + json['produtos']['data'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                </form>
                            </div>
                        </div>
                    `;
                } else {
                    produtos[i] += `
                                <span class="esgotQtd">ESGOTADO</span>
                                <form class="formBuy">
                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                </form>
                            </div>
                        </div>
                    `;
                }
            }
        }
        $('.divShowProdFilter').html("");
        for (var i = 0; produtos.length > i; i++) {
            $('.divShowProdFilter').append(produtos[i]);
        }
        attCarrinho();
        btnFavorito();
        abrirModal();

        $('.registShow').html(`
            Mostrando ` + json['produtos']['data'].length + ` de ` + json['produtos']['total'] + ` resultados
        `);

        $('.paginacao').html(`
            <a onclick="dataDeparts(${json['produtos']['pagesUrl'][0]})" href="#" class="btnPaginacao" style="font-size:8pt;"><i class="fas fa-chevron-left"></i></a> 
        `);

        for (let pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
            if (pag_ant >= 1) {
                $('.paginacao').append(`
                    <a onclick="dataDeparts(${json['produtos']['pagesUrl'][(pag_ant - 1)]})" href="#" class="btnPaginacao">${pag_ant}</a> 
                `);
            }
        }

        $('.paginacao').append(`<a href="#" class="btnPaginacaoPage"><b>${page}</b></a>`);

        for (let pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
            if (pag_dep <= json['produtos']['pages']) {
                $('.paginacao').append(`
                    <a onclick="dataDeparts(${json['produtos']['pagesUrl'][(pag_dep - 1)]})" href="#" class="btnPaginacao">${pag_dep}</a> 
                `);
            }
        }

        $('.paginacao').append(`
            <a onclick="dataDeparts(${json['produtos']['pagesUrl'][(json['produtos']['pages'] - 1)]})" href="#" class="btnPaginacao" style="font-size:8pt;"><i class="fas fa-chevron-right"></i></a>
        `);
    }

    if (json['first'] !== false) {
        if (json['first'] === "tamanho") {
            page = 1;
            $('.FilterVol').append(' &nbsp;&nbsp;&nbsp;<span class="limpaVol limpaBusca"><i class="fas fa-minus-square"></i></span>');
            $('.limpaVol').click(function(e) {
                e.preventDefault();
                limpaVol();
            });
        } else if (json['first'] === "marca") {
            page = 1;
            $('.FilterMarca').append(' &nbsp;&nbsp;&nbsp;<span class="limpaMarca limpaBusca"><i class="fas fa-minus-square"></i></span>');
            $('.limpaMarca').click(function(e) {
                e.preventDefault();
                limpaMarca();
            });
        } else if (json['first'] === "preco") {
            page = 1;
            $('.filterPreco').append(' &nbsp;&nbsp;&nbsp;<span class="limpaPreco limpaBusca"><i class="fas fa-minus-square"></i></span>');
            $('.limpaPreco').click(function(e) {
                e.preventDefault();
                limpaPreco();
            });
        } else if (json['first'] === "favorito") {
            page = 1;
            $('.filterFav').append(' &nbsp;&nbsp;&nbsp;<span class="limpaFav limpaBusca"><i class="fas fa-minus-square"></i></span>');
            $('.limpaFav').click(function(e) {
                e.preventDefault();
                limpaFav();
            });
        }
    }
}

function dataDeparts(pageEsc) {
    var dado = "page=" + pageEsc;
    page = pageEsc;

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dado,
        url: BASE_URL + 'functions/filtroPage',
        beforeSend: function() {
            $('.registShow').html(loadingResSmall());
            $('.paginacao').html(loadingResSmall());
            $('.divShowProdFilter').html(loadingRes("Buscando produtos..."));
        },
        success: function(json) {
            writeProducts(json)
        }
    });
}

const limpaVol = () => {
    page = 1;
    $('.FilterVol').html('<i class="fas fa-weight-hanging"></i> VOLUME');

    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/filtroTamanho',
        beforeSend: function() {
            $('.divShowProdFilter').html(loadingRes("Removendo filtro..."));
        },
        success: function(json) {
            writeProducts(json)
        }
    })

    var tam = document.getElementsByClassName('produto_tamanho');
    for (let i = 0; tam.length > i; i++) {
        tam[i].checked = false;
    }
    $(".produto_tamanho option[value='*000*']").removeAttr("disabled");
    $(".produto_tamanho option[value='*000*']").removeAttr("selected");
    $(".produto_tamanho option[value='*000*']").attr("selected", true);
    $(".produto_tamanho option[value='*000*']").attr("disabled", true);
}

const limpaMarca = () => {
    page = 1;
    $('.FilterMarca').html('<i class="fas fa-copyright"></i> MARCA');

    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/filtroMarca',
        beforeSend: function() {
            $('.divShowProdFilter').html(loadingRes("Removendo filtro..."));
        },
        success: function(json) {
            writeProducts(json)
        }
    })

    var marca = document.getElementsByClassName('prod_marca');
    for (let i = 0; marca.length > i; i++) {
        marca[i].checked = false;
    }
    $(".prod_marca option[value='*000*']").removeAttr("disabled");
    $(".prod_marca option[value='*000*']").removeAttr("selected");
    $(".prod_marca option[value='*000*']").attr("selected", true);
    $(".prod_marca option[value='*000*']").attr("disabled", true);
}

const limpaPreco = () => {
    page = 1;
    $('.filterPreco').html('&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO');

    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/filtroPreco',
        beforeSend: function() {
            $('.divShowProdFilter').html(loadingRes("Removendo filtro..."));
        },
        success: function(json) {
            writeProducts(json)
        }
    })

    var preco = document.getElementsByClassName('prod_preco');
    for (let i = 0; preco.length > i; i++) {
        preco[i].checked = false;
    }
    $(".prod_preco option[value='*000*']").removeAttr("disabled");
    $(".prod_preco option[value='*000*']").removeAttr("selected");
    $(".prod_preco option[value='*000*']").attr("selected", true);
    $(".prod_preco option[value='*000*']").attr("disabled", true);
}

const limpaFav = () => {
    page = 1;
    $('.filterFav').html('<i class="fas fa-heart"></i> FAVORITOS');

    $.ajax({
        type: 'post',
        dataType: 'json',
        data: "prod_fav=1",
        url: BASE_URL + 'functions/filtroFavorito',
        beforeSend: function() {
            $('.divShowProdFilter').html(loadingRes("Removendo filtro..."));
        },
        success: function(json) {
            if (json['logado']) {
                writeProducts(json)
            } else {
                Toast.fire({
                    type: 'error',
                    title: 'Você precisa estar logado'
                });
                $("#usu_email_login").val("");
                $("#usu_senha_login").val("");
                $(".help-block-login").html("");
                var favMobile = document.getElementById('fav_radio');
                var fav = document.getElementById('fav_rad');
                favMobile.checked = false;
                fav.checked = false;
                modal.style.display = "block";
            }
        }
    })

    var fav = document.getElementsByClassName('prod_fav');
    for (let i = 0; fav.length > i; i++) {
        fav[i].checked = false;
    }
}

$(document).ready(function() {
    $(".categ").change(function() {
        var href = $(this).val();
        var local = location;
        local = local + "";

        if (local.indexOf('#') != -1) {
            var local = local.substring(0, (local.length - 1));
        }

        window.location = local + '/' + href;
    });


    $('.produto_tamanho').change(function(e) {
        e.preventDefault();
        page = 1;

        var dado = "produto_tamanho=" + $(this).val();
        var url = BASE_URL + 'functions/filtroTamanho';
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: dado,
            url: url,
            beforeSend: function() {
                $('.divShowProdFilter').html(loadingRes(" Buscando..."));
            },
            success: function(json) {
                writeProducts(json)
            }
        });
    });

    $('.prod_marca').change(function(e) {
        e.preventDefault();
        page = 1;

        var dado = "produto_marca=" + $(this).val();
        var url = BASE_URL + 'functions/filtroMarca';
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: dado,
            url: url,
            beforeSend: function() {
                $('.divShowProdFilter').html(loadingRes(" Buscando..."));
            },
            success: function(json) {
                writeProducts(json)
            }
        });
    });

    $('.prod_preco').change(function(e) {
        e.preventDefault();
        page = 1;

        var dado = "produto_preco=" + $(this).val();
        var url = BASE_URL + 'functions/filtroPreco';
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: dado,
            url: url,
            beforeSend: function() {
                $('.divShowProdFilter').html(loadingRes(" Buscando..."));
            },
            success: function(json) {
                writeProducts(json)
            }
        });
    });

    $('.prod_fav').change(function(e) {
        e.preventDefault();
        page = 1;

        var dado = "produto_fav=" + $(this).val();
        var url = BASE_URL + 'functions/filtroFavorito';
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: dado,
            url: url,
            beforeSend: function() {
                $('.divShowProdFilter').html(loadingRes(" Buscando..."));
            },
            success: function(json) {
                if (json['logado']) {
                    writeProducts(json)
                } else {
                    Toast.fire({
                        type: 'error',
                        title: 'Você precisa estar logado'
                    });
                    $("#usu_email_login").val("");
                    $("#usu_senha_login").val("");
                    $(".help-block-login").html("");
                    var favMobile = document.getElementById('fav_radio');
                    var fav = document.getElementById('fav_rad');
                    favMobile.checked = false;
                    fav.checked = false;
                    modal.style.display = "block";
                }
            }
        });
    });
});

$('.limpaVol').click(function(e) {
    e.preventDefault();
    limpaVol();
});
$('.limpaMarca').click(function(e) {
    e.preventDefault();
    limpaMarca();
});
$('.limpaPreco').click(function(e) {
    e.preventDefault();
    limpaPreco();
});
$('.limpaFav').click(function(e) {
    e.preventDefault();
    limpaFav();
});