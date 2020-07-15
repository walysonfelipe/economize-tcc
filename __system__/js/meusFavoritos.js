function meusFavoritos() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/meusFavoritos',
        success: function(json) {
            if(json['logado']) {
                if(json['produtos'].length > 0) {
                    var produtos = [];
                    for (var i = 0; json['produtos'].length > i; i++) {
                        if(json['produtos'][i].produto_desconto_porcent || json['produtos'][i].promo_desconto) {
                            produtos[i] = `
                                <div class="prodFilter">
                                    <div class='btnFavoriteFilter btnFavorito` + json['produtos'][i].produto_id + `'>
                                        
                                    </div>
                                    <a class="linksProdCarousel" id-produto="` + json['produtos'][i].produto_id + `">
                                        <img src='` + BASE_URL2 + `admin-area/img-produtos/` + json['produtos'][i].produto_img + `'/>
                                        <p class="divProdPromo">-` + ((json['produtos'][i].promo_desconto != null) ? json['produtos'][i].promo_desconto : json['produtos'][i].produto_desconto_porcent) + `%</p>
                                        <div class='divisorFilter'></div>
                                        <h5 class='titleProdFilter'>` + json['produtos'][i].produto_nome + ` - `  + json['produtos'][i].produto_tamanho + `</h5>
                                        <p class='priceProdFilter'><span class="divProdPrice1">R$` + json['produtos'][i].produto_preco + `</span> R$` + json['produtos'][i].produto_desconto + `</p>
                                    </a>
                                    <div>
                                        <form class="formBuy">
                                            <input type="hidden" value="` + json['produtos'][i].produto_id + `" name="id_prod"/>
                                            <input type="number" min="0" max="20" value="` + json['produtos'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                            <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                        </form>
                                    </div>
                                </div>
                            `;
                        } else {
                            produtos[i] = `
                                <div class="prodFilter">
                                    <div class='btnFavoriteFilter btnFavorito` + json['produtos'][i].produto_id + `'>
                                        
                                    </div>
                                    <img src='` + BASE_URL2 + `admin-area/img-produtos/` + json['produtos'][i].produto_img + `'/>
                                    <div class='divisorFilter'></div>
                                    <h5 class='titleProdFilter'>` + json['produtos'][i].produto_nome + ` - `  + json['produtos'][i].produto_tamanho + `</h5>
                                    <p class='priceProdFilter'>R$ ` + json['produtos'][i].produto_preco + `</p>
                                    <div>
                                        <form class="formBuy">
                                            <input type="hidden" value="` + json['produtos'][i].produto_id + `" name="id_prod"/>
                                            <input type="number" min="0" max="20" value="` + json['produtos'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                            <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                        </form>
                                    </div>
                                </div>
                            `;
                        }
                    }
                    $('.l-favoritos').html("");
                    for(c = 0; produtos.length > c; c++) {
                        $('.l-favoritos').append(produtos[c]);
                    }
                } else {
                    $('.l-favoritos').html(`
                        <div class="msgNoProds">
                            Você não tem produtos favoritados, por enquanto...
                        </div>
                    `);
                }
            }

            btnFavorito();
            attCarrinho();
            abrirModal();
        }
    });
}

meusFavoritos();