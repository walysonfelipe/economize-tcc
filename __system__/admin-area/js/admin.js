function carregar(pagina) {
    $("#conteudo").load(pagina);
}

function modalView() {
    var modal = document.getElementById('myModalView');

    var btn = [];
    for(var i = 0; i < $('.myBtnView').length; i++) {
        btn[i] = $('.myBtnView')[i];
    }

    for(var c = 0; c < btn.length; c++) {
        btn[c].onclick = function() {
            modal.style.display = "block";
        }
    }

    var span = document.getElementsByClassName("closeModalView")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function modalUpd() {
    var modalUpd = document.getElementById('myModalUpd');

    var btnUpd = [];
    for(var i = 0; i < $('.myBtnUpd').length; i++) {
        btnUpd[i] = $('.myBtnUpd')[i];
    }

    for(var c = 0; c < btnUpd.length; c++) {
        btnUpd[c].onclick = function() {
            modalUpd.style.display = "block";
        }
    }

    var span = document.getElementsByClassName("closeModalUpd")[0];

    span.onclick = function() {
        modalUpd.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modalUpd) {
            modalUpd.style.display = "none";
        }
    }
}


var modalAdd = document.getElementById('myModalAdd');

function mostraModalAdd() {
    $('.formInserir').each(function() {
        this.reset();
    });
    $('.help-block').html("");
    $('.newAdd').remove();
    $('.imgUpload').attr("src", "");
}

$('.linkAlterAdm').click(function(e) {
    e.preventDefault();
    mostraModalAdd();
    modalAdd.style.display = "block";
});

$('.closeModalAdd').click(function(e) {
    e.preventDefault();
    $('.inpProd').html(``);
    modalAdd.style.display = "none";
});

var modalNot = document.getElementById('myModalNot');
$('.notification').mouseover(function(e) {
    e.preventDefault();
    modalNot.style.display = "block";

    $(document).on("click", function (e) {
        var obj = "myModalNot", id = $(e.target).attr('id');
        if (id==obj) return;
        if ($("#"+obj+" #"+id).length > 0) return;
        $("#"+obj).hide();
    });
});

window.onclick = function(event) {
    if (event.target == modalAdd) {
        $('.inpProd').html(``);
        modalAdd.style.display = "none";
    }
}

function notification() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL4 + 'functions/notification',
        success: function(json) {
            if(json['status']) {
                if(json['noVisu']) {
                    if(json['noVisu'] <= 99) {
                        $('.numNot').html(`
                            <p class="qtdNotifi">` + json['noVisu'] + `</p>
                        `);
                    } else {
                        $('.numNot').html(`
                            <p class="qtdNotifi">99+</p>
                        `);
                        $('.qtdNotifi').css({
                            'width':'21px',
                            'height':'21px',
                            'border-radius':'21px',
                            'line-height':'19px'
                        });
                    }
                } else {
                    $('.numNot').html(``);
                }

                $('.showNotModal').html("");
                if(json['notificationNoVisu'].length > 0) {
                    $('.showNotModal').append(`
                        <div class="subtituloNot" id="subtituloNova">NÃO VISTAS</div>
                    `);
                    for(var i = 0; i < json['notificationNoVisu'].length; i++) {
                        if(json['notificationNoVisu'][i].resp_id) {
                            var resp = `<span class="spanActive">JÁ RESPONDIDA</span>`;
                        } else {
                            var resp = `<span class="spanNoActive">ESPERANDO RESPOSTA</span>`;
                        }
                        $('.showNotModal').append(`
                            <a id="aNot` + json['notificationNoVisu'][i].id_atd + `" id-data="` + json['notificationNoVisu'][i].id_atd +`" class="aNotNot btnViewNot" href="` + BASE_URL4 + `atendimento/central?id_atd=` + json['notificationNoVisu'][i].id_atd + `">
                                <div class="notNoVisu" id="msg` + i + `">
                                    <b id="btnN` + i + `">` + json['notificationNoVisu'][i].nome_usu + `</b> enviou uma mensagem para o atendimento online.<br/>
                                    <b id="btnD` + i + `" class="dataenv">` + json['notificationNoVisu'][i].dataenv_pro + resp + `
                                </div>
                            </a>
                        `);
                    }
                } else {
                    $('.showNotModal').append(``);
                }
                if(json['notificationVisu'].length > 0) {
                    $('.showNotModal').append(`
                        <div class="subtituloNot" id="subtituloAnt">JÁ VISTAS</div>
                    `);
                    for(var i = 0; i < json['notificationVisu'].length; i++) {
                        if(json['notificationVisu'][i].resp_id) {
                            var resp = `<span class="spanActive">JÁ RESPONDIDA</span>`;
                        } else {
                            var resp = `<span class="spanNoActive">ESPERANDO RESPOSTA</span>`;
                        }
                        $('.showNotModal').append(`
                            <a id="aNot` + json['notificationVisu'][i].id_atd + `" id-data="` + json['notificationVisu'][i].id_atd +`" class="aNotNot" href="` + BASE_URL4 + `atendimento/central?id_atd=` + json['notificationVisu'][i].id_atd + `">
                                <div class="notVisu" id="msgV` + i + `">
                                    <b id="btnN` + i + `">` + json['notificationVisu'][i].nome_usu + `</b> enviou uma mensagem para o atendimento online.<br/>
                                    <b id="btnD` + i + `" class="dataenv">` + json['notificationVisu'][i].dataenv_pro + resp + `
                                </div>
                            </a>
                        `);
                    }
                } else {
                    $('.showNotModal').append(``);
                }
                visuNot();

                if(json['entrega_pendente']) {
                    $('.notifEnt').css({'display':'inline-block'});
                    $('.notifEnt').html(json['entrega_pendente']);
                } else {
                    $('.notifEnt').css({'display':'none'});
                    $('.notifEnt').html(``);
                }
            }
        },
        complete: function() {
            setTimeout(notification, 10000);
            setTimeout(visuNot, 10000);
        }
    });
}

function visuNot() {
    $('.btnViewNot').click(function(e) {
        e.preventDefault();
        var dado = "id_atd=" + $(this).attr("id-data");
        var url = $(this).attr("href");

        $.ajax({
            url: BASE_URL4 + 'functions/notification',
            type: 'post',
            data: dado,
            success: function(json) {
                if(json['status'] == 0) {
                    Toast.fire({
                        type: "error",
                        title: "Um erro ocorreu"
                    });
                }
                window.location.href = url;
            }
        });
    });
}

function allNot() {
    $('.aNotMarca').click(function(e) {
        e.preventDefault();
        var dado = "id_func=" + $(this).attr("func-id");

        $.ajax({
            url: BASE_URL4 + 'functions/notification',
            type: 'post',
            data: dado,
            success: function(json) {
                notification();
                if(json['status'] == 0) {
                    Toast.fire({
                        type: "error",
                        title: "Um erro ocorreu"
                    });
                }
            }
        });
    });
}

notification();
allNot();