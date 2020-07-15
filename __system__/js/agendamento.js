// function listCarrinho() {
//     $.ajax({
//         url: BASE_URL + 'functions/listCarrinho',
//         dataType: 'json',
//         success: function(json) {
//             if(!json['empty']) {
//                 $('.divShowProdFav').html(`<tr class="trNames">
//                 <th>PRODUTO</th>
//                 <th>QUANTIDADE</th>
//                 <th>PREÇO</th>
//                 <th>SUBTOTAL</th>
//                 </tr>`);
//                 for(var i = 0; json['prods'].length > i; i++) {
//                     if(json['prods'][i].produto_desconto_porcent) {
//                         $('.divShowProdFav').append(`
//                             <tr class="trCart">          
//                                 <td class="tdCart" width="40%">
//                                     <img class="imgCart" src="` + BASE_URL2 + `admin-area/img-produtos/` + json['prods'][i].produto_img + `"/>
//                                     <h5 class="titleProdCart">` + json['prods'][i].produto_nome + ` - ` + json['prods'][i].produto_tamanho + `</h5>
//                                     <h5 class="brandProdCart">` + json['prods'][i].marca_nome + `</h5>
//                                 </td>
//                                 <td class="tdCart" width="15%">
//                                     <input type='text' class="qtdProdCart" readonly value='` + json['prods'][i].carrinho + `'/>
//                                 </td>
//                                 <td class="tdCart" width="15%">
//                                     <h3 class="descProdCart">R$` + json['prods'][i].produto_preco + `</h3>
//                                     <h3 class="priceProdCart">R$` + json['prods'][i].produto_desconto + `</h3>
//                                 </td>
//                                 <td class="tdCart" width="20%">
//                                     <h3 class="priceProdCart subtot` + json['prods'][i].produto_id + `">R$` + json['prods'][i].subtotal + `</h3>
//                                 </td>
//                             </tr>
//                         `);
//                     } else {
//                         $('.divShowProdFav').append(`
//                         <tr class="trCart">
//                             <td class="tdCart" width="40%">
//                                 <img class="imgCart" src="` + BASE_URL2 + `admin-area/img-produtos/` + json['prods'][i].produto_img + `"/>
//                                 <h5 class="titleProdCart">` + json['prods'][i].produto_nome + ` - ` + json['prods'][i].produto_tamanho + `</h5>
//                                 <h5 class="brandProdCart">` + json['prods'][i].marca_nome + `</h5>
//                             </td>
//                             <td class="tdCart" width="20%">
//                                 <input type='text' class="qtdProdCart" readonly value='` + json['prods'][i].carrinho + `'/>
//                             </td>
//                             <td class="tdCart" width="20%">
//                                 <h3 class="descProdCart">-</h3>
//                                 <h3 class="priceProdCart">R$` + json['prods'][i].produto_preco + `</h3>
//                             </td>
//                             <td class="tdCart" width="20%">
//                                 <h3 class="priceProdCart subtot` + json['prods'][i].produto_id + `">R$` + json['prods'][i].subtotal + `</h3>
//                             </td>
//                         </tr>
//                         `);
//                     }
//                 }
//                 $('.divShowOpt').html(`
//                     <h2 class="summaryTitle">RESUMO</h2>
//                     <div class="divisorSummary"></div>
//                     <div class="summarySubTitles">
//                         <h3 class="totalDesc">DESCONTOS:</h3><h3 class="valueDesc">- R$` + json['totDesconto'] + `</h3>
//                     </div>
//                     <div class="summarySubTitles">
//                         <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$` + json['totCompra'] + `</h2>
//                     </div>
//                     `);
//                 $('.divShowOptBtn').html(`
//                     <a class="linkShop" href="` + BASE_URL + `home"><i class="fas fa-arrow-left"></i> CONTINUAR COMPRANDO</a>
//                     <button class="limparCart"><i class="fas fa-arrow-left"></i> VOLTAR AO CARRINHO</button>
//                     `);
//                 $('.divShowTot').html(`
//                     <h2 class="summaryTitle">RESUMO</h2>
//                     <div class="divisorSummary"></div>
//                     <div class="summarySubTitles">
//                         <h3 class="totalDesc">DESCONTOS:</h3><h3 class="valueDesc">- R$` + json['totDesconto'] + `</h3>
//                     </div>
//                     <div class="summarySubTitles">
//                         <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$` + json['totCompra'] + `</h2>
//                     </div>
//                     `);
//                     $('.divShowOptDesk').html(`
//                     <button class="limparCart"><i class="fas fa-arrow-left"></i> VOLTAR AO CARRINHO</button>
//                     <a class="linkShop" href="` + BASE_URL + `home"><i class="fas fa-arrow-left"></i> CONTINUAR COMPRANDO</a>
//                     `);
//                 $('.finalizaCompra').click(function() {
//                     window.location.href = BASE_URL + 'pagamento';
//                 });
//                 $('.limpaCart').click(function() {
//                     window.location.href = BASE_URL + 'carrinho';
//                 });
//             } else {
//                 $('.divShowProdFav').html("Sem produtos no carrinho!");
//                 $('.divShowTot').html("");
//                 $('.divShowOpt').html("");
//             }
//         }
//     });
// }

$(document).ready(function() {
    $("#usu_cep").focusout(function(){
        $.ajax({
            url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/unicode/',
            dataType: 'json',
            success: function(resposta){
                $("#usu_end").val(resposta.logradouro);
                $("#usu_complemento").val(resposta.complemento);
                $("#usu_bairro").val(resposta.bairro);
                $("#usu_uf").val(resposta.uf);
                $("#usu_cidade").val(resposta.localidade);
                $("#usu_num").focus();
            }
        });
    });

    $("#endereco_entrega").submit(function() {
        $.ajax({
            dataType: 'json',
            type: 'post',
            data: $(this).serialize(),
            url: BASE_URL + 'functions/agendamento',
            beforeSend: function() {
                $('#btn-cad').siblings('.help-block').html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    clearErrors();
                    $("#btn-cad").siblings(".help-block").html(loadingRes("Redirecionando..."));
                    $('.divAgend').html(`
                        <h2 class="titleStep2_1">Escolha o horário que você quer que entreguemos sua compra<br>(prazo máximo de uma hora e meia)!</h2>
                        <p class="titleEndAgend">Entrega para o endereço:</p><p class="endAgend">` + json['agend_end'] + `</p>
                    `);
                    $.ajax({
                        url: BASE_URL + 'functions/agendamento',
                        success: function(response) {
                            $('.divAgend').append(response);
                            $("#hora_agend").submit(function() {
                                $.ajax({
                                    url: BASE_URL + 'functions/agendamento',
                                    type: 'post',
                                    data: $(this).serialize(),
                                    success: function() {
                                        window.location.href = "pagamento";
                                    }
                                });
                                return false;
                            });
                        }
                    })
                } else {
                    if(json['armazem']) {
                        showErrors(json["error_list"]);
                    } else {
                        clearErrors();
                        Swal.fire({
                            title: "e.conomize informa:",
                            text: "O armazém que está comprando não faz entrega em sua cidade, desculpe-nos!",
                            type: "error",
                            confirmButtonColor: "#A94442",
                            confirmButtonText: "Ok, cancelar"
                        });
                    }
                    
                }
            }
        });
        return false;
    });
});

// listCarrinho();