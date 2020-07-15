$(document).ready(function() {
    $('#btn-solicita').click(function() {
        var dado = "solicita=" + $(this).attr("data-code");

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: BASE_URL + 'functions/confirmaEmail',
            data: dado,
            beforeSend: function () {
                $(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if (json["status"] === 1) {
                    $(".help-block").html(loadingRes("Enviando email..."));
                    window.location.href = BASE_URL + "usuario/confirmar-email";
                } else {
                    $(".help-block").html("<p>Um erro ocorreu ao solicitar o código. Tente novamente!</p>");
                }
            }
        });
    });

    $("#form-confemail").submit(function() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: BASE_URL + 'functions/confirmaEmail',
            data: $(this).serialize(),
            beforeSend: function () {
                $(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if (json["status"] === 1) {
                    $(".help-block").html(loadingRes("Confirmando email..."));

                    Swal.fire({
                        title: "Confirmado com sucesso!",
                        text: "Bem vindo(a), " + json["nome_usuario"] + "!!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#9C45EB",
                        confirmButtonText: "Ok"
                    }).then(() => {
                        window.location.href = BASE_URL;
                    });
                } else {
                    $(".help-block").html(json['error']);
                }
            }
        })

        return false
    })
});;

  