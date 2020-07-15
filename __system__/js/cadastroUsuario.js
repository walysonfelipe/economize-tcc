$(document).ready(function() {
  $("#usu_cep").keyup(function(){
      if($(this).val().length == 9) {
          $.ajax({
              url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
              dataType: 'json',
              beforeSend: function() {
                  $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;` + loadingResSmall(`Buscando...`));
                  $("#usu_end").val(``);
                  $("#usu_complemento").val(``);
                  $("#usu_bairro").val(``);
                  $("#usu_uf").val(``);
                  $("#usu_cidade").val(``);
              },
              success: function(resposta) {
                  if(resposta.erro) {
                      $(".answer-cep").html(` &nbsp;&nbsp;&nbsp;<small style="color:#A94442;" class="smallAnswer">Endereço inexistente</small>`);
                  } else {
                      $(".answer-cep").html(``);
                      $("#usu_end").val(resposta.logradouro);
                      $("#usu_complemento").val(resposta.complemento);
                      $("#usu_bairro").val(resposta.bairro);
                      $("#usu_uf").val(resposta.uf);
                      $("#usu_cidade").val(resposta.localidade);
                      $("#usu_num").focus();
                  }
              }
          });
      } else {
          $(".answer-cep").html(``);
          $("#usu_end").val(``);
          $("#usu_complemento").val(``);
          $("#usu_bairro").val(``);
          $("#usu_uf").val(``);
          $("#usu_cidade").val(``);
      }
  });

  $("#form-cadastro").submit(function() {
    $.ajax({
      type: 'POST',
      dataType: 'json',
      data: $(this).serialize(),
      url: BASE_URL + 'functions/cadastroUsuario',
      beforeSend: function() {
        clearErrors();
        $("#btn-cad").siblings(".help-block").html(loadingRes("Verificando..."));
      },
      success: function(response) {
        if(response["status"]) {
            $("#btn-cad").siblings(".help-block").html(loadingRes("Cadastrando..."));
            window.location.href = BASE_URL + "usuario/confirmar-email";
            //   $("#btn-cad").siblings(".help-block").html(loadingRes("Cadastrando..."));
            //   clearErrors();
            //   Swal.fire({
            //     title: "Cadastrado(a) com sucesso!",
            //     text: "Bem vindo(a), " + response["nome_usuario"] + "!!",
            //     type: "success",
            //     showCancelButton: false,
            //     confirmButtonColor: "#9C45EB",
            //     confirmButtonText: "Ok"
            //   }).then(() => {
            //       window.location.href = BASE_URL + "usuario/confirmar-email";
            //   });
        } else {
            showErrors(response["error_list"]);
            $("#telefone").siblings(".help-block-tel").html(response["error_tel"]);
        }
      }
    });
    return false;
  });
});