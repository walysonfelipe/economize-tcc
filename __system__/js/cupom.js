function inputCupom() {
    $('.inputAddCupom').keyup(function() {
        var dado = "addCupom=" + $(this).val();
        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/cupom',
            beforeSend: function() {
                $('.divAnswer').html(loadingRes("Buscando cupom..."));
            },
            success: function(json) {
                if(json['status']) {
                    Toast.fire({
                        type: 'success',
                        title: 'Cupom foi adicionado'
                    });
                    $('.divAddCupom').html(`
                        <span class="codeCupom">
                            <b>Código cupom:</b> ` + json['cupom']['cupom_codigo'] + `
                        </span>
                        <button class="remCupom" title="Remova o cupom"><i class="far fa-times-circle"></i></button>
                    `);
                    $(".divAnswer").html(`<p>Cupom de ` + json['cupom']['cupom_desconto_porcent'] + `% de desconto adicionado</p>`);
                    $('.valueBuy').html(`R$` + json['new_total_price']);
                    $('.remCupom').click(function(e) {
                        e.preventDefault();
                        remCupom();
                    });
                } else {
                    $(".divAnswer").html(`<p>` + json['answer'] + `</p>`);
                }
            }
        });
    });
}

function remCupom() {
    $.ajax({
        dataType: 'json',
        type: 'post',
        data: "remCupom=1",
        url: BASE_URL + 'functions/cupom',
        success: function(json) {
            Toast.fire({
                type: 'success',
                title: 'Cupom foi removido'
            });
            $('.divButtonCupom').html(`<button class="addCupom">ADICIONAR CUPOM <i class="fas fa-tag"></i></button>`);
            $('.divAddCupom').html("");
            $('.divAnswer').html("");
            $('.valueBuy').html(`R$` + json['new_total_price']);
            $('.addCupom').click(function(e) {
                e.preventDefault();
                addCupom();
            });
        }
    });
}

function verificaCupom() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/cupom',
        beforeSend: function() {
            $('.divAnswer').html(loadingRes());
        },
        success: function(json) {
            if(!json['empty']) {
                $('.divButtonCupom').html("");
                $('.divAddCupom').html(`
                    <span class="codeCupom">
                        <b>Código cupom:</b> ` + json['cupom']['cupom_codigo'] + `
                    </span>
                    <button class="remCupom" title="Remova o cupom"><i class="far fa-times-circle"></i></button>
                `);
                $(".divAnswer").html(`<p>Cupom de ` + json['cupom']['cupom_desconto_porcent'] + `% de desconto adicionado</p>`)
                $('.valueBuy').html(`R$` + json['new_total_price']);
                Toast.fire({
                    type: 'success',
                    title: 'Cupom foi adicionado'
                });
                inputCupom();
                $('.remCupom').click(function(e) {
                    e.preventDefault();
                    remCupom();
                });
            } else {
                $('.divAnswer').html("");
            }
        }
    });
}

function addCupom() {
    $('.divButtonCupom').html("");
    $('.divAddCupom').html(`
        <input type="text" class="inputAddCupom" name="cupom_cod" title="Digite o código do cupom" placeholder=" "/>
        <button class="remCupom" title="Remova o cupom"><i class="far fa-times-circle"></i></button>
    `);
    inputCupom();
    $('.remCupom').click(function(e) {
        e.preventDefault();
        remCupom();
    });
}

function botaoAddCupom() {
    $('.addCupom').click(function(e) {
        e.preventDefault();
        addCupom();
    });
}