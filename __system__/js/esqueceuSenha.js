$(document).ready(function() {

	$("#form-esqsenha").submit(function() {
        var campo_vazio = false;
        
		if ($('#usu_emailSenha').val() == '') {
            var answer = `Insira seu email, por favor.`;
            $('#usu_emailSenha').focus();
            
			var campo_vazio = true;
        }
        
        if (campo_vazio) {
            $(".help-block").html(`<p style='color:#A94442;'><b>` + answer + `</b></p>`);
            return false;
        } else {
            $(".help-block").html(loadingRes("Verificando..."));

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: BASE_URL + 'functions/esqueceuSenha',
                data: $(this).serialize(),
                success: function(json) {
                    clearErrors();
                    
                    if (json["status"]) {
                        $(".help-block").html(loadingRes("Enviando email..."));
                        window.location.href = BASE_URL + "usuario/reset";
                    } else {
                        $(".help-block").html(json["error"]);
                    }
                }
            });

            return false;
        }
    });
    
    $("#form-resetsenha").submit(function() {
        var campo_vazio = false;
        
		if ($('#usu_senha_new').val() === '') {
            var answer = `Insira sua nova senha, por favor.`;
            $('#usu_senha_new').focus();
            
			var campo_vazio = true;
        } else if ($('#usu_senha_confirm').val() === '') {
            var answer = `Confirme sua nova senha, por favor.`;
            $('#usu_senha_confirm').focus();
            
			var campo_vazio = true;
        } else if ($('#usu_senha_new').val() !== $('#usu_senha_confirm').val()) {
            var answer = `As senhas não coincidem.`;
            $('#usu_senha_confirm').focus();
            
			var campo_vazio = true;
        }
        
        if (campo_vazio) {
            $(".help-block").html(`<p style='color:#A94442;'><b>` + answer + `</b></p>`);
            return false;
        } else {
            $(".help-block").html(loadingRes("Verificando..."));

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: BASE_URL + 'functions/esqueceuSenha',
                data: $(this).serialize(),
                success: function(json) {
                    clearErrors();
                    
                    if (json["status"]) {
                        $(".help-block").html(loadingRes("Alterando sua senha..."));
                        location.reload();
                    } else {
                        $(".help-block").html(`<p style='color:#A94442;'><b>${json['error']}</b></p>`);
                    }
                }
            });

            return false;
        }
	});
});