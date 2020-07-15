function showPost() {
    $('.viewPost').click(function(e) {
        e.preventDefault();
        var dado = "showPost=" + $(this).attr("data-post");

        $.ajax({
            dataType: 'json',
            data: dado,
            type: 'post',
            url: BASE_URL +  'functions/notifica',
            beforeSend: function() {
                $('.answer-purch').html(loadingRes("Processando..."));
            },
            success: function(json) {
                $('.answer-purch').html(``);
                if(json['status']) {
                    $('.divCompraRight').html(`
                        <span class="btnClosePurch"><i class="far fa-times-circle"></i></span>

                        <img style="width: 50%;margin:0 25%;" src="` + BASE_URL2 + `style/img/postagem/` + json['postagem']['post_img'] + `"/>

                        <p style="width:85%;margin:1rem 15%;">Postado em ` + json['postagem']['post_registro'] + `</p>

                        <h2 style="width:80%;margin:0 auto;text-align:center;">` + json['postagem']['post_title'] + `</h2>

                        <p style="width:90%;margin:2.5rem auto;text-align:justify;text-indent:30px;">` + json['postagem']['post_text'] + `</p>
                    `);
                    closePost();
                } else {
                    $('.answer-purch').html(`
                        <p class="msgErrorPurch">` + json['error'] + `</p>
                    `);
                }
            }
        });
    });
}

function closePost() {
    $('.btnClosePurch').click(function(e) {
        e.preventDefault();
        $('.divCompraRight').html(`
            <div class="answer-purch">
                <h1>Clique em uma notificação e ela aparecerá aqui!</h1>
            </div>
        `);
    });
}

function writePost() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL +  'functions/notifica',
        beforeSend: function() {
            $('.help-block-post').html(loadingResSmall());
        },
        success: function(json) {
            $('.help-block-post').html(``);
            $('.showCompras').html(``);
            if(json['status']) {
                for(var i = 0; i < json['postagens'].length; i++) {
                    $('.showCompras').append(`
                        <a href="#" class="viewPurchase" data-post="` + json['postagens'][i].post_id + `">
                            <p class="p_showPurch">
                                <b>` + json['postagens'][i].post_title + `</b><br/>
                                <small>` + json['postagens'][i].post_registro + `</small>
                            </p>
                        </a>
                    `);
                }
                showPost();
            } else {
                $('.showCompras').html(`
                    <p class="msgErrorPurch">` + json['error'] + `</p>
                `);
            }
        }
    });
}

$('#inputSearch').keyup(function(e) {
    e.preventDefault();

    if($(this).val().length > 0) {
        var dado = "searchPost=" + $(this).val();

        $.ajax({
            dataType: 'json',
            data: dado,
            type: 'post',
            url: BASE_URL +  'functions/notifica',
            beforeSend: function() {
                $('.help-block-post').html(loadingResSmall());
            },
            success: function(json) {
                $('.help-block-post').html(``);
                $('.showCompras').html(``);
                if(json['status']) {
                    for(var i = 0; i < json['postagens'].length; i++) {
                        $('.showCompras').append(`
                            <a href="#" class="viewPurchase" data-post="` + json['postagens'][i].post_id + `">
                                <p class="p_showPurch">
                                    <b>` + json['postagens'][i].post_title + `</b><br/>
                                    <small>` + json['postagens'][i].post_registro + `</small>
                                </p>
                            </a>
                        `);
                    }
                    showPost();
                } else {
                    $('.showCompras').html(`
                        <p class="msgErrorPurch">` + json['error'] + `</p>
                    `);
                }
            }
        });
    } else {
        writePost();
    }
});

showPost();