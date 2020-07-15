function listCarrinho() {
    $.ajax({
        url: BASE_URL + 'functions/listCarrinho',
        dataType: 'json',
        success: function(json) {
            $('.divShowProdFav').html("");
            $('.divShowTot').html("");
            $('.divShowOpt').html("");
            $('.divShowOptBtn').html("");
            $('.divShowOptDesk').html("");
            if(!json['empty']) {
                $('.divShowProdFav').html(`
                    <tr class="trNames">
                        <th>PRODUTO</th>
                        <th>QUANTIDADE</th>
                        <th>PREÇO</th>
                        <th>SUBTOTAL</th>
                        <th></th>
                    </tr>
                `);
                for(var i = 0; json['produtosCart'].length > i; i++) {
                    if(json['produtosCart'][i].produto_desconto_porcent || json['produtosCart'][i].promo_desconto) {
                        $('.divShowProdFav').append(`
                        <tr class="trCart">
                            <td class="tdCart" width="40%">
                                <img class="imgCart" src="` + BASE_URL3 + json['produtosCart'][i].produto_img + `"/>
                                <h5 class="titleProdCart">` + json['produtosCart'][i].produto_nome + ` - ` + json['produtosCart'][i].produto_tamanho + `</h5>
                                <h5 class="brandProdCart">` + json['produtosCart'][i].marca_nome + `</h5>
                            </td>
                            <td class="tdCart" width="15%">
                                <input type='number' min='0' max='20' class="qtdProdCart" id-prod="` + json['produtosCart'][i].produto_id + `" value='` + json['produtosCart'][i].carrinho + `'>
                            </td>
                            <td class="tdCart" width="15%">
                                <h3 class="descProdCart">R$` + json['produtosCart'][i].produto_preco + `</h3>
                                <h3 class="priceProdCart">R$` + json['produtosCart'][i].produto_desconto + `</h3>
                            </td>
                            <td class="tdCart" width="20%">
                                <h3 class="priceProdCart subtot` + json['produtosCart'][i].produto_id + `">R$` + json['produtosCart'][i].subtotal + `</h3>
                            </td>
                            <td class="tdCart" width="20%">
                                <button class="tirarProd btnProdCart" id-prod="` + json['produtosCart'][i].produto_id + `"><i class="far fa-times-circle"></i></button>
                            </td>
                        </tr>
                        `);
                    } else {
                        $('.divShowProdFav').append(`
                        <tr class="trCart">
                            <td class="tdCart" width="40%">
                                <img class="imgCart" src="` + BASE_URL3 + json['produtosCart'][i].produto_img + `"/>
                                <h5 class="titleProdCart">` + json['produtosCart'][i].produto_nome + ` - ` + json['produtosCart'][i].produto_tamanho + `</h5>
                                <h5 class="brandProdCart">` + json['produtosCart'][i].marca_nome + `</h5>
                            </td>
                            <td class="tdCart" width="20%">
                                <input type='number' min='0' max='20' class="qtdProdCart" id-prod="` + json['produtosCart'][i].produto_id + `" value='` + json['produtosCart'][i].carrinho + `'>
                            </td>
                            <td class="tdCart" width="20%">
                                <h3 class="descProdCart">-</h3>
                                <h3 class="priceProdCart">R$` + json['produtosCart'][i].produto_preco + `</h3>
                            </td>
                            <td class="tdCart" width="20%">
                                <h3 class="priceProdCart subtot` + json['produtosCart'][i].produto_id + `">R$` + json['produtosCart'][i].subtotal + `</h3>
                            </td>
                            <td class="tdCart" width="20%">
                                <button class="tirarProd btnProdCart" id-prod="` + json['produtosCart'][i].produto_id + `"><i class="far fa-times-circle"></i></button>
                            </td>
                        </tr>
                        `);
                    }
                }
                $('.divShowOpt').html(`
                    <h2 class="summaryTitle">RESUMO</h2>
                    <div class="divisorSummary"></div>
                    <div class="summarySubTitles">
                        <h3 class="totalDesc">DESCONTOS:</h3><h3 class="valueDesc">- R$` + json['totDesconto'] + `</h3>
                    </div>
                    <div class="summarySubTitles">
                        <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$` + json['totCompra'] + `</h2>
                    </div>
                    `);
                $('.divShowOptBtn').html(`
                    <a class="linkShop" href="` + BASE_URL + `"><i class="fas fa-arrow-left"></i> CONTINUAR COMPRANDO</a>
                    <button class="limparCart">LIMPAR CARRINHO <i class="far fa-trash-alt"></i></button>
                    <div class="divButtonCupom">
                        <button class="addCupom">ADICIONAR CUPOM <i class="fas fa-tag"></i></button>
                    </div>
                    <div class="divAddCupom"></div>
                    <div class="divAnswer"></div>
                    <button class="finalizaCompra">PRÓXIMO PASSO <i class="fas fa-arrow-right"></i></button>
                    `);
                $('.divShowTot').html(`
                    <h2 class="summaryTitle">RESUMO</h2>
                    <div class="divisorSummary"></div>
                    <div class="summarySubTitles">
                        <h3 class="totalDesc">DESCONTOS:</h3><h3 class="valueDesc">- R$` + json['totDesconto'] + `</h3>
                    </div>
                    <div class="summarySubTitles">
                        <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$` + json['totCompra'] + `</h2>
                    </div>
                `);
                $('.divShowOptDesk').html(`
                    <button class="limparCart">LIMPAR CARRINHO <i class="far fa-trash-alt"></i></button>
                    <div class="divButtonCupom">
                        <button class="addCupom">ADICIONAR CUPOM <i class="fas fa-tag"></i></button>
                    </div>
                    <div class="divAddCupom"></div>
                    <div class="divAnswer"></div>
                    <button class="finalizaCompra">PRÓXIMO PASSO <i class="fas fa-arrow-right"></i></button><br>
                    <a class="linkShop" href="` + BASE_URL + `home"><i class="fas fa-arrow-left"></i> CONTINUAR COMPRANDO</a>
                `);
                $('.finalizaCompra').click(function() {
                    if(json['logado']) {
                        buscaEndereco();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'Você precisa estar logado'
                        });

                        $("#usu_email_login").val("");
                        $("#usu_senha_login").val("");
                        $(".help-block-login").html("");
                        modal.style.display = "block";
                    }
                });
                
                verificaCupom();
                botaoAddCupom();
                attCarrinho();
            } else {
                $('.divShowProdFav').html(
                    `<center><img src="${BASE_URL2}style/img/banner/cart.png" class="imgEmptyCart" alt="Carrinho está vazio!" title="Seu carrinho está vazio!"></center>`
                );
                $('.divTable').html(
                    `<center><img src="${BASE_URL2}style/img/banner/cart.png" class="imgEmptyCart" alt="Carrinho está vazio!" title="Seu carrinho está vazio!"></center>`
                );
                $('.divShowTot').removeClass("divShowTot");
                $('.divShowOpt').removeClass("divShowOpt");
                $('.divShowOptBtn').removeClass("divShowOptBtn");
                $('.divShowOptDesk').removeClass("divShowOptDesk");
            }
        }
    });
}

function listParcialCarrinho() {
    $.ajax({
        url: BASE_URL + 'functions/listCarrinho',
        dataType: 'json',
        success: function(json) {
            for(var i = 0; json['produtosCart'].length > i; i++) {
                $('.subtot' + json['produtosCart'][i].produto_id).html(`R$` + json['produtosCart'][i].subtotal);
            }
            $('.valueDesc').html(`- R$` + json['totDesconto']);
            $('.valueBuy').html(`R$` + json['totCompra']);
        }
    });
}