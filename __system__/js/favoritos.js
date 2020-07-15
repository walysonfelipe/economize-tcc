function favoritar() {
    $('.addFavorito').click(function(e) {
        e.preventDefault();
        var dado = 'add_prod_id=' + $(this).attr('id');

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/favoritos',
            success: function(json) {
                if (json['error']) {
                    Toast.fire({
                        type: 'error',
                        title: json["error"]
                    });
                    if (!json['logado']) {
                        $("#usu_email_login").val("");
                        $("#usu_senha_login").val("");
                        $(".help-block-login").html("");
                        modal.style.display = "block";
                    }
                } else {
                    Toast.fire({
                        type: 'success',
                        title: 'Produto adicionado aos favoritos'
                    });

                    btnFavorito();
                }
            }
        });
    });

    $('.remFavorito').click(function(e) {
        e.preventDefault();
        var dado = 'rem_prod_id=' + $(this).attr('id');

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/favoritos',
            success: function(json) {
                if (json['error']) {
                    Toast.fire({
                        type: 'error',
                        title: json["error"]
                    });
                } else {
                    Toast.fire({
                        type: 'success',
                        title: 'Produto removido dos favoritos'
                    });

                    btnFavorito();
                }
            }
        });

        var url = location.href;
        if (url.indexOf("favoritos") != -1) {
            meusFavoritos();
        }
    });
}

function btnFavorito() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/favoritos',
        success: function(json) {
            if (json['status']) {
                if (json['favorites'] !== false) {
                    for (let i = 0; json['products'].length > i; i++) {
                        if (jQuery.inArray(json['products'][i].produto_id, json['favorites']) >= 0) {
                            $(`.btnFavorito${json['products'][i].produto_id}`).html(`<i class="fas fa-heart remFavorito" id="${json['products'][i].produto_id}"></i>`);
                        } else {
                            $(`.btnFavorito${json['products'][i].produto_id}`).html(`<i class="far fa-heart addFavorito" id="${json['products'][i].produto_id}"></i>`);
                        }
                    }
                } else {
                    for (let i = 0; json['products'].length > i; i++) {
                        $(`.btnFavorito${json['products'][i].produto_id}`).html(`<i class="far fa-heart addFavorito" id="${json['products'][i].produto_id}"></i>`);
                    }
                }
            }

            favoritar();
        }
    });
}

function favoritou(id) {
    $(`.btnFavorito${id}`).html(`<i class="fas fa-heart remFavorito" id="${id}"></i>`);
    favoritar();
}

function desfavoritou(id) {
    $(`.btnFavorito${id}`).html(`<i class="far fa-heart addFavorito" id="${id}"></i>`);
    favoritar();
}

btnFavorito();