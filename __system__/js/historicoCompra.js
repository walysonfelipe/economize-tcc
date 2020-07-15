function showPurch() {
    $('.viewPurchase').click(function(e) {
        e.preventDefault();
        var dado = "showPurch=" + $(this).attr("data-purch");

        $.ajax({
            dataType: 'json',
            data: dado,
            type: 'post',
            url: BASE_URL +  'functions/historicoCompra',
            beforeSend: function() {
                $('.answer-purch').html(loadingRes("Processando..."));
            },
            success: function(json) {
                $('.answer-purch').html(``);
                if(json['status']) {
                    $('.divCompraRight').html(`
                        <div class="headerShowPurch">
                            <span class="hashHeaderPurch">Código: ` + json['compra']['hash'] + `</span>
                            <span class="totHeaderPurch">Valor total: R$` + json['compra']['total'] + `</span>
                        </div>

                        <span class="btnClosePurch"><i class="far fa-times-circle"></i></span>

                        <div class="mainShowPurch">
                            Data realizada: <b>` + json['compra']['registro'] + `</b><br/>
                            Armazém: <b>` + json['compra']['armazem'] + `</b><br/>
                            Status: <b>` + json['compra']['status'] + `</b><br/>
                            Meio de pagamento: <b>` + json['compra']['forma_pag'] + `</b> 
                            <span class="linkPayment"></span><br/>
                            <a href="` + BASE_URL + `usuario/nota-fiscal?compra=` + json['compra']['id'] + `">Gerar PDF</a>
                        </div>

                        <div class="productsShowPurch">
                            <h4 class="itCart">Itens do carrinho</h4>
                            <div class="productCart"></div>
                        </div>

                        <div class="shippingShowPurch">
                            <h4 class="itCart">Endereço de entrega</h4>

                            Agendamento: <b>` + json['end']['horario'] + `</b><br/>
                            CEP: <b>` + json['end']['cep'] + `</b><br/>
                            Logradouro: <b>` + json['end']['log'] + `, ` + json['end']['num'] + ((json['end']['complemento'] != '') ? ` - ` + json['end']['complemento'] : `` ) + `</b><br/>
                            Bairro: <b>` + json['end']['bairro'] + `</b><br/>
                            Localidade: <b>` + json['end']['cidade'] + ` - ` + json['end']['uf'] + `</b><br/>
                        </div>
                    `);

                    if(json['compra']['link']) {
                        $('.linkPayment').html(`<a href="` + json['compra']['link'] + `">Abrir link</a>`);
                    }

                    for(var i = 0; i < json['produto_id'].length; i++) {
                        $('.productCart').append(`
                            <p class="p_prodCart">
                                Produto: <b>` + json['produto_nome'][i] + `</b><br/>
                                Quantidade: <b>` + json['produto_qtd'][i] + `</b><br/>
                                <a href="` + BASE_URL + `produto/` + json['produto_cript'][i] + `">Ver produto</a>
                            </p>
                        `);
                    }
                    closePurch();
                } else {
                    $('.answer-purch').html(`
                        <p class="msgErrorPurch">` + json['error'] + `</p>
                    `);
                }
            }
        });
    });
}

function closePurch() {
    $('.btnClosePurch').click(function(e) {
        e.preventDefault();
        $('.divCompraRight').html(`
            <div class="answer-purch">
                <h1>Clique em uma compra e ela aparecerá aqui!</h1>
            </div>
        `);
    });
}

function writePurch() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL +  'functions/historicoCompra',
        beforeSend: function() {
            $('.help-block-purch').html(loadingResSmall());
        },
        success: function(json) {
            $('.help-block-purch').html(``);
            $('.showCompras').html(``);
            if(json['status']) {
                for(var i = 0; i < json['compra'].length; i++) {
                    $('.showCompras').append(`
                        <a href="#" class="viewPurchase" data-purch="` + json['compra'][i].compra_id + `">
                            <p class="p_showPurch">
                                Data: ` + json['compra'][i].compra_registro + `<br/>
                                Total: R$` + json['compra'][i].compra_total + `<br/>
                                Meio Pag.: ` + json['compra'][i].forma_nome + `
                            </p>
                        </a>
                    `);
                }
                showPurch();
            } else {
                $('.showCompras').html(`
                    <p class="msgErrorPurch">` + json['error'] + `</p>
                `);
            }
        }
    });
}

$('#inputSearchPurch').keyup(function(e) {
    e.preventDefault();

    if($(this).val().length > 0) {
        var dado = "searchPurch=" + $(this).val();

        $.ajax({
            dataType: 'json',
            data: dado,
            type: 'post',
            url: BASE_URL +  'functions/historicoCompra',
            beforeSend: function() {
                $('.help-block-purch').html(loadingResSmall());
            },
            success: function(json) {
                $('.help-block-purch').html(``);
                $('.showCompras').html(``);
                if(json['status']) {
                    for(var i = 0; i < json['compra'].length; i++) {
                        $('.showCompras').append(`
                            <a href="#" class="viewPurchase" data-purch="` + json['compra'][i].compra_id + `">
                                <p class="p_showPurch">
                                    Data: ` + json['compra'][i].compra_registro + `<br/>
                                    Total: R$` + json['compra'][i].compra_total + `<br/>
                                    Meio Pag.: ` + json['compra'][i].forma_nome + `
                                </p>
                            </a>
                        `);
                    }
                    showPurch();
                } else {
                    $('.showCompras').html(`
                        <p class="msgErrorPurch">` + json['error'] + `</p>
                    `);
                }
            }
        });
    } else {
        writePurch();
    }
});

showPurch();