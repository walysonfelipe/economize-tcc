function verificaLogin() {
    $.ajax({
        dataType: 'json',
        url: BASE_URL + 'functions/verificaLogin',
        beforeSend: function() {
            $('.s_login').html(`<i class='fa fa-circle-notch fa-spin'></i>`);
        },
        success: function(json) {
            if(json['logado']) {
                $('.s_login').html(`PERFIL`);
                $('.modal-content').html(`
                <div class="modalProfileLeftContent">
                    <h5 class="titleModalProfile">` + json['usuario']['tpu_usu_nome'] + `</h5<br><h4 class="titleModalProfileName">` + json['usuario']['usu_first_name'] + ` ` + json['usuario']['usu_last_name'] + `</h4>
                    <div class='divisorFilterProfile'></div>
                    <div class="sectionAccountInfo">
                        <h5>DADOS PESSOAIS</h5>
                        <div class="accountInfoData">
                            <p class="linhaProfile"><b>Nome:</b> ` + json['usuario']['usu_first_name'] + ` ` + json['usuario']['usu_last_name'] + `</p>
                            <p class="linhaProfile"><b>CPF:</b>&nbsp;&nbsp;&nbsp;&nbsp; ` + json['usuario']['usu_cpf'] + `</p>
                            <p class="linhaProfile"><b>Email:</b>&nbsp; ` + json['usuario']['usu_email'] + `</p>
                        </div>
                        <h5>ENDEREÇO</h5>
                        <div class="accountInfoData">
                        <p class="linhaProfile"><b>` + json['usuario']['usu_end'] + `</b><b>, ` + json['usuario']['usu_num'] + `</b></p>
                        <p class="linhaProfile"><b>` + json['usuario']['usu_complemento'] + `</b></p>
                        <p class="linhaProfile"><b>` + json['usuario']['usu_cep'] + `</b></p>
                        <p class="linhaProfile"><b>` + json['usuario']['usu_cidade'] + ` - ` + json['usuario']['usu_uf'] + `</b></p>
                        </div>
                    </div>
                    <p class="linkConfig"><a href="` + BASE_URL + `usuario/configurar"><i class="fas fa-cog"></i> &nbsp;CONFIGURAÇÕES DO PERFIL</a></p>
                </div>
                <div class="modalProfileRightContent">
                    <span class="close">&times;</span>
                    <p class="linkRight"><a href="` + BASE_URL + `usuario/favoritos"><i class="fas fa-heart"></i> &nbsp;MEUS PRODUTOS FAVORITOS</a></p>
                    <p class="linkRight"><a href="` + BASE_URL + `usuario/estatisticas"><i class="fas fa-chart-line"></i> &nbsp;MINHAS ESTATÍSTICAS</a></p>
                    <p class="linkRight"><a href="` + BASE_URL + `usuario/compras"><i class="fas fa-shopping-bag"></i> &nbsp;HISTÓRICO DE COMPRAS</a></p>
                    <p class="linkRight"><a href="` + BASE_URL + `usuario/notificacoes"><i class="fas fa-bell"></i> &nbsp;NOTIFICAÇÕES</a></p>
                    <p class="linkRight esqSenhaMod"><a href=""><i class="fas fa-question"></i> &nbsp;RECUPERE SUA SENHA</a></p>
                   
                    <p class="linkRight logout"><a href=""><i class="fas fa-sign-out-alt"></i> &nbsp;SAIR</a></p>
   
                    <p class="linkDate">JUNTOS DESDE ` + json['usuario']['usu_registro'] + `</p>
                </div>
                `);
                
                var span1 = document.getElementsByClassName("close")[0];
                span1.onclick = function() {
                    modal1.style.display = "none";
                }

                $('.esqSenhaMod').click(function() {
                    Swal.fire({
                        title: "Deseja mesmo recuperar sua senha?",
                        text: "Enviaremos um email para você",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonColor: "#494949",
                        cancelButtonText: "Cancelar",
                        confirmButtonColor: "#9C45EB",
                        confirmButtonText: "Sim, recuperar"
                    }).then((result) => {
                        if(result.value) {
                            $.ajax({
                                url: BASE_URL + 'functions/esqueceuSenha',
                                dataType: 'json',
                                type: 'post',
                                beforeSend: function() {
                                    Toast.fire({
                                        title: loadingRes("Verificando...")
                                    });
                                },
                                success: function(json) {
                                    if (json['status']) {
                                        Toast.fire({
                                            title: loadingRes("Enviando email...")
                                        });
                                        window.location.href = BASE_URL + 'usuario/reset';
                                    } else {
                                        Swal.fire({
                                            title: "Um erro ocorreu:",
                                            text: json['error'].stripHTML(),
                                            type: "error",
                                            showCancelButton: false,
                                            confirmButtonColor: "#9C45EB",
                                            confirmButtonText: "Ok"
                                        })
                                    }
                                }
                            })
                        }
                    });

                    return false;
                });

                $('.logout').click(function() {
                    Swal.fire({
                        title: "Deseja mesmo sair?",
                        text: "Qualquer compra não finalizada será perdida permanentemente!",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonColor: "#494949",
                        cancelButtonText: "Cancelar",
                        confirmButtonColor: "#9C45EB",
                        confirmButtonText: "Sim, sair"
                    }).then((result) => {
                        if(result.value) {
                            window.location.href = BASE_URL + 'functions/logout';
                        }
                    });
                    return false;
                });
            } else {
                $('.s_login').html(`ENTRAR`);
            }
        }
    });
}

verificaLogin();