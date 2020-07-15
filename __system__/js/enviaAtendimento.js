$("#form-atd").submit(function() {
    $.ajax({
        dataType: 'json',
        type: 'post',
        data: $(this).serialize(),
        url: BASE_URL + 'functions/enviaAtendimento',
        beforeSend: function() {
            $("#btnAtend").siblings(".help-block").html(loadingRes("Verificando..."));
        },
        success: function(json) {
            clearErrors();
           if(json['status']) {
                $("#btnAtend").siblings(".help-block").html(loadingRes("Enviando mensagem..."));
                $('#form-atd').each(function() {
                    this.reset();
                });
                Swal.fire({
                    title: "Mensagem enviada com sucesso!",
                    text: "O e.conomize agradece sua disponibilidade! Você receberá sua resposta em breve no email inserido no formulário.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9C45EB",
                    confirmButtonText: "Ok",
                    footer: '<a href="' + BASE_URL + 'usuario/notificacoes">Ver minhas notificações</a>'
                });
                clearErrors();
           } else {
                showErrorsAdmin(json["error_list"]);
           }
        }
    });

    return false;
});