$(document).ready(function() {

	$('#btn-login').click(function() {
		var campo_vazio = false;
		if(($('#usu_email_login').val() == '') && ($('#usu_senha_login').val() == '')) {
			$('#usu_email_login').css({'border-color':'#A94442'});
			$('#usu_senha_login').css({'border-color':'#A94442'});
			$('#usu_email_login').focus();
			var campo_vazio = true;
		} else {
			if($('#usu_email_login').val() == '') {
			    $('#usu_email_login').css({'border-color':'#A94442'});
			    $('#usu_senha_login').css({'border-color':'#ccc'});
			    $('#usu_email_login').focus();
			    var campo_vazio = true;
			    // alert('Campo de usuário está vazio!');
			}
			if($('#usu_senha_login').val() == '') {
				$('#usu_senha_login').css({'border-color':'#A94442'});
				$('#usu_email_login').css({'border-color':'#ccc'});
				$('#usu_senha_login').focus();
				var campo_vazio = true;
				// alert('Campo de senha está vazio!');
			}
			else {
				$('#usu_senha_login').css({'border-color':'#ccc'});
				$('#usu_email_login').css({'border-color':'#ccc'});
			}
		}
		if(campo_vazio) return false;
	});


	$("#form-login").submit(function() {
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: BASE_URL + 'functions/login',
			data: $(this).serialize(),
			beforeSend: function() {
				$(".help-block-login").html(loadingRes("Verificando..."));
			},
			success: function(response) {
				if(response["status"]) {
					clearErrors();
					modal.style.display = "none";
					$(".help-block-login").html(loadingRes("Logando..."));
					Swal.fire({
			            title: "Bem vindo(a)!",
			            text: "Olá novamente, " + response["nome_usuario"] + "!",
			            type: "success",
			            showCancelButton: false,
			            confirmButtonColor: "#9C45EB",
			            confirmButtonText: "Ok"
					});
					verificaLogin();

					var url = location.href;
					if(url.indexOf("procedimento") != -1) {
						listCarrinho();
					} else {
						btnFavorito();
					}
				} else {
					$(".help-block-login").html(response["error"]);
				}
			}
		});
		return false;
	});
});