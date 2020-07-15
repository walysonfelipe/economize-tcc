<?php 
    use Model\{Cart, User};
    $sql = new Sql();

    require_once "configuration.php";

    $inf_compra['status'] = 1;
    $inf_compra['error'] = null;

    if (User::checkLogin()) {
        //BUSCANDO OS TELEFONES DO CLIENTE
        $tel = $sql->select("SELECT tel_num FROM telefone WHERE usu_id = :id LIMIT 1", [
            ":id" => $_SESSION[User::SESSION]['usu_id']
        ]);
        $v = $tel[0];
        $v['ddd'] = substr($v['tel_num'], 1, 2);
        $v['num'] = substr($v['tel_num'], -10);
        $pos = strpos($v['num']," ");
        if ($pos) {
            $v['num'] = str_replace(" ", "", $v['num']);
        }
        $v['num'] = str_replace("-", "", $v['num']);

        $inf_compra['client'] = $_SESSION[User::SESSION];
        $inf_compra['client']['tel_ddd'] = $v['ddd'];
        $inf_compra['client']['tel_num'] = $v['num'];

        if (!isset($_SESSION[Cart::SESSION])) {
            $inf_compra['status'] = 0;
            $inf_compra['error'] = "<small>Voçê precisa ter produto(s) no carrinho para efetuar o pagamento!</small>";
        } else {
            if (!isset($_SESSION['end_agend'])) {
                $inf_compra['status'] = 0;
                $inf_compra['error'] = "<small>Voçê precisa informar o endereço de entrega para efetuar o pagamento!</small>";
            } else {
                $inf_compra['end_entrega'] = $_SESSION['end_agend'];
                if (!isset($_SESSION['agend_horario'])) {
                    $inf_compra['status'] = 0;
                    $inf_compra['error'] = "<small>Voçê precisa agendar a entrega para efetuar o pagamento!</small>";
                } else {
                    $exp = explode(" ", $_SESSION['agend_horario']);
                    $day = explode("-", $exp[0]);
                    $hour = explode(":", $exp[1]);
        
                    $inf_compra['agend_horario'] = "Para " . $day[2] . "/" . $day[1] . "/" . $day[0] . " às " . $hour[0] . "h" . $hour[1];

                    if (isset($_SESSION['cupom_compra'])) {
                        $totCupom = $_SESSION['totCompraCupom']*($_SESSION['cupom_compra']['cupom_desconto_porcent']/100);
                        $totCupom = Project::formatPriceToDolar($totCupom);
                    }

                    $totCompra = Project::formatPriceToDolar($_SESSION['totCompra']);
                }
            }
        }
    } else {
        $inf_compra['status'] = 0;
        $inf_compra['error'] = "<small>Voçê precisa estar logado para efetuar o pagamento!</small>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>e.conomize | Checkout Transparente</title>
    <link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
    <link href="<?= Project::baseUrl(); ?>functions/pagseguro/style/main.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>/style/css/minified-main.css"/>
</head>
<body>
    <?php
    if($inf_compra['status']):?>
        <div id="answer"></div>
        <form id="formBuyPagSeguro">
            <h4>Tipo de pagamento</h4>
            <input type="radio" name="paymentMethod" id="paymentMethodCreditCard" value="creditCard" checked/>
            <label for="paymentMethodCreditCard"> Cartão de crédito</label>

            <input type="radio" name="paymentMethod" id="paymentMethodBoleto" value="boleto"/>
            <label for="paymentMethodBoleto"> Boleto</label>

            <input type="radio" name="paymentMethod" id="paymentMethodEft" value="eft"/>
            <label for="paymentMethodEft"> Débito online</label>


            <div class="divDebitoOnline">
                <h4>Escolha o banco</h4>
                <select name="bankName" id="bankName">

                </select>
            </div>
            

            <input type="hidden" name="receiverEmail" id="receiverEmail" value="<?= EMAIL_LOJA; ?>"/>
            <input type="hidden" name="currency" id="currency" value="<?= MOEDA_PAGAMENTO; ?>"/>

            <input type="hidden" name="amount" id="amount" value="<?= $totCompra; ?>"/>
            <input type="hidden" name="extraAmount" id="extraAmount" value="<?= isset($totCupom) ? "-" . $totCupom : ""; ?>"/>
            
            <input type="hidden" name="notificationURL" id="notificationURL" value="<?= URL_NOTIFICATION; ?>"/>
            <input type="hidden" name="reference" id="reference" value="ECONOMIZE0101"/>

            <h4>Dados do comprador</h4>
            <div class="infComp">
                <?= $inf_compra['client']['usu_first_name'] . " " . $inf_compra['client']['usu_last_name']; ?><br/>
                <?= $inf_compra['client']['usu_cpf']; ?><br/>
                <?= $inf_compra['client']['tel_ddd'] . " " . $inf_compra['client']['tel_num']; ?><br/>

                <input type="hidden" name="inputSenderName" id="inputSenderName" value="<?= $inf_compra['client']['usu_first_name'] . " " . $inf_compra['client']['usu_last_name']; ?>"/>
                <input type="hidden" name="inputSenderCPF" id="inputSenderCPF" value="<?= $inf_compra['client']['usu_cpf']; ?>"/>
                <input type="hidden" name="inputSenderDDD" id="inputSenderDDD" value="<?= $inf_compra['client']['tel_ddd']; ?>"/>
                <input type="hidden" name="inputSenderNum" id="inputSenderNum" value="<?= $inf_compra['client']['tel_num']; ?>"/>
                <input type="text" name="inputSenderEmail" id="inputSenderEmail" value="c42358207331366747669@sandbox.pagseguro.com.br"/>
            </div>

            <h4>Endereco da entrega</h4>
            <div class="endComp">
                <?= $inf_compra['end_entrega'][0]; ?><br/>
                <?= $inf_compra['end_entrega'][1] . " nº " . $inf_compra['end_entrega'][2]; ?><br/>
                <?= (($inf_compra['end_entrega'][3] != "") ? ", {$inf_compra['end_entrega'][3]} <br/>" : ""); ?>
                <?= $inf_compra['end_entrega'][4]; ?><br/>
                <?= $inf_compra['end_entrega'][5] . " - " . $inf_compra['end_entrega'][6]; ?>

                <input type="hidden" name="shippingType" id="shippingType" value="3"/> <!-- Tipo de entrega -->
                <input type="hidden" name="shippingCost" id="shippingCost" value="0.00"/> <!-- Valor frete -->

                <input type="hidden" name="shippingAddressRequired" id="shippingAddressRequired" value="true"/>
                <input type="hidden" name="shippingAddressPostalCode" id="shippingAddressPostalCode" value="<?= $inf_compra['end_entrega'][0]; ?>"/>
                <input type="hidden" name="shippingAddressStreet" id="shippingAddressStreet" value="<?= $inf_compra['end_entrega'][1]; ?>"/>
                <input type="hidden" name="shippingAddressNumber" id="shippingAddressNumber" value="<?= $inf_compra['end_entrega'][2]; ?>"/>
                <input type="hidden" name="shippingAddressComplement" id="shippingAddressComplement" value="<?= $inf_compra['end_entrega'][3]; ?>"/>
                <input type="hidden" name="shippingAddressDistrict" id="shippingAddressDistrict" value="<?= $inf_compra['end_entrega'][4]; ?>"/>
                <input type="hidden" name="shippingAddressCity" id="shippingAddressCity" value="<?= $inf_compra['end_entrega'][5]; ?>"/>
                <input type="hidden" name="shippingAddressState" id="shippingAddressState" value="<?= $inf_compra['end_entrega'][6]; ?>"/>
                <input type="hidden" name="shippingAddressCountry" id="shippingAddressCountry" value="BRA"/>
            </div>

            <h4>Agendamento da entrega</h4>
            <div class="agendComp">
                <?= $inf_compra['agend_horario']; ?>
            </div>

            <div class="CardsData">
                <h4>Dados do cartao</h4>
                <label for="inputNumCard">Numero:</label>
                <input type="text" name="inputNumCard" class="numberCard" id="inputNumCard"/>
                <span class="brandCard"></span>
                <br/>
                
                <input type="hidden" name="inputBrandCard" id="inputBrandCard"/>

                <label for="inputCvvCard">CVV:</label>
                <input type="text" name="inputCvvCard" class="porcent" id="inputCvvCard"/><br/>

                <label for="inputMonthValid">Mes de validade (mm):</label>
                <input type="text" name="inputMonthValid" class="month" id="inputMonthValid"/><br/>

                <label for="inputYearValid">Ano de validade (aaaa):</label>
                <input type="text" name="inputYearValid" class="year" id="inputYearValid"/><br/>

                <label for="selQtdParc">Quantidade de parcelas:</label>
                <select name="selQtdParc" id="selQtdParc" disabled></select><br/>
                
                <input type="hidden" name="inputParcValue" id="inputParcValue"/><br/>

                <label for="creditCardHolderCPF">CPF:</label>
                <input type="text" name="creditCardHolderCPF" class="cpf" id="creditCardHolderCPF"/><br/>

                <label for="creditCardHolderName">Nome:</label>
                <input type="text" name="creditCardHolderName" id="creditCardHolderName"/><br/>

                <label for="creditCardHolderBirthDate">Data de Nascimento:</label>
                <input type="text" name="creditCardHolderBirthDate" class="date" id="creditCardHolderBirthDate"/><br/>

                <label>Telefone:</label>
                <input type="text" name="creditCardHolderAreaCode" id="creditCardHolderAreaCode" placeholder="DDD" class="month"/>
                <input type="text" name="creditCardHolderPhone" id="creditCardHolderPhone" placeholder="Número" class="numberPhone"/><br/>
                <div class="help-card"></div>

                <h4>Endereco da fatura do cartao</h4>
                
                <input type="radio" value="1" name="billingAddress" id="sameAddress" checked/>
                <label for="sameAddress"> Mesmo endereco da entrega</label>
                <div class="divEndFatura">
                    <?= $inf_compra['end_entrega'][0]; ?><br/>
                    <?= $inf_compra['end_entrega'][1] . " nº " . $inf_compra['end_entrega'][2]; ?><br/>
                    <?= (($inf_compra['end_entrega'][3] != "") ? ", {$inf_compra['end_entrega'][3]}<br/>" : ""); ?>
                    <?= $inf_compra['end_entrega'][4]; ?><br/>
                    <?= $inf_compra['end_entrega'][5] . " - " . $inf_compra['end_entrega'][6]; ?>
                    
                    <input type="hidden" name="billingAddressPostalCode" id="billingAddressPostalCode" value="<?= $inf_compra['end_entrega'][0]; ?>"/>
                    <input type="hidden" name="billingAddressStreet" id="billingAddressStreet" value="<?= $inf_compra['end_entrega'][1]; ?>"/>
                    <input type="hidden" name="billingAddressNumber" id="billingAddressNumber" value="<?= $inf_compra['end_entrega'][2]; ?>"/>
                    <input type="hidden" name="billingAddressComplement" id="billingAddressComplement" value="<?= $inf_compra['end_entrega'][3]; ?>"/>
                    <input type="hidden" name="billingAddressDistrict" id="billingAddressDistrict" value="<?= $inf_compra['end_entrega'][4]; ?>"/>
                    <input type="hidden" name="billingAddressCity" id="billingAddressCity" value="<?= $inf_compra['end_entrega'][5]; ?>"/>
                    <input type="hidden" name="billingAddressState" id="billingAddressState" value="<?= $inf_compra['end_entrega'][6]; ?>"/>
                    <input type="hidden" name="billingAddressCountry" id="billingAddressCountry" value="BRA"/>
                </div><br/>
                
                <input type="radio" value="0" name="billingAddress" id="otherAddress"/>
                <label for="otherAddress"> Outro endereco</label>
                <div class="divOtherEndFatura" style="display:none;">
                    <label for="billingAddressOtherPostalCode">CEP: </label><input type="text" class="cep" name="billingAddressOtherPostalCode" id="billingAddressOtherPostalCode"/>
                    <span class="answer-cep"></span><br/>
                    <label for="billingAddressOtherStreet">Rua: </label><input type="text" name="billingAddressOtherStreet" id="billingAddressOtherStreet"/><br/>
                    <label for="billingAddressOtherNumber">Numero: </label><input type="text" name="billingAddressOtherNumber" id="billingAddressOtherNumber"/><br/>
                    <label for="billingAddressOtherComplement">Complemento: </label><input type="text" name="billingAddressOtherComplement" id="billingAddressOtherComplement"/><br/>
                    <label for="billingAddressOtherDistrict">Bairro: </label><input type="text" name="billingAddressOtherDistrict" id="billingAddressOtherDistrict"/><br/>
                    <label for="billingAddressOtherCity">Cid: </label><input type="text" name="billingAddressOtherCity" id="billingAddressOtherCity"/><br/>
                    <label for="billingAddressOtherState">Estado: </label><input type="text" name="billingAddressOtherState" id="billingAddressOtherState"/>
                    <input type="hidden" name="billingAddressOtherCountry" id="billingAddressOtherCountry" value="BRA"/>
                </div>
            </div>

            <input type="hidden" name="inputTokenCard" id="inputTokenCard"/><br/>
            <input type="hidden" name="inputHashSender" id="inputHashSender"/><br/>

            <button type="submit" name="btnBuyPagSeguro" id="btnBuyPagSeguro">Comprar</button>
        </form>

        <div class="listPayments"></div>

        <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
        <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
        <script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
        <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
        <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
        <script src="<?= SCRIPT_PAGSEGURO; ?>"></script>
        <script src="<?= Project::baseUrl(); ?>functions/pagseguro/js/pagseguro.js"></script>
        <script>
            $("#billingAddressOtherPostalCode").keyup(function(){
                if($(this).val().length == 9) {
                    $.ajax({
                        url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
                        dataType: 'json',
                        beforeSend: function() {
                            $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;` + loadingResSmall(`Buscando...`));
                            $("#billingAddressOtherStreet").val(``);
                            $("#billingAddressOtherComplement").val(``);
                            $("#billingAddressOtherDistrict").val(``);
                            $("#billingAddressOtherState").val(``);
                            $("#billingAddressOtherCity").val(``);
                        },
                        success: function(resposta) {
                            if(resposta.erro) {
                                $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;<small style="color:#A94442;" class="smallAnswer">Endereço inexistente</small>`);
                            } else {
                                $(".answer-cep").html(``);
                                $("#billingAddressOtherStreet").val(resposta.logradouro);
                                $("#billingAddressOtherComplement").val(resposta.complemento);
                                $("#billingAddressOtherDistrict").val(resposta.bairro);
                                $("#billingAddressOtherState").val(resposta.uf);
                                $("#billingAddressOtherCity").val(resposta.localidade);
                                $("#billingAddressOtherNumber").focus();
                            }
                        }
                    });
                } else {
                    $(".answer-cep").html(``);
                    $("#billingAddressOtherStreet").val(``);
                    $("#billingAddressOtherComplement").val(``);
                    $("#billingAddressOtherDistrict").val(``);
                    $("#billingAddressOtherState").val(``);
                    $("#billingAddressOtherCity").val(``);
                }
            });
        </script>
        <?php
    else:?>
        <div class="msgNoProds">
            <h3><?= $inf_compra['error']; ?></h3>
        </div>
        <?php
    endif;
    ?>
</body>
</html>