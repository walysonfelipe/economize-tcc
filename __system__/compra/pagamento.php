<?php 
    use Model\{Cart, User};
    $cart = Cart::getCart();

    $sql = new Sql();

    require_once "__system__/functions/pagseguro/configuration.php";

    $_SESSION['pagamento'] = true;

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

<ul class="progress-tracker progress-tracker--word progress-tracker--word-left progress-tracker--center anim-ripple-large">
    <li class="progress-step is-complete compCart">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 1</h4>
            <i class="fas fa-shopping-cart"></i> CARRINHO
        </span>
    </li>
    <li class="progress-step is-complete compEnd">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 2</h4>
            <i class="fas fa-map-marker-alt"></i> ENDEREÇO
        </span>
    </li>
    <li class="progress-step is-complete compAgend">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 3</h4>
            <i class="far fa-clock"></i> AGENDAMENTO
        </span>
    </li>
    <li class="progress-step is-active">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 4</h4>
            <i class="far fa-credit-card"></i> PAGAMENTO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 5</h4>
            <i class="fas fa-file-alt"></i> EXTRATO
        </span>
    </li>
</ul>

<?php
    if (isset($_SESSION['subcid_frete'])) {
        $frete = Project::formatPriceToDolar($_SESSION['subcid_frete']);
    }
    $totCompra = Project::formatPriceToDolar($_SESSION['totCompra']);
?>

<!-- <div class="divShowTotPag">
    <h2 class="summaryTitle">RESUMO</h2>
    <div class="divisorSummary"></div>
    <div class="summarySubTitles">
        <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$<?= $totCompra; ?></h2>
    </div>
    <div class="summarySubTitles">
        <h2 class="totalFrete">FRETE:</h2><h2 class="valueFrete">R$<?= isset($frete) ? $frete : '0,00'; ?></h2>
    </div>
</div> -->

<div>
    <h2 align="center" class="tituloOfertas"><i class="far fa-credit-card"></i> PAGAMENTO</h2>
