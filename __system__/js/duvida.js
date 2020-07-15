const escreveDuvida = (json) => {
    if (json['status']) {
        if (!json['empty']) {
            $('.l-duvida').html(`
                <p class="loadDuvida">
                    <i class='fa fa-circle-notch fa-spin'></i> &nbsp;Buscando...
                </p>
            `);
            $('.l-duvida').html(``);
            for (let i = 0; i < json['duvidas'].length; i++) {
                var c = i + 1;
                $('.l-duvida').append(`
                    <div>
                        <div class="divDuvida" id-duvida="` + json['duvidas'][i].duvida_id + `">
                            <h4 class="perguntaDuvida">` + c + ` - ` + json['duvidas'][i].duvida_pergunta + `</h4>
                        </div>
                        <div class="respostaDuvida"></div>
                    </div>
                `);
            }
            getResposta();
        } else {
            $('.l-duvida').html(`
                <p class="loadDuvida">
                    Não houve resposta para a pesquisa!
                </p>
            `);
        }
    } else {
        $('.l-duvida').html(`
            <p class="loadDuvida">
                Um erro inesperado ocorreu. Estamos trabalhando para consertá-lo!
            </p>
        `);
    }
}

const cleanSearch = (action = true) => {
    if (action) {
        $('.cleanSearch').click(function(e) {
            e.preventDefault();
            $('#search_duvida').val(``)
            cleanSearch(false)
        })
    } else {
        $('.cleanSearch').html(``);
        showPerguntas();
    }
}

function showPerguntas() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/duvida',
        beforeSend: function() {
            $('.l-duvida').html(`
                <p class="loadDuvida">
                    <i class='fa fa-circle-notch fa-spin'></i> &nbsp;Processando...
                </p>
            `);
        },
        success: function(json) {
            escreveDuvida(json)
        }
    });
}

function searchDuvida() {
    $(".inputSearchDuvida").keyup(function(e) {
        e.preventDefault();
        if ($(this).val().length > 0) {
            $('.cleanSearch').html(`
                <i class="far fa-times-circle"></i>
            `)
            var dado = "searchDuvida=" + $(this).val();
            cleanSearch();

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL + 'functions/duvida',
                beforeSend: function() {
                    $('.l-duvida').html(`
                        <p class="loadDuvida">
                            <i class='fa fa-circle-notch fa-spin'></i> &nbsp;Processando...
                        </p>
                    `);
                },
                success: function(json) {
                    escreveDuvida(json)
                }
            });
        } else {
            cleanSearch(false);
        }
    });
}

function getResposta() {
    $('.divDuvida').click(function(e) {
        e.preventDefault();
        var dado = "duvida_id=" + $(this).attr("id-duvida");
        var divResp = $(this).siblings(".respostaDuvida");

        if (divResp.html().length > 0) {
            divResp.html(``);
            divResp.css({'display':'none'});
        } else {
            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL + 'functions/duvida',
                beforeSend: function() {
                    $('.respostaDuvida').html(``);
                    $('.respostaDuvida').css({'display':'none'});
                },
                success: function(json) {
                    divResp.css({'display':'block'});

                    if (json['status']) {
                        divResp.html(`<p>` + json['duvida_resposta'] + `</p>`);
                    } else {
                        divResp.html(`
                            <p class="loadDuvida">
                                Um erro ocorreu ao buscarmos a resposta. Tente novamente, por favor!
                            </p>
                        `);
                    }
                }
            });
        }
    });
}

searchDuvida();
getResposta();