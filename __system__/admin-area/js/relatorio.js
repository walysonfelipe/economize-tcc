$('.selTypeDate').change(function(e) {
    e.preventDefault();
    var type = $(this).val();

    if(type == "day") {
        $('.divTypeDate').html(`<input type="text" class="selectConfigArm date" placeholder="dd/mm/aaaa" name="dayRelat" id="dayRelat" size="60"/>`);
    } else if(type == "month") {
        $('.divTypeDate').html(`<input type="text" class="selectConfigArm month-year" placeholder="mm/aaaa" name="dayRelat" id="monthRelat" size="60"/>`);
    } else if(type == "year") {
        $('.divTypeDate').html(`<input type="text" class="selectConfigArm year" placeholder="aaaa" name="dayRelat" id="yearRelat" size="60"/>`);
    }

    mask();
});

$('#formGeraRelatorio').submit(function() {
    $('.help-block').html(loadingRes(`Verificando...`));
    var type = $('.selTypeDate').val();

    if(type == "day") {
        var length_input = $('#dayRelat').val().length;
        if(length_input < 10) {
            $('.help-block').html(`<p style="text-align:center;">Insira uma data válida, por favor</p>`);
            var retorno = true;
        }
    } else if(type == "month") {
        var length_input = $('#monthRelat').val().length;
        if(length_input < 7) {
            $('.help-block').html(`<p style="text-align:center;">Insira um mês válido, por favor</p>`);
            var retorno = true;
        }
    } else if(type == "year") {
        var length_input = $('#yearRelat').val().length;
        if(length_input < 7) {
            $('.help-block').html(`<p style="text-align:center;">Insira um ano válido, por favor</p>`);
            var retorno = true;
        }
    } else {
        $('.help-block').html(`<p style="text-align:center;">Escolha um tipo de data, por favor</p>`);
        var retorno = true;
    }

    if(retorno)
        return false;
});