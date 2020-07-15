var amount = $('#amount').val();
payment();

function payment() {
    $.ajax({
        url: BASE_URL + 'functions/pagseguro/payment',
        type: 'post',
        dataType: 'json',
        success: function(sessionId) {
            // console.log(sessionId);
            PagSeguroDirectPayment.setSessionId(sessionId.id);
        },
        complete: function() {
            listDirectPayment();
        }
    });
}

function listDirectPayment() {
    PagSeguroDirectPayment.getPaymentMethods({
        amount: amount,
        success: function(response) {
            // Buscando Cartões de Crédito
            $('.divShowCards').html(`
                <h4 class="hCardTitle">Cartões de Crédito</h4>
                <a class="a_hrefPayment ocultCardShow" href="#">Ocultar &nbsp;<i class="far fa-times-circle"></i></button></a>
            `);
            $.each(response.paymentMethods.CREDIT_CARD.options, function(i, obj) {
                $('.divShowCards').append(`
                    <div class="divShowCard">
                        <img src="https://stc.pagseguro.uol.com.br` + obj.images.SMALL.path + `"/><br/>
                        <span class="displayNameCard">` + obj.displayName + `</span>
                    </div>
                `);
            });

            $('.a_cardDiv').click(function(e) {
                e.preventDefault();
                $('.divShowCards').css({'display':'block'});
            });

            $('.ocultCardShow').click(function(e) {
                e.preventDefault();
                $('.divShowCards').css({'display':'none'});
            });

            // Buscando Boleto
            $('.divMethodBoleto').html(`
                <div class="divShowBol">
                    <img class="imgBoleto" src="https://stc.pagseguro.uol.com.br` + response.paymentMethods.BOLETO.options.BOLETO.images.MEDIUM.path + `"/><br/>
                    Tarifa de boleto = R$ 1,00<br/>
                    Tarifa aplicada para cobrir os custos de gestão de risco do meio de pagamento.
                </div>
            `);
            
            $('#bankName').html(`<option value="*000*">...</option>`);
            $.each(response.paymentMethods.ONLINE_DEBIT.options, function(i, obj) {
                $('#bankName').append(`<option value="` + obj.name + `">` + obj.displayName + `</option>`);
            });
        },
        error: function(response) {
            // Callback para chamadas que falharam.
        },
        complete: function(response) {
            
        }
    });
}

$('[name=paymentMethod]').change(function(e) {
    e.preventDefault();
    $('.help-submit-pag').html(``);

    var dado = $(this).val();
    if(dado == "creditCard") {
        $('.divDebitoOnline').css({'display':'none'});
        $('.divMethodBoleto').css({'display':'none'});
        
        $('.divMethodCard').css({'display':'block'});
        $('.divSaveCard').css({'display':'block'});
        $('.CardsData').css({'display':'block'});
        $('#btnBuyPagSeguro').html(`Efetuar pagamento`);
    } else if (dado == "boleto") {
        $('.divDebitoOnline').css({'display':'none'});
        $('.divMethodCard').css({'display':'none'});
        $('.CardsData').css({'display':'none'});
        $('.divSaveCard').css({'display':'none'});

        $('.divMethodBoleto').css({'display':'block'});
        $('#btnBuyPagSeguro').html(`Gerar boleto`);
    } else {
        $('.divMethodCard').css({'display':'none'});
        $('.CardsData').css({'display':'none'});
        $('.divMethodBoleto').css({'display':'none'});
        $('.divSaveCard').css({'display':'none'});

        $('.divDebitoOnline').css({'display':'block'});
        $('#btnBuyPagSeguro').html(`Acessar página do banco`);
    }
});

$('[name=billingAddress]').change(function(e) {
    e.preventDefault();
    var dado = $(this).val();
    if(dado == 0) {
        $('.divEndFatura').hide().fadeOut('slow');
        $('.divOtherEndFatura').show().fadeIn('slow');
    } else {
        $('.divOtherEndFatura').hide().fadeOut('slow');
        $('.divEndFatura').show().fadeIn('slow');
    }
});

$('#inputNumCard').keyup(function(e) {
    e.preventDefault();

    var numCard = $(this).val();
    var qtdNum = numCard.length;

    if(qtdNum >= 6) {
        $('.brandCard').html(loadingResSmall());
        PagSeguroDirectPayment.getBrand({
            cardBin: numCard,
            success: function(response) {
                var imgBrand = response.brand.name;
                $('.brandCard').html(`<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/42x20/` + imgBrand + `.png"/>`);

                $('#inputBrandCard').val(imgBrand);
                getParcelas(imgBrand);
            },
            error: function() {
                $('.brandCard').html(`<small>Cartão inválido!</small>`);
                $('#inputBrandCard').val(``);
                $('#selQtdParc').html(``);
                $('#selQtdParc').attr('disabled', true);
            },
            complete: function(response) {
                //tratamento comum para todas chamadas
            }
        });
    } else {
        $('.brandCard').html(``);
        $('#selQtdParc').html(``);
        $('#selQtdParc').attr('disabled', true);
        $('#inputBrandCard').val(``);
    }
});

