<?php
    $empty = FALSE;
    if(!isset($URL[2])):?>
        <div class="center_header">
            <div class="tilteFilterProd">
                <h4><i class="<?= $result[0]["depart_icon"]; ?>"></i> <?= $result[0]["depart_nome"]; ?></h4>
            </div>
            <h5><?= $result[0]["depart_desc"]; ?></h5>
            <?php
            $sel = $conn->prepare("SELECT * FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ");
            $sel->execute();
            if($sel->rowCount() == 0):?>
                <div class="msgNoProds">
                    <h3>Este departamento está vazio, por enquanto!</h3>
                </div>
                <?php
                $empty = TRUE;
            endif;
            ?>
        </div>

        <?php
        if(!$empty):
            $sel = $conn->prepare("SELECT * FROM subcateg WHERE depart_id={$_SESSION[Department::SESSION]['depart_id']} ORDER BY subcateg_nome");
            $sel->execute();
            $result = $sel->fetchAll();?>
            <div class="filtro_pesquisaMobile">
                <h5 class="titleFilter"><i class="fas fa-sliders-h"></i> FILTROS DE PESQUISA</h5>
                <div class="divFilter">
                    <label for="href" class="titleConfigFilter"><i class="fas fa-font"></i> CATEGORIA</label>
                    <select class="selectFilter categ">
                        <option selected disabled> Filtrar </option>
                        <?php
                        foreach($result as $v):?>
                            <option value="<?= $v['subcateg_nome']; ?>"><?= $v['subcateg_nome']; ?></option>
                            <?php
                        endforeach;?>
                    </select>
                </div>
                <?php
                $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                $sel2->execute();
                $result2 = $sel2->fetchAll();?>
                <div class="divFilter">
                    <label class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME</label>
                    <select class="selectFilter produto_tamanho">
                        <option selected disabled value="*000*"> Filtrar </option>
                        <?php
                        foreach($result2 as $v):?>
                            <option value="<?= $v['tam']; ?>"><?= $v['tam']; ?></option>
                            <?php
                        endforeach;?>
                    </select>
                </div>
                <div class="divFilter">
                <?php
                $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                $sel2->execute();
                $result2 = $sel2->fetchAll();?>
                    <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label> 
                    <select class="selectFilter prod_marca">
                        <option selected disabled value="*000*"> Filtrar </option>
                        <?php
                        foreach($result2 as $v):?>
                            <option value="<?= $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></option>
                            <?php
                        endforeach;?>
                    </select>
                </div>
                <div class="divFilter">
                    <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                    <select class="selectFilter prod_preco">
                        <option selected disabled value="*000*"> Filtrar </option>
                        <option value="DESC">Maior Preço</option>
                        <option value="ASC">Menor Preço</option>
                    </select>
                </div>
                <div class="divFilter">
                    <label class="titleConfigFilter filterFav" for="fav_radio"><i class="fas fa-heart"></i> FAVORITOS</label>
                    <input type="radio" name="fav_radio" class="fav_radio prod_fav" id="fav_radio"/>
                </div>
            </div>

            <!-- FILTROS PARA TELAS GRANDES -->

            <div class="filtro_pesquisa">
                <div class="divTitleFilter">
                    <h5 class="titleFilter">FILTROS DE PESQUISA</h5>
                </div>
                <div class="divFilter">
                    <label for="href" class="titleConfigFilter"><i class="fas fa-font"></i> CATEGORIA</label>
                    <ul class="listFilterOptions">
                    <?php
                    foreach($result as $v):?>
                        <li class="celulaListFilterOpt" value="<?= $v['subcateg_nome']; ?>">
                            <input id="<?= $v['subcateg_nome'].$v['subcateg_id']; ?>" class="categ" type="radio" value="<?= $v['subcateg_nome']; ?>"> 
                            <label for="<?= $v['subcateg_nome'].$v['subcateg_id']; ?>"><?= $v['subcateg_nome']; ?></label>
                        </li>
                        <?php
                    endforeach;?>
                    </ul>
                </div>
                <?php
                $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                $sel2->execute();
                $result2 = $sel2->fetchAll();?>
                <div class="divFilter">
                    <label for="href" class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME</label>
                    <ul class="listFilterOptions">
                    <?php
                    foreach($result2 as $k => $v):?>
                        <li class="celulaListFilterOpt"><input type="radio" name="prod_tam" id="<?= $k; ?>" class="produto_tamanho" value="<?= $v['tam']; ?>"/> <label for="<?= $k; ?>"><?= $v['tam']; ?></label></li>
                        <?php
                    endforeach;?>
                    </ul>
                </div>
                <?php
                $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                $sel2->execute();
                $result2 = $sel2->fetchAll();?>
                <div class="divFilter">
                    <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label>
                    <ul class="listFilterOptions">
                    <?php
                    foreach($result2 as $k => $v):?>
                        <li class="celulaListFilterOpt"><input type="radio" name="produto_marca" id="<?= $k . $v['marca_nome']; ?>" class="prod_marca" value="<?= $v['marca_nome']; ?>"/> <label for="<?= $k . $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></label></li>
                        <?php
                    endforeach;?>
                    </ul>
                </div>
                <div class="divFilter">
                    <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                    <ul class="listFilterOptions">
                        <li class="celulaListFilterOpt">
                            <input type="radio" name="produto_preco" class="prod_preco" id="me_p" value="ASC"> <label for="me_p">Menor preço</label>
                        </li>
                        <li class="celulaListFilterOpt">
                            <input type="radio" name="produto_preco" class="prod_preco" id="ma_p" value="DESC"> <label for="ma_p">Maior preço</label>
                        </li>
                    </ul>
                </div>
                <div class="divFilter">
                    <label class="titleConfigFilter filterFav"><i class="fas fa-heart"></i> FAVORITOS</label>
                    <ul class="listFilterOptions">
                        <li class="celulaListFilterOpt">
                            <input type="radio" name="produto_fav" class="prod_fav" id="fav_rad"> <label for="fav_rad">Favoritos</label>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="divShowProdFilter">
                <?php
                $sel2 = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id LEFT JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id LEFT JOIN promocao_temp AS pr ON dp.promo_id=pr.promo_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ");
                $_SESSION[Department::SESSION]['rawQuery'] = "SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto AS p JOIN marca_prod AS m ON m.marca_id=p.produto_marca JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id LEFT JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id LEFT JOIN promocao_temp AS pr ON dp.promo_id=pr.promo_id WHERE s.depart_id={$_SESSION[Department::SESSION]['depart_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ";
                $sel2->execute();
                while($v = $sel2->fetch( PDO::FETCH_ASSOC )):?>
                    <div class="prodFilter">
                        <div class="btnFavoriteFilter btnFavorito<?= $v['produto_id']; ?>">
                            
                        </div>
                        <a class="linksProdCarousel" id-produto="<?= $v['produto_id']; ?>">
                            <img src="<?= base_url(); ?>admin-area/img-produtos/<?= $v["produto_img"]; ?>"/>
                            <?php 
                                if($v['produto_desconto_porcent'] <> "") {
                                    $v["produto_desconto"] = $v["produto_preco"]*($v["produto_desconto_porcent"]/100);
                                    $v["produto_desconto"] = number_format($v["produto_desconto"], 2, '.', '');
                                    $v["produto_desconto"] = $v["produto_preco"]-$v["produto_desconto"];
                                    $v["produto_desconto"] = number_format($v["produto_desconto"], 2, ',', '.');
                                } elseif($v['promo_desconto']) {
                                    $v["produto_desconto"] = $v["produto_preco"]*($v["promo_desconto"]/100);
                                    $v["produto_desconto"] = number_format($v["produto_desconto"], 2, '.', '');
                                    $v["produto_desconto"] = $v["produto_preco"]-$v["produto_desconto"];
                                    $v["produto_desconto"] = number_format($v["produto_desconto"], 2, ',', '.');
                                }
                                $v['produto_preco'] = number_format($v["produto_preco"], 2, ',', '.');
                            ?>
                            <?= isset($v["produto_desconto"]) ? '<p class="divProdPromo">-' . $v['produto_desconto_porcent'] . $v['promo_desconto'] . '%</p>' : '' ; ?>
                            <div class='divisorFilter'></div>
                            <h5 class='titleProdFilter'><?= $v["produto_nome"]; ?> - <?= $v["produto_tamanho"]; ?></h5>
                            <p class='priceProdFilter'>
                                <?= isset($v["produto_desconto"]) ? '<span class="divProdPrice1">R$' . $v['produto_preco'] . '</span> R$' . $v['produto_desconto'] : 'R$ ' . $v["produto_preco"]; ?>
                            </p>
                        </a>
                        <div>
                            <?php 
                                if($v["produto_qtd"] > 0):?>
                                    <form class="formBuy">
                                        <input type="hidden" value="<?= $v["produto_id"]; ?>" name="id_prod"/>
                                        <input type="number" min="0" max="20" value="<?= isset($_SESSION[Cart::SESSION][$v['produto_id']]) ? $_SESSION[Cart::SESSION][$v['produto_id']] : 0 ; ?>" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                        <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                    </form>
                                    <?php
                                else:?>
                                    <span class="esgotQtdFilter">ESGOTADO</span>
                                    <form class="formBuy">
                                        <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                    </form>
                                    <?php
                                endif;
                            ?>
                        </div>
                    </div>
                    <?php
                endwhile;?>
            </div><?php
        endif;
    else:
        if(!isset($URL[3])):?>
            <div class="center_header">
                <div class="tilteFilterProd">
                    <h4><i class="<?= $result[0]["depart_icon"]; ?>"></i> <?= $result2[0]["subcateg_nome"]; ?></h4>
                </div>
                <?php
                $sel = $conn->prepare("SELECT * FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ");
                $sel->execute();
                if($sel->rowCount() == 0):?>
                    <div class="msgNoProds">
                        <h3>Esta subcategoria está vazia, por enquanto!</h3>
                    </div>
                    <?php
                    $empty = TRUE;
                endif;
                ?>
            </div>

            <?php
            if(!$empty):
                $sel = $conn->prepare("SELECT * FROM categ WHERE subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']}");
                $sel->execute();
                if($sel->rowCount() > 0):?>
                    <div class="filtro_pesquisaMobile">
                        <h5 class="titleFilter"><i class="fas fa-sliders-h"></i> FILTROS DE PESQUISA</h5>
                        <div class="divFilter">
                            <label for="href" class="titleConfigFilter"><i class="fas fa-font"></i> SUBCATEGORIA</label>
                            <select class="selectFilter categ">
                                <option selected disabled> Filtrar </option>
                                <?php
                                $result = $sel->fetchAll();
                                foreach($result as $v):?>
                                    <option value="<?= $v['categ_nome']; ?>"><?= $v['categ_nome']; ?></option>
                                    <?php
                                endforeach;?>
                            </select>
                        </div>

                        <?php
                        $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                        $sel2->execute();
                        $result2 = $sel2->fetchAll();?>
                        <div class="divFilter">
                            <label class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME</label>
                            <select class="selectFilter produto_tamanho">
                                <option selected disabled value="*000*"> Filtrar </option>
                                <?php
                                foreach($result2 as $v):?>
                                    <option value="<?= $v['tam']; ?>"><?= $v['tam']; ?></option>
                                    <?php
                                endforeach;?>
                            </select>
                        </div>
                        <div class="divFilter">
                        <?php
                        $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                        $sel2->execute();
                        $result2 = $sel2->fetchAll();?>
                        <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label> 
                        <select class="selectFilter prod_marca">
                            <option selected disabled value="*000*"> Filtrar </option>
                            <?php
                            foreach($result2 as $v):?>
                                <option value="<?= $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></option>
                                <?php
                            endforeach;?>
                        </select>
                        </div>
                        <div class="divFilter">
                            <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                            <select class="selectFilter prod_preco">
                                <option selected disabled value="*000*"> Filtrar </option>
                                <option value="DESC">Maior Preço</option>
                                <option value="ASC">Menor Preço</option>
                            </select>
                        </div>
                        <div class="divFilter">
                            <label class="titleConfigFilter filterFav" for="fav_radio"><i class="fas fa-heart"></i> FAVORITOS</label>
                            <input type="radio" name="fav_radio" class="fav_radio prod_fav" id="fav_radio"/>
                        </div>
                    </div>
                    
                    <!-- FILTRO PARA TELAS GRANDES -->
                    <div class="filtro_pesquisa">
                        <div class="divTitleFilter">
                            <h5 class="titleFilter">FILTROS DE PESQUISA</h5>
                        </div>
                        <div class="divFilter">
                            <label for="href" class="titleConfigFilter"><i class="fas fa-font"></i> SUBCATEGORIA</label>
                            <ul class="listFilterOptions">
                            <?php
                            foreach($result as $v):?>
                                <li class="celulaListFilterOpt" value="<?= $v['subcateg_nome']; ?>"><input id="<?= $v['categ_nome'].$v['categ_id']; ?>" class="categ" type="radio" value="<?= $v['categ_nome']; ?>"> <label for="<?= $v['categ_nome'].$v['categ_id']; ?>"><?= $v['categ_nome']; ?></label></li>
                        <?php
                            endforeach;?>
                            </ul>
                        </div>
                        <?php
                        $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                        $sel2->execute();
                        $result2 = $sel2->fetchAll();?>
                        <div class="divFilter">
                            <label for="href" class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME</label>
                            <ul class="listFilterOptions">
                            <?php
                            foreach($result2 as $k => $v):?>
                                <li class="celulaListFilterOpt"><input type="radio" name="prod_tam" id="<?= $k; ?>" class="produto_tamanho" value="<?= $v['tam']; ?>"/> <label for="<?= $k; ?>"><?= $v['tam']; ?></label></li>
                                <?php
                            endforeach;?>
                            </ul>
                        </div>
                        <?php
                        $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                        $sel2->execute();
                        $result2 = $sel2->fetchAll();?>
                        <div class="divFilter">
                            <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label>
                            <ul class="listFilterOptions">
                            <?php
                            foreach($result2 as $v):?>
                                <li class="celulaListFilterOpt"><input type="radio" name="produto_marca" id="<?= $k . $v['marca_nome']; ?>" class="prod_marca" value="<?= $v['marca_nome']; ?>"/> <label for="<?= $k . $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></label></li>
                                <?php
                            endforeach;?>
                            </ul>
                        </div>
                        <div class="divFilter">
                            <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                            <ul class="listFilterOptions">
                                <li class="celulaListFilterOpt">
                                    <input type="radio" name="produto_preco" class="prod_preco" id="me_p" value="ASC"> <label for="me_p">Menor preço</label>
                                </li>
                                <li class="celulaListFilterOpt">
                                    <input type="radio" name="produto_preco" class="prod_preco" id="ma_p" value="DESC"> <label for="ma_p">Maior preço</label>
                                </li>
                            </ul>
                        </div>
                        <div class="divFilter">
                            <label class="titleConfigFilter filterFav"><i class="fas fa-heart"></i> FAVORITOS</label>
                            <ul class="listFilterOptions">
                                <li class="celulaListFilterOpt">
                                    <input type="radio" name="produto_fav" class="prod_fav" id="fav_rad"> <label for="fav_rad">Favoritos</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="divShowProdFilter">
                        <?php
                        $sel2 = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id LEFT JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id LEFT JOIN promocao_temp AS pr ON dp.promo_id=pr.promo_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ");
                        $_SESSION[Department::SESSION]['rawQuery'] = "SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto AS p JOIN marca_prod AS m ON m.marca_id=p.produto_marca JOIN categ AS c ON p.produto_categ=c.categ_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id LEFT JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id LEFT JOIN promocao_temp AS pr ON dp.promo_id=pr.promo_id WHERE c.subcateg_id={$_SESSION[Department::SESSION]['subcateg_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ";
                        $sel2->execute();
                        while($v = $sel2->fetch( PDO::FETCH_ASSOC )):?>
                            <div class="prodFilter">
                                <div class="btnFavoriteFilter btnFavorito<?= $v['produto_id']; ?>">
                                    
                                </div>
                                <a class="linksProdCarousel" id-produto="<?= $v['produto_id']; ?>">
                                    <img src="<?= base_url(); ?>admin-area/img-produtos/<?= $v["produto_img"]; ?>"/>
                                    <?php 
                                        if($v['produto_desconto_porcent'] <> "") {
                                            $v["produto_desconto"] = $v["produto_preco"]*($v["produto_desconto_porcent"]/100);
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, '.', '');
                                            $v["produto_desconto"] = $v["produto_preco"]-$v["produto_desconto"];
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, ',', '.');
                                        } elseif($v['promo_desconto']) {
                                            $v["produto_desconto"] = $v["produto_preco"]*($v["promo_desconto"]/100);
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, '.', '');
                                            $v["produto_desconto"] = $v["produto_preco"]-$v["produto_desconto"];
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, ',', '.');
                                        }
                                        $v['produto_preco'] = number_format($v["produto_preco"], 2, ',', '.');
                                    ?>
                                    <?= isset($v["produto_desconto"]) ? '<p class="divProdPromo">-' . $v['produto_desconto_porcent'] . $v['promo_desconto'] . '%</p>' : '' ; ?>
                                    <div class='divisorFilter'></div>
                                    <h5 class='titleProdFilter'><?= $v["produto_nome"]; ?> - <?= $v["produto_tamanho"]; ?></h5>
                                    <p class='priceProdFilter'>
                                        <?= isset($v["produto_desconto"]) ? '<span class="divProdPrice1">R$' . $v['produto_preco'] . '</span> R$' . $v['produto_desconto'] : 'R$ ' . $v["produto_preco"]; ?>
                                    </p>
                                </a>
                                <div>
                                    <?php 
                                        if($v["produto_qtd"] > 0):?>
                                            <form class="formBuy">
                                                <input type="hidden" value="<?= $v["produto_id"]; ?>" name="id_prod"/>
                                                <input type="number" min="0" max="20" value="<?= isset($_SESSION[Cart::SESSION][$v['produto_id']]) ? $_SESSION[Cart::SESSION][$v['produto_id']] : 0 ; ?>" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                                <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                            </form>
                                            <?php
                                        else:?>
                                            <span class="esgotQtdFilter">ESGOTADO</span>
                                            <form class="formBuy">
                                                <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                            </form>
                                            <?php
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <?php
                        endwhile;?>
                    </div><?php
                endif;
            endif;
        else:?>
            <div class="center_header">
                <div class="tilteFilterProd">
                    <h4><i class="<?= $result[0]["depart_icon"]; ?>"></i> <?= $result2[0]["subcateg_nome"]; ?> - <?= $result3[0]["categ_nome"]; ?></h4>
                </div>
                <?php
                $sel = $conn->prepare("SELECT * FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ");
                $sel->execute();
                if($sel->rowCount() == 0):?>
                    <div class="msgNoProds">
                        <h3>Esta categoria está vazia, por enquanto!</h3>
                    </div>
                    <?php
                    $empty = TRUE;
                endif;
                ?>
            </div>

            <?php
            if(!$empty):?>
                <div class="filtro_pesquisaMobile">
                    <?php
                    $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto AS p JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                    $sel2->execute();
                    $result2 = $sel2->fetchAll();?>
                    <div class="divFilter">
                        <label class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME</label>
                        <select class="selectFilter produto_tamanho">
                            <option selected disabled value="*000*"> Filtrar </option>
                            <?php
                            foreach($result2 as $v):?>
                                <option value="<?= $v['tam']; ?>"><?= $v['tam']; ?></option>
                                <?php
                            endforeach;?>
                        </select>
                    </div>
                    <div class="divFilter">
                    <?php
                    $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                    $sel2->execute();
                    $result2 = $sel2->fetchAll();?>
                    <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label> 
                    <select class="selectFilter prod_marca">
                        <option selected disabled value="*000*"> Filtrar </option>
                        <?php
                        foreach($result2 as $v):?>
                            <option value="<?= $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></option>
                            <?php
                        endforeach;?>
                    </select>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                        <select class="selectFilter prod_preco">
                            <option selected disabled value="*000*"> Filtrar </option>
                            <option value="DESC">Maior Preço</option>
                            <option value="ASC">Menor Preço</option>
                        </select>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterFav" for="fav_radio"><i class="fas fa-heart"></i> FAVORITOS</label>
                        <input type="radio" name="fav_radio" class="fav_radio prod_fav" id="fav_radio"/>
                    </div>
                </div>

                <!-- FILTRO PARA TELAS GRANDES -->
                <div class="filtro_pesquisa">
                    <div class="divTitleFilter">
                        <h5 class="titleFilter">FILTROS DE PESQUISA</h5>
                    </div>
                    <?php
                    $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto AS p JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                    $sel2->execute();
                    $result2 = $sel2->fetchAll();?>
                    <div class="divFilter">
                        <label for="href" class="titleConfigFilter FilterVol"><i class="fas fa-weight-hanging"></i> VOLUME</label>
                        <ul class="listFilterOptions">
                        <?php
                        foreach($result2 as $k => $v):?>
                            <li class="celulaListFilterOpt"><input type="radio" name="prod_tam" id="<?= $k; ?>" class="produto_tamanho" value="<?= $v['tam']; ?>"/> <label for="<?= $k; ?>"><?= $v['tam']; ?></label></li>
                            <?php
                        endforeach;?>
                        </ul>
                    </div>
                    <?php
                    $sel2 = $conn->prepare("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto AS p JOIN marca_prod AS m ON p.produto_marca=m.marca_id JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']}");
                    $sel2->execute();
                    $result2 = $sel2->fetchAll();?>
                    <div class="divFilter">
                        <label class="titleConfigFilter FilterMarca"><i class="fas fa-copyright"></i> MARCA</label>
                        <ul class="listFilterOptions">
                        <?php
                        foreach($result2 as $v):?>
                            <li class="celulaListFilterOpt"><input type="radio" name="produto_marca" id="<?= $k . $v['marca_nome']; ?>" class="prod_marca" value="<?= $v['marca_nome']; ?>"/> <label for="<?= $k . $v['marca_nome']; ?>"><?= $v['marca_nome']; ?></label></li>
                            <?php
                        endforeach;?>
                        </ul>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterPreco">&nbsp<i class="fas fa-dollar-sign"></i> &nbspPREÇO</label>
                        <ul class="listFilterOptions" id="preco_filtro">
                            <li class="celulaListFilterOpt">
                                <input type="radio" name="produto_preco" class="prod_preco" id="me_p" value="ASC"> <label for="me_p">Menor preço</label>
                            </li>
                            <li class="celulaListFilterOpt">
                                <input type="radio" name="produto_preco" class="prod_preco" id="ma_p" value="DESC"> <label for="ma_p">Maior preço</label>
                            </li>
                        </ul>
                    </div>
                    <div class="divFilter">
                        <label class="titleConfigFilter filterFav"><i class="fas fa-heart"></i> FAVORITOS</label>
                        <ul class="listFilterOptions" id="preco_filtro">
                            <li class="celulaListFilterOpt">
                                <input type="radio" name="produto_fav" class="prod_fav" id="fav_rad"> <label for="fav_rad">Favoritos</label>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="divShowProdFilter">
                    <?php
                    $sel = $conn->prepare("SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto AS p JOIN dados_armazem AS d ON p.produto_id=d.produto_id LEFT JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id LEFT JOIN promocao_temp AS pr ON dp.promo_id=pr.promo_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ");
                    $_SESSION[Department::SESSION]['rawQuery'] = "SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto AS p JOIN marca_prod AS m ON m.marca_id=p.produto_marca JOIN dados_armazem AS d ON p.produto_id=d.produto_id LEFT JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id LEFT JOIN promocao_temp AS pr ON dp.promo_id=pr.promo_id WHERE p.produto_categ={$_SESSION[Department::SESSION]['categ_id']} AND d.armazem_id={$_SESSION[Storage::SESSION]['arm_id']} ";
                    $sel->execute();
                    if($sel->rowCount() > 0):
                        while($v = $sel->fetch( PDO::FETCH_ASSOC )):?>
                            <div class="prodFilter">
                                <div class="btnFavoriteFilter btnFavorito<?= $v['produto_id']; ?>">
                                    
                                </div>
                                <a class="linksProdCarousel" id-produto="<?= $v['produto_id']; ?>">
                                    <img src="<?= base_url(); ?>admin-area/img-produtos/<?= $v["produto_img"]; ?>"/>
                                    <?php 
                                        if($v['produto_desconto_porcent'] <> "") {
                                            $v["produto_desconto"] = $v["produto_preco"]*($v["produto_desconto_porcent"]/100);
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, '.', '');
                                            $v["produto_desconto"] = $v["produto_preco"]-$v["produto_desconto"];
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, ',', '.');
                                        } elseif($v['promo_desconto']) {
                                            $v["produto_desconto"] = $v["produto_preco"]*($v["promo_desconto"]/100);
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, '.', '');
                                            $v["produto_desconto"] = $v["produto_preco"]-$v["produto_desconto"];
                                            $v["produto_desconto"] = number_format($v["produto_desconto"], 2, ',', '.');
                                        }
                                        $v['produto_preco'] = number_format($v["produto_preco"], 2, ',', '.');
                                    ?>
                                    <?= isset($v["produto_desconto"]) ? '<p class="divProdPromo">-' . $v['produto_desconto_porcent'] . $v["promo_desconto"] . '%</p>' : '' ; ?>
                                    <div class='divisorFilter'></div>
                                    <h5 class='titleProdFilter'><?= $v["produto_nome"]; ?> - <?= $v["produto_tamanho"]; ?></h5>
                                    <p class='priceProdFilter'>
                                        <?= isset($v["produto_desconto"]) ? '<span class="divProdPrice1">R$' . $v['produto_preco'] . '</span> R$' . $v['produto_desconto'] : 'R$ ' . $v["produto_preco"]; ?>
                                    </p>
                                </a>
                                <div>
                                    <?php 
                                        if($v["produto_qtd"] > 0):?>
                                            <form class="formBuy">
                                                <input type="hidden" value="<?= $v["produto_id"]; ?>" name="id_prod"/>
                                                <input type="number" min="0" max="20" value="<?= isset($_SESSION[Cart::SESSION][$v['produto_id']]) ? $_SESSION[Cart::SESSION][$v['produto_id']] : 0 ; ?>" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                                <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                            </form>
                                            <?php
                                        else:?>
                                            <span class="esgotQtdFilter">ESGOTADO</span>
                                            <form class="formBuy">
                                                <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                            </form>
                                            <?php
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <?php
                        endwhile;
                    endif;?>
                </div><?php
            endif;
        endif;
    endif;
?>