</div>
<div class="divAgend">
<?php 
    if ($inf_compra['status']):?>
        <form id="formBuyPagSeguro">
            <div class="pagseguroLogo">
                <?php
                    if ($sandbox):?>
                        <img class="imgPagseguroLogo" src="<?= Project::baseUrl(); ?>style/img/pagseguro/218x35-t.png"/>
                        <?php
                    endif;
                ?>
            </div>
            <div class="paymentMethod_div">
                <input type="radio" name="paymentMethod" id="paymentMethodCreditCard" value="creditCard" checked/>
                <label for="paymentMethodCreditCard"> Cartão de crédito</label>

                <input type="radio" name="paymentMethod" id="paymentMethodBoleto" value="boleto"/>
                <label for="paymentMethodBoleto"> Boleto</label>

                <input type="radio" name="paymentMethod" id="paymentMethodEft" value="eft"/>
                <label for="paymentMethodEft"> Débito online</label>
            </div>

            <div class="divMethodCard">
                <a href="#" class="a_hrefPayment a_cardDiv">Ver cartões aceitos</a>
                <div class="divShowCards"></div>
            </div>

            <div class="divMethodBoleto"></div>

            <div class="divDebitoOnline">
                <h4 class="subTitPag">Selecione o banco:</h4>
                <select class="selectInstallments" name="bankName" id="bankName">

                </select>
            </div>
            
            <input type="hidden" name="receiverEmail" id="receiverEmail" value="<?= EMAIL_LOJA; ?>"/>
            <input type="hidden" name="currency" id="currency" value="<?= MOEDA_PAGAMENTO; ?>"/>

            <input type="hidden" name="amount" id="amount" value="<?= $totCompra; ?>"/>
            <input type="hidden" name="extraAmount" id="extraAmount" value="<?= isset($totCupom) ? "-" . $totCupom : ""; ?>"/>
            
            <input type="hidden" name="notificationURL" id="notificationURL" value="<?= URL_NOTIFICATION; ?>"/>
            <input type="hidden" name="reference" id="reference" value="ECONOMIZE0101"/>

            <!-- Dados do comprador -->
            <div class="infComp">
                <input type="hidden" name="inputSenderName" id="inputSenderName" value="<?= $inf_compra['client']['usu_first_name'] . " " . $inf_compra['client']['usu_last_name']; ?>"/>
                <input type="hidden" name="inputSenderCPF" id="inputSenderCPF" value="<?= $inf_compra['client']['usu_cpf']; ?>"/>
                <input type="hidden" name="inputSenderDDD" id="inputSenderDDD" value="<?= $inf_compra['client']['tel_ddd']; ?>"/>
                <input type="hidden" name="inputSenderNum" id="inputSenderNum" value="<?= $inf_compra['client']['tel_num']; ?>"/>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" class="placeholder-shown" name="inputSenderEmail" id="inputSenderEmail" placeholder=" "/>
                        <label class="labelFieldCad" for="inputSenderEmail"><strong>SEU EMAIL</strong></label>
                    </div>
                    <div class="help-block-email-pag">
                        <small>* Este tem de ser o seu email do pagseguro</small>
                    </div>
                    <div class="help-block-pag"></div><br/>
                </div>
            </div>

            <!-- Endereco da entrega -->
            <div class="endComp">
                <!-- Tipo de entrega -->
                <input type="hidden" name="shippingType" id="shippingType" value="3"/>

                <!-- Valor frete -->
                <input type="hidden" name="shippingCost" id="shippingCost" value="<?= isset($_SESSION['subcid_frete']) ? $_SESSION['subcid_frete'] : '0.00'; ?>"/>

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

            <div class="CardsData">
                <h3 class="subTitPag">DADOS DO CARTÃO</h3>
                
                <div class="divNumberCard">
                    <div class="outsideSecInputCad">
                        <div class="field -md">
                            <input type="text" name="inputNumCard" placeholder=" " class="placeholder-shown numberCard" id="inputNumCard"/>
                            <label class="labelFieldCad" for="inputNumCard">NÚMERO</label>
                        </div>
                    </div>
                    <span class="brandCard"></span>
                </div>
                
                <input type="hidden" name="inputBrandCard" id="inputBrandCard"/>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" name="inputCvvCard" placeholder=" " class="placeholder-shown porcent" id="inputCvvCard"/>
                        <label class="labelFieldCad" for="inputCvvCard">CVV</label>
                    </div>
                </div>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" placeholder=" " name="inputMonthValid" class="placeholder-shown month" id="inputMonthValid"/>
                        <label class="labelFieldCad" for="inputMonthValid">MÊS DE VALIDADE (xx)</label>
                    </div>
                </div>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" placeholder=" " name="inputYearValid" class="placeholder-shown year" id="inputYearValid"/>
                        <label class="labelFieldCad" for="inputYearValid">ANO DE VALIDADE (xxxx):</label>
                    </div>
                </div>
                
                <div class="divInstallments">
                    <label for="selQtdParc" id="labelPagQuantity">Pague em até 18 vezes</label><br/>
                    <select name="selQtdParc" class="selectInstallments" id="selQtdParc" disabled></select>
                </div>
                
                <input type="hidden" name="inputParcValue" id="inputParcValue"/>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" placeholder=" " name="creditCardHolderCPF" class="placeholder-shown cpf" id="creditCardHolderCPF"/>
                        <label class="labelFieldCad" for="creditCardHolderCPF">CPF DO DONO DO CARTÃO</label>
                    </div>
                </div>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" placeholder=" " class="placeholder-shown" name="creditCardHolderName" id="creditCardHolderName"/>
                        <label class="labelFieldCad" for="creditCardHolderName">NOME DO DONO DO CARTÃO:</label>
                    </div>
                    <div class="help-block-name-card">
                        <small>Ex.: CARLOS A F DE OLIVEIRA</small>
                    </div>
                </div><br/>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" placeholder=" " name="creditCardHolderBirthDate" class="placeholder-shown date" id="creditCardHolderBirthDate"/>
                        <label class="labelFieldCad" for="creditCardHolderBirthDate">DATA DE NASCIMENTO</label>
                    </div>
                    <div class="help-block-birthday-card">
                        <small>Ex.: 10/03/1992</small>
                    </div>
                </div><br/>
                
                <div class="outsideSecInputCad">
                    <div class="field -md">
                        <input type="text" placeholder=" " name="creditCardHolderPhone" id="creditCardHolderPhone" class="placeholder-shown sp_celphones"/>
                        <label class="labelFieldCad" for="creditCardHolderPhone">TELEFONE</label>
                    </div>
                </div>

                <h3 class="subTitPag">ENDEREÇO DA FATURA DO CARTÃO</h3>

                <div class="divFaturaEndereco">
                    <input type="radio" value="1" name="billingAddress" id="sameAddress" checked/>
                    <label for="sameAddress"> Mesmo endereço da entrega</label>
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
                    <label for="otherAddress"> Outro endereço</label>
                    <div class="divOtherEndFatura" style="display:none;">
                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown cep" name="billingAddressOtherPostalCode" id="billingAddressOtherPostalCode"/>
                                <label class="labelFieldCad" for="billingAddressOtherPostalCode">CEP</label>
                            </div>
                            <span class="answer-cep"></span>
                        </div>
                        
                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown" name="billingAddressOtherStreet" id="billingAddressOtherStreet"/>
                                <label class="labelFieldCad" for="billingAddressOtherStreet">LOGRADOURO</label>
                            </div>
                        </div>

                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown" name="billingAddressOtherNumber" id="billingAddressOtherNumber"/>
                                <label class="labelFieldCad" for="billingAddressOtherNumber">NÚMERO</label>
                            </div>
                        </div>
                        
                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown" name="billingAddressOtherComplement" id="billingAddressOtherComplement"/>
                                <label class="labelFieldCad" for="billingAddressOtherComplement">COMPLEMENTO</label>
                            </div>
                        </div>

                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown" name="billingAddressOtherDistrict" id="billingAddressOtherDistrict"/>
                                <label class="labelFieldCad" for="billingAddressOtherDistrict">BAIRRO</label>
                            </div>
                        </div>
                        
                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown" name="billingAddressOtherCity" id="billingAddressOtherCity"/>
                                <label class="labelFieldCad" for="billingAddressOtherCity">CIDADE</label>
                            </div>
                        </div>

                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" placeholder=" " class="placeholder-shown" name="billingAddressOtherState" id="billingAddressOtherState"/>
                                <label class="labelFieldCad" for="billingAddressOtherState">ESTADO</label>
                            </div>
                        </div>
                        <input type="hidden" name="billingAddressOtherCountry" id="billingAddressOtherCountry" value="BRA"/>
                    </div>
                </div>
            </div><br/>

            <input type="hidden" name="inputTokenCard" id="inputTokenCard"/>
            <input type="hidden" name="inputHashSender" id="inputHashSender"/>

            <div class="divSaveCard">
                <input type="checkbox" name="inputSaveCard" id="inputSaveCard" checked/>
                <label for="inputSaveCard"> Salvar cartão para a próxima compra</label>
            </div><br/>

            <button type="submit" class="btnPag" name="btnBuyPagSeguro" id="btnBuyPagSeguro">
                Efetuar pagamento
            </button>
            <div class="help-submit-pag"></div>
        </form>
</div>

        <script src="<?= SCRIPT_PAGSEGURO; ?>"></script>
        <script src="<?= Project::baseUrl(); ?>functions/pagseguro/js/pagseguro.js"></script>
        <script>
            $('.is-complete').css({'cursor': 'pointer'});
            $('.compCart').click(function(e) {
                e.preventDefault();
                
                buscaCarrinho();
            });
            $('.compEnd').click(function(e) {
                e.preventDefault();
                
                buscaEndereco();
            });
            $('.compAgend').click(function(e) {
                e.preventDefault();
                
                buscaAgendamento();
            });

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

            mask();
        </script>
        <?php
    else:?>
        <div class="msgNoProds">
            <h3><?= $inf_compra['error']; ?></h3>
        </div>
        <script>
            $('.is-complete').css({'cursor': 'pointer'});
            $('.compCart').click(function(e) {
                e.preventDefault();
                
                buscaCarrinho();
            });
            $('.compEnd').click(function(e) {
                e.preventDefault();
                
                buscaEndereco();
            });
            $('.compAgend').click(function(e) {
                e.preventDefault();
                
                buscaAgendamento();
            });
        </script>
        <?php
    endif;
?>