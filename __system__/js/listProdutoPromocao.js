$(function() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/listProdutoPromocao',
        success: function(response) {
            if(response['status']) {
                var produtos = [];
                for (var i = 0; response['produtos'].length > i; i++) {
                    if(!response['produtos'][i].empty) {
                        produtos[i] = `
                            <div class="divProdCarousel">
                                <div class="btnFavorito` + response['produtos'][i].produto_id + `"></div>
                                <a class="linksProdCarousel" id-produto="` + response['produtos'][i].produto_id + `">
                                    <img class="divProdImg" src="` + BASE_URL3 + response['produtos'][i].produto_img + `">
                                    <div class='divisorFilterCar'></div>
                                    <p class="divProdPromo">-` + response['produtos'][i].produto_desconto_porcent + `%</p>
                                    <h4 class="divProdTitle">` + response['produtos'][i].produto_nome + ` - ` + response['produtos'][i].produto_tamanho + `</h4>
                                    <p class="divProdPrice"><span class="divProdPrice1">R$` + response['produtos'][i].produto_preco + `</span> R$` + response['produtos'][i].produto_desconto + `</p>
                                </a>
                                <div>
                                    <form class="formBuy">
                                        <input type="hidden" value="` + response['produtos'][i].produto_id + `" name="id_prod"/>
                                        <div class="quantity">
                                            <input type="number" min="0" max="20" value="` + response['produtos'][i].carrinho + `" class="inputQtd inputBuy` + response['produtos'][i].produto_id + `" name="qtd_prod"/>
                                        </div>
                                        <button class="btnBuy" type="submit">ADICIONAR</button>
                                    </form>
                                </div>
                            </div>
                        `;
                    } else {
                        produtos[i] = `
                            <div class="divProdCarousel">
                                <div class="btnFavorito` + response['produtos'][i].produto_id + `"></div>
                                <a class="linksProdCarousel" id-produto="` + response['produtos'][i].produto_id + `">
                                    <img class="divProdImg" src="` + BASE_URL3 + response['produtos'][i].produto_img + `">
                                    <div class='divisorFilterCar'></div>
                                    <p class="divProdPromo">-` + response['produtos'][i].produto_desconto_porcent + `%</p>
                                    <h4 class="divProdTitle">` + response['produtos'][i].produto_nome + ` - ` + response['produtos'][i].produto_tamanho + `</h4>
                                    <p class="divProdPrice"><span class="divProdPrice1">R$` + response['produtos'][i].produto_preco + `</span> R$` + response['produtos'][i].produto_desconto + `</p>
                                </a>
                                <div>
                                    <div class="quantity">
                                        <span class="esgotQtd">ESGOTADO</span>
                                    </div>
                                    <form class="formBuy">
                                        <button class="btnBuy" type="submit">ADICIONAR</button>
                                    </form>
                                </div>
                            </div>
                        `;
                    }
                }
                $('.l-prods').html(`<div class="loop owl-carousel prodsCar"></div>`);
                for(var i = 0; produtos.length > i; i++) {
                    $('.prodsCar').append(produtos[i]);
                }
                $('body').append(`
                    <div class="myModalProduto" id="myModalProduto">
                        <div class="modalProdutoContent">
                        <span class="closeModalProduto">&times;</span>
                            <div class="showProdutoModal">
                                
                            </div>
                        </div>
                    </div>
                `);
                attCarrinho();
                btnFavorito();
            } else {
                $('.l-prods').html(`<h2 class="sem_promo">Sem promoções hoje. Aproveite a barra de pesquisa</h2>`);
            }
        }
    });
});