$(document).ready(function() {
    $('.formPesquisaHeader').submit(function() {
        if($('.pesquisaTxtHeader').val().length > 0) {
            Toast.fire({
                type: 'success',
                title: 'Pesquisa realizada com sucesso'
            });
        } else {
            Swal.fire({
                title: "Digite o que está buscando no campo ao topo da sua tela que, em instantes, te daremos a resposta!",
                type: "error",
                showCancelButton: false,
                confirmButtonColor: "#9C45EB",
                confirmButtonText: "Ok",
            });
        }
    
        return false;
    });

    attCarrinho();
    
    $('.pesquisaTxtHeader').keyup(function(e) {
        $('.pesquisaTxtHeader').val($(this).val());
        e.preventDefault();

        if($(this).val().length > 0) {
            var dado = "q=" + $(this).val();
            $.ajax({
                dataType: 'json',
                url: BASE_URL + 'functions/listSearch',
                type: 'post',
                data: dado,
                beforeSend: function() {
                    $('.defaultTitle').html(`Sua pesquisa sobre: ` + $('.pesquisaTxtHeader').val());
                },
                success: function(json) {
                    if(!json['empty']) {
                        var produtos = [];
                        $('.divShowProdFav').html("");
                        for(var i = 0; json['prods'].length > i; i++) {
                            if(json['prods'][i].produto_desconto_porcent || json['prods'][i].promo_desconto) {
                                produtos[i] = `
                                    <div class="prodFilter">
                                        <div class='btnFavoriteFilter btnFavorito` + json['prods'][i].produto_id + `'>
                                            <i class="far fa-heart addFavorito" id="` + json['prods'][i].produto_id + `"></i>
                                        </div>
                                        <a class="linksProdCarousel" id-produto="` + json['prods'][i].produto_id + `">
                                            <img src='` + BASE_URL2 + `admin-area/img-produtos/` + json['prods'][i].produto_img + `'/>
                                            <p class="divProdPromo">-` + ((json['prods'][i].promo_desconto != null) ? json['prods'][i].promo_desconto : json['prods'][i].produto_desconto_porcent) + `%</p>
                                            <div class='divisorFilter'></div>
                                            <h5 class='titleProdFilter'>` + json['prods'][i].produto_nome + ` - `  + json['prods'][i].produto_tamanho + `</h5>
                                            <p class='priceProdFilter'><span class="divProdPrice1">R$` + json['prods'][i].produto_preco + `</span> R$` + json['prods'][i].produto_desconto + `</p>
                                        </a>
                                        <div>
                                `;
                                if(!json['prods'][i].empty) {
                                    produtos[i] += `
                                                <form class="formBuy">
                                                    <input type="hidden" value="` + json['prods'][i].produto_id + `" name="id_prod"/>
                                                    <input type="number" min="0" max="20" value="` + json['prods'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
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
                                        <div class='btnFavoriteFilter btnFavorito` + json['prods'][i].produto_id + `'>
                                            <i class="far fa-heart addFavorito" id="` + json['prods'][i].produto_id + `"></i>
                                        </div>
                                        <a class="linksProdCarousel" id-produto="` + json['prods'][i].produto_id + `">
                                            <img src='` + BASE_URL2 + `admin-area/img-produtos/` + json['prods'][i].produto_img + `'/>
                                            <div class='divisorFilter'></div>
                                            <h5 class='titleProdFilter'>` + json['prods'][i].produto_nome + ` - `  + json['prods'][i].produto_tamanho + `</h5>
                                            <p class='priceProdFilter'>R$ ` + json['prods'][i].produto_preco + `</p>
                                        </a>
                                        <div>
                                `;
                                if(!json['prods'][i].empty) {
                                    produtos[i] += `
                                                <form class="formBuy">
                                                    <input type="hidden" value="` + json['prods'][i].produto_id + `" name="id_prod"/>
                                                    <input type="number" min="0" max="20" value="` + json['prods'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
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
                        for(var c = 0; c < produtos.length; c++) {
                            $('.divShowProdFav').append(produtos[c]);
                        }
                        btnFavorito();
                        attCarrinho();
                        abrirModal();
                    } else {
                        $('.divShowProdFav').html(`
                            <p class='msgHelpSearch'>
                                <h4>Não houve resposta para o que pesquisou!</h4>
                                <b>Possíveis soluções:</b><br/>
                                <b>1.</b> Tente ser bem específico ao que está procurando;<br/>
                                <b>2.</b> Tente escrever pelo menos uma palavra inteira, por exemplo 'Refrigerante' ao invés de 'Refri';<br/>
                                <b>3.</b> Não use palavras tão comuns;<br/>
                                <b>4.</b> ...<br/>
                            </p>
                        `);
                    }
                }
            });

            window.history.pushState("object", "e.conomize | Busca de Produtos", BASE_URL + "pesquisa?q=" + $('.pesquisaTxtHeader').val());
        } else {
            window.history.pushState("object", "e.conomize | Busca de Produtos", BASE_URL + "pesquisa?q=" + $('.pesquisaTxtHeader').val());
            $('.defaultTitle').html(`Pesquise seu produto no campo acima`);
            $('.divShowProdFav').html(``);
        }
    });
});