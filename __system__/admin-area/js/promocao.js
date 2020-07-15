var c = 0;
function changeArm() {
    $('.armazemPromo').change(function(e) {
        e.preventDefault();
        var selProd = $(this).parent().parent().siblings(".prodTr");
        selProd = selProd.find(".produtoPromo");
        if($(this).val() != "*000*") {
            var dado = "arm_id=" + $(this).val();

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/promocao',
                success: function(json) {
                    if(json['status']) {
                        selProd.html(`
                            <option value="*000*"> -- Selecione o produto: --</option>
                            <option value="*111*">Adicionar todos deste armazém</option>
                        `);
                        for(var i = 0; i < json['produtos'].length; i++) {
                            selProd.append(`
                                <option value="` + json['produtos'][i].produto_id + `">
                                    ` + json['produtos'][i].produto_nome + 
                                    ` - ` 
                                    + json['produtos'][i].produto_tamanho + `
                                </option>
                            `);
                        }
                        triggerCheckbox();
                    } else {
                        selProd.html(`
                            <option value="*000*"> -- Selecione o produto: --</option>
                        `);
                        Toast.fire({
                            type: "error",
                            title: "Armazém está vazio!"
                        });
                    }
                }
            });
        } else {
            selProd.html(`
                <option value="*000*"> -- Selecione o produto: --</option>
            `);
        }
    });
}

function insertPromocao() {
    $('.formInserirPromocao').submit(function(e) {
        e.preventDefault();

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/promocao',
            type: 'POST',
            data: $(this).serialize(),
            beforeSend() {
                clearErrors();
                $("#btnInsertPromocao").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status'] == 2) {
                    Swal.fire({
                        title: "Você está tentando promocionar produto(s) já promocionado(s)?",
                        text: "Caso confirmar, qualquer promoção relacionada à estes produtos serão perdidas!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Confirmar",
                        cancelButtonColor: "#999",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if(result.value) {
                            $.ajax({
                                dataType: 'json',
                                url: BASE_URL4 + 'functions/promocao',
                                type: 'POST',
                                data: "confirmaPromo=1",
                                beforeSend: function() {
                                    Swal.fire({
                                        title: "<h1><i class='fa fa-circle-notch fa-spin'></i></h1>",
                                        showCancelButton: false,
                                        showConfirmButton: false
                                    });
                                },
                                success: function(json) {
                                    if(json['status']) {
                                        Swal.fire({
                                            title: "Promoção cadastrada com sucesso!",
                                            text: "Deseja continuar cadastrando promoções?",
                                            type: "success",
                                            showCancelButton: true,
                                            confirmButtonColor: "#333",
                                            confirmButtonText: "Continuar",
                                            cancelButtonColor: "#999",
                                            cancelButtonText: "Sair"
                                        }).then((result) => {
                                            if(result.value) {
                                                $('.inpProd').html(``);
                                                mostraModalAdd();
                                            } else {
                                                $('.inpProd').html(``);
                                                modalAdd.style.display = "none";
                                            }
                                        });
                                    } else {
                                        $("#btnInsertPromocao").siblings(".help-block").html(json['error']);
                                    }
                                }
                            });
                        }
                    }); // Economize seu tempo e dinheiro! Só aqui produtos 30% OFF para o seu paizão. Aproveite!
                } else if(json['status']) {
                    Swal.fire({
                        title: "Promoção cadastrada com sucesso!",
                        text: "Deseja continuar cadastrando promoções?",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Continuar",
                        cancelButtonColor: "#999",
                        cancelButtonText: "Sair"
                    }).then((result) => {
                        if(result.value) {
                            mostraModalAdd();
                        } else {
                            modalAdd.style.display = "none";
                        }
                    });
                } else {
                    $("#btnInsertPromocao").siblings(".help-block").html(json['error']);
                }
            }
        });
        return false;
    });
}

changeArm();