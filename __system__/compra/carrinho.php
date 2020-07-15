<?php
    use Model\Cart;
    $cart = Cart::getCart();
    
    // print_r($_SESSION['cupom_compra']);
    // exit;
?>
<ul class="progress-tracker progress-tracker--word progress-tracker--word-left progress-tracker--center anim-ripple-large">
    <li class="progress-step is-active">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 1</h4>
            <i class="fas fa-shopping-cart"></i> CARRINHO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 2</h4>
            <i class="fas fa-map-marker-alt"></i> ENDEREÇO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 3</h4>
            <i class="far fa-clock"></i> AGENDAMENTO
        </span>
    </li>
    <li class="progress-step">
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
<h2 align="center" class="tituloOfertas"><i class="fas fa-shopping-cart"></i> MEU CARRINHO</h2>
<?php
    if (!$cart['empty']):?>
        <div class="divShowOpt">
            <h2 class="summaryTitle">RESUMO</h2>
            <div class="divisorSummary"></div>
            <div class="summarySubTitles">
                <h3 class="totalDesc">DESCONTOS:</h3><h3 class="valueDesc">- R$<?= $cart['totDesconto']; ?></h3>
            </div>
            <div class="summarySubTitles">
                <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$<?= $cart['totCompra']; ?></h2>
            </div>
            <?php
                if (isset($_SESSION['subcid_frete'])):?>
                    <div class="summarySubTitles">
                        <h2 class="totalFrete">FRETE:</h2><h2 class="valueFrete">R$<?= $cart['frete']; ?></h2>
                    </div>
                    <script>
                        $('.divShowTot').css({'height':'auto'});
                    </script>
                    <?php
                endif;
            ?>
        </div>
        <div class="divShowOptBtn">
            <div class="inlineDivShowOptBtn">
                <a class="linkShop" href="<?= Project::baseUrlPhp(); ?>">CONTINUAR COMPRANDO<br><i class="fas fa-arrow-left"></i></a>
            </div>
            <div class="inlineDivShowOptBtn">
                <button class="limparCart">LIMPAR CARRINHO<br><i class="far fa-trash-alt"></i></button>
            </div>
            <div class="inlineDivShowOptBtn">
                <button class="addCupom">ADICIONAR CUPOM<br><i class="fas fa-tag"></i></button>
            </div>
            <div class="divAddCupom"></div>
            <div class="divAnswer"></div>
            <div class="inlineDivShowOptBtn">
                <button class="finalizaCompra">PRÓXIMO PASSO<br><i class="fas fa-arrow-right"></i></button>
            </div>    
        </div>
        <div class="divTable">
            <table class="divShowProdFav tableCart" width="100%" padding="0" margin="0">
                <tr class="trNames">
                    <th>PRODUTO</th>
                    <th>QUANTIDADE</th>
                    <th>PREÇO</th>
                    <th>SUBTOTAL</th>
                    <th></th>
                </tr>
                <?php
                    foreach ($cart['produtosCart'] as $v):?>
                        <tr class="trCart">
                            <td class="tdCart" width="50%">
                                <img class="imgCart" src="<?= Project::baseUrlAdm() . "img-produtos/" . $v['produto_img']; ?>"/>
                                <h5 class="titleProdCart">
                                    <?= $v['produto_nome'] . " - " . $v['produto_tamanho']; ?>
                                </h5>
                                <h5 class="brandProdCart"><?= $v['marca_nome']; ?></h5>
                            </td>
                            <td class="tdCart" width="15%">
                                <input type='number' min='0' max='20' class="qtdProdCart" id-prod="<?= $v['produto_id']; ?>" value="<?= $v['carrinho']; ?>">
                            </td>
                            <td class="tdCart" width="15%">
                                <?php
                                    if ($v['produto_desconto_porcent'] || $v['promo_desconto']):?>
                                        <h3 class="descProdCart">R$<?= $v['produto_preco']; ?></h3>
                                        <h3 class="priceProdCart">R$<?= $v['produto_desconto']; ?></h3>
                                        <?php
                                    else:?>
                                        <h3 class="descProdCart">-</h3>
                                        <h3 class="priceProdCart">R$<?= $v['produto_preco']; ?></h3>
                                        <?php
                                    endif;
                                ?>
                            </td>
                            <td class="tdCart" width="15%">
                                <h3 class="priceProdCart subtot<?= $v['produto_id']; ?>">
                                    R$<?= $v['subtotal']; ?>
                                </h3>
                            </td>
                            <td class="tdCart" width="5%">
                                <button class="tirarProd btnProdCart" id-prod="<?= $v['produto_id']; ?>" title="Remova '<?= $v['produto_nome'] . " - " . $v['produto_tamanho']; ?>' do carrinho"><i class="far fa-times-circle"></i></button>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                ?>
            </table>
        </div>
        <div class="divShowTot">
            <h2 class="summaryTitle">RESUMO</h2>
            <div class="divisorSummary"></div>
            <div class="summarySubTitles">
                <h3 class="totalDesc">DESCONTOS:</h3><h3 class="valueDesc">- R$<?= $cart['totDesconto']; ?></h3>
            </div>
            <div class="summarySubTitles">
                <h2 class="totalPrice">TOTAL DA COMPRA:</h2><h2 class="valueBuy">R$<?= $cart['totCompra']; ?></h2>
            </div>
            <?php
                if (isset($_SESSION['subcid_frete'])):?>
                    <div class="summarySubTitles">
                        <h2 class="totalFrete">FRETE:</h2><h2 class="valueFrete">R$<?= $cart['frete']; ?></h2>
                    </div>
                    <script>
                        $('.divShowTot').css({'height':'auto'});
                    </script>
                    <?php
                endif;
            ?>
        </div>
        <div class="divShowOptDesk">
            <button class="limparCart">LIMPAR CARRINHO <i class="far fa-trash-alt"></i></button>
            <div class="divButtonCupom">
                <button class="addCupom">ADICIONAR CUPOM <i class="fas fa-tag"></i></button>
            </div>
            <div class="divAddCupom"></div>
            <div class="divAnswer"></div>
            <button class="finalizaCompra">PRÓXIMO PASSO <i class="fas fa-arrow-right"></i></button><br>
            <a class="linkShop" href="<?= Project::baseUrlPhp(); ?>"><i class="fas fa-arrow-left"></i> CONTINUAR COMPRANDO</a>
        </div>
        <script>
            attCarrinho();
            verificaCupom();
            botaoAddCupom();

            $('.finalizaCompra').click(function() {
                <?php
                    if ($cart['logado']):?>
                        buscaEndereco();
                        <?php
                    else:?>
                        Toast.fire({
                            type: 'error',
                            title: 'Você precisa estar logado'
                        });

                        $("#usu_email_login").val("");
                        $("#usu_senha_login").val("");
                        $(".help-block-login").html("");
                        modal.style.display = "block";
                        <?php
                    endif;
                ?>
            });
        </script>
        <?php
    else:?>
        <div class="divTable">
            <center>
                <img src="<?= Project::baseUrl(); ?>style/img/banner/cart.png" class="imgEmptyCart" alt="Carrinho está vazio!" title="Seu carrinho está vazio!">
            </center>
        </div>
        <?php
    endif;
?>