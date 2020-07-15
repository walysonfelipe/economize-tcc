function mudarSenha() {
    $('#formMudarSenha').submit(function() {
        var dado = $(this).serialize();
        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL4 + 'functions/configUser',
            beforeSend: function() {
                clearErrors();
                $("#btnSaveMudarSenha").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                if(json["status"]) {
                    $("#btnSaveMudarSenha").siblings(".help-block").html(loadingRes("Mudando senha..."));
                    clearErrors();
                    Toast.fire({
                        type: 'success',
                        title: 'Senha foi mudada'
                    });
                    $('#formMudarSenha').each(function() {
                        this.reset();
                    });
                } else {
                    showErrorsAdmin(json["error_list"]);
                }
            }
        });

        return false;
    });
}

mudarSenha();