// RECUPERANDO A QUANTIDADE DE PARCELAS E SEUS RESPECTIVOS VALORES
function getParcelas(brand) {
    var maxInstallment = 2;
    PagSeguroDirectPayment.getInstallments({
        amount: amount,
        maxInstallmentNoInterest: maxInstallment,
        brand: brand,
        success: function(response) {
            $('#selQtdParc').attr('disabled', false);
            $('#selQtdParc').show().html(`
                <option value="*000*">...</option>
            `);
            var c = 1;
            $.each(response.installments, function(ia, obja) {
                $.each(obja, function(ib, objb) {
                    var valorParc = objb.installmentAmount.toFixed(2).replace(".", ","); // Padrão BR
                    var totalAmount = objb.totalAmount.toFixed(2).replace(".", ","); // Padrão BR
                    var valorParcDouble = objb.installmentAmount.toFixed(2); // Com 2 casas decimais

                    if(c <= maxInstallment) {
                        $('#selQtdParc').append(`
                            <option value="` + objb.quantity + `" data-parcelas="` + valorParcDouble + `">` + objb.quantity + ` x R$ ` + valorParc + ` = R$ ` + totalAmount + ` sem acréscimo</option>
                        `);
                    } else {
                        $('#selQtdParc').append(`
                            <option value="` + objb.quantity + `" data-parcelas="` + valorParcDouble + `">` + objb.quantity + ` x R$ ` + valorParc + ` = R$ ` + totalAmount + `</option>
                        `);
                    }
                    c++;
                });
            });

            c--;
            if(c > 0) {
                if(c > 1) {
                    $('#labelPagQuantity').html(`Pague em até ` + c + ` vezes`);
                } else {
                    $('#labelPagQuantity').html(`Pague em uma vez`);
                }
            }
        },
        error: function(response) {
            // callback para chamadas que falharam.
        },
        complete: function(response) {
            // Callback para todas chamadas.
        }
    });
}

// ENVIANDO O VALOR DA PARCELA PARA O FORMULÁRIO
$('#selQtdParc').change(function(e) {
    e.preventDefault();
    $('#inputParcValue').val($(this).find(":selected").attr("data-parcelas"));
});

$('#formBuyPagSeguro').submit(function(e) {
    e.preventDefault();
    $('.help-submit-pag').html(`<p class="loading"><i class='fa fa-circle-notch fa-spin'></i> &nbsp;&nbsp;Verificando...</p>`);
    var paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;

    if(paymentMethod == "creditCard") {
        // RECUPERANDO TOKEN DO CARTÃO DE CRÉDITO
        PagSeguroDirectPayment.createCardToken({
            cardNumber: $('#inputNumCard').val(), // Número do cartão de crédito
            brand: $('#inputBrandCard').val(), // Bandeira do cartão
            cvv: $('#inputCvvCard').val(), // CVV do cartão
            expirationMonth: $('#inputMonthValid').val(), // Mês da expiração do cartão
            expirationYear: $('#inputYearValid').val(), // Ano da expiração do cartão, é necessário os 4 dígitos.
            success: function(response) {
                $('.help-card').html(``);
                $('#inputTokenCard').val(response.card.token);
                recupHash();
            },
            error: function(response) {
                $('.help-submit-pag').html(`<p class="infErrorPag">Preencha os campos corretamente, por favor!</p>`);
                return false;
            },
            complete: function(response) {

            }
        });
    } else if(paymentMethod == "boleto") {
        recupHash();
    } else {
        recupHash();
    }

    return false;
});

function recupHash() {
    // RECUPERANDO O HASH DO CARTÃO
    $('.help-submit-pag').html(`<p class="loading"><i class='fa fa-circle-notch fa-spin'></i> &nbsp;&nbsp;Recuperando hash...</p>`);

    PagSeguroDirectPayment.onSenderHashReady(function(response) {
        if(response.status == 'error') {
            $('.help-submit-pag').html(`<p class="infErrorPag">Ocorreu um erro ao buscar o hash! Tente novamente, por favor.</p>`);
            return false;
        } else {
            $('#inputHashSender').val(response.senderHash);
            var dataForm = $('#formBuyPagSeguro').serialize();

            $.ajax({
                type: 'post',
                url: BASE_URL + 'functions/pagseguro/processPurchase',
                data: dataForm,
                dataType: 'json',
                success: function(response) {
                    if(response.dados.error) {
                        $('.help-submit-pag').html(`<p class="infErrorPag">Preencha os campos corretamente, por favor!</p>`);
                    } else {
                        if(response.dados.errorInsert) {
                            $('.help-submit-pag').html(`<p class="infErrorPag">` + response.dados.errorInsert + `</p>`);
                        } else {
                            if(response.dados.paymentMethod.type == 1) {
                                buscaExtrato();
                            } else if(response.dados.paymentMethod.type == 2) {
                                buscaExtrato();
                                $('.answer').html(`
                                    <a target="_blank" href="` + response.dados.paymentLink + `">Gerar boleto</a>
                                `);
                            } else {
                                buscaExtrato();
                                window.open(response.dados.paymentLink);
                            }

                            $('.answer').append(`
                                <h4>Total pago: R$` + response.dados.grossAmount + `</h4>
                                <h4>Sua compra</h4>
                            `);
                            $.each(response.dados.items, function(ia, obja) {
                                $.each(obja, function(ib, objb) {
                                    $('.answer').append(`
                                        <p>
                                            Produto: ` + objb.description + `<br/>
                                            Quantidade: ` + objb.quantity + `
                                        </p>
                                    `);
                                });
                            });
                        }
                    }
                },
                error: function() {
                    $('.help-submit-pag').html(`
                        <p>Ocorreu um erro ao realizar a transação! Tente novamente, por favor.</p>
                    `);
                }
            });
        }
    });
}