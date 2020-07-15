function attCampos(prod_id) {
    var dado = "attCampo_id=" + prod_id;

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dado,
        url: BASE_URL + 'functions/attCarrinho',
        success: function(json) {
            $(".inputBuy" + prod_id).val(json["carrinho_qtd"]);
            // $(`input[name='id_prod'][value='${prod_id}']`).siblings(".inputBuy").val(json["carrinho_qtd"]);
        }
    });
    return false;
}

function attCarrinho() {
    $('.formBuy').submit(function(e) {
        e.preventDefault();
        var dado = $(this).serialize();
        var elementoPai = $(this).parent();
        var elemento = elementoPai.find('[name = "id_prod"]').val();

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/attCarrinho',
            success: function(json) {
                Toast.fire({
                    type: json['type'],
                    title: json['answer']
                });
                attCampos(elemento);
            }
        });
        return false;
    });

    $('.tirarProd').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Deseja mesmo excuir o produto do carrinho?",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#9C45EB",
            confirmButtonText: "Sim, excluir"
        }).then((result) => {
            if(result.value) {
                var dado = "produto_id=" + $(this).attr("id-prod");
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: dado,
                    url: BASE_URL + 'functions/attCarrinho',
                    success: function(json) {
                        Toast.fire({
                            type: json['type'],
                            title: json['answer']
                        });
                        listCarrinho();
                    }
                });
            }
        });
    });

    $('.limparCart').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Deseja mesmo limpar o carrinho?",
            text: "Uma vez limpando o carrinho, será perdido permanentemente!",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#9C45EB",
            confirmButtonText: "Sim, limpe"
        }).then((result) => {
            if(result.value) {
                var dado = "limpaCart=1";
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: dado,
                    url: BASE_URL + 'functions/attCarrinho',
                    success: function(json) {
                        Toast.fire({
                            type: json['type'],
                            title: json['answer']
                        });
                        listCarrinho();
                    }
                });
            }
        });
    });

    $('.qtdProdCart').change(function(e) {
        e.preventDefault();
        if($(this).val() == 0) {
            Swal.fire({
                title: "Deseja mesmo excuir o produto do carrinho?",
                type: "warning",
                showCancelButton: true,
                cancelButtonColor: "#494949",
                cancelButtonText: "Cancelar",
                confirmButtonColor: "#9C45EB",
                confirmButtonText: "Sim, excluir"
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        dataType: 'json',
                        type: 'post',
                        data: {
                            "prod_id": $(this).attr("id-prod"),
                            "qtd_prod": $(this).val()
                        },
                        url: BASE_URL + 'functions/attCarrinho',
                        success: function(json) {
                            Toast.fire({
                                type: json['type'],
                                title: json['answer']
                            });
                            listCarrinho();
                        }
                    });
                } else {
                    $(this).val("1");
                    $.ajax({
                        dataType: 'json',
                        type: 'post',
                        data: {
                            "prod_id": $(this).attr("id-prod"),
                            "qtd_prod": $(this).val()
                        },
                        url: BASE_URL + 'functions/attCarrinho',
                        success: function(json) {
                            Toast.fire({
                                type: json['type'],
                                title: json['answer']
                            });
                            listParcialCarrinho();
                        }
                    });
                }
            });
        } else {
            $.ajax({
                dataType: 'json',
                type: 'post',
                data: {
                    "prod_id": $(this).attr("id-prod"),
                    "qtd_prod": $(this).val()
                },
                url: BASE_URL + 'functions/attCarrinho',
                success: function(json) {
                    Toast.fire({
                        type: json['type'],
                        title: json['answer']
                    });
                    listParcialCarrinho();
                }
            });
        }
    })
}

attCarrinho();