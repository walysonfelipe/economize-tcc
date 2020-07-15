$(document).ready(function() {
    // O 'one click' faz com que a função seja chamada apenas uma vez
    $('.linkArm').one("click", function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'json',
            url: BASE_URL + 'functions/listArmazem',
            success: function(response) {
                $('.Armazens').html(loadingRes("Buscando armazéns..."));
                $('.Armazens').html(`
                    <h5 class="titleModalArm">
                        <i class="fas fa-info-circle"></i> SOBRE ESTE ARMAZÉM: 
                        <a class='linkAboutArm' href="` + BASE_URL + `ajuda/horario-armazem">HORÁRIOS DE ENTREGA</a> | 
                        <a class='linkAboutArm' href="` + BASE_URL + `ajuda/subcidades">SUBCIDADES</a>
                    </h5>
                    <h5 class="titleModalArm"><i class="fas fa-warehouse"></i> ESCOLHA OUTRO ARMAZÉM:</h5>
                `);
                for (var i = 0; response.length > i; i++) {
                    if(response[i].meuArm) {
                        $('.meuArmazem').html(`
                            <h4 class="titleModalArmName">` + response[i].armazem_nome + `<br/>` + response[i].cid_nome + ` - ` + response[i].est_uf + `</h4>
                        `);
                    } else {
                        $('.Armazens').append(`
                            <button class="btn-arm btnModalArm" id-armazem="` + response[i].armazem_id + `">` + response[i].armazem_nome + `<br/>` + response[i].cid_nome + ` - ` + response[i].est_uf + `</button>
                        `);
                    }
                }
                $('.Armazens').append(`
                    <h5 class="linkModalArm">* O preço dos produtos podem mudar de acordo com o armazém</h5>
                `);
                $('body').append('<script src="' + BASE_URL2 + 'js/escolherArmazem.js"></script>');
            }
        });
    });
});