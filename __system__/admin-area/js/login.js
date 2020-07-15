$('#form-login-adm').submit(function(e) {
    e.preventDefault();
    var dado = $(this).serialize();

    $.ajax({
        dataType: 'json',
        url: BASE_URL4 + 'functions/login',
        type: 'post',
        data: dado,
        beforeSend: function() {
            clearErrors();
            $("#btnLogin").siblings(".help-block").html(loadingRes("Verificando..."));
        },
        success: function(json) {
            clearErrors();
            
            if(json['status']) {
                $("#btnLogin").siblings(".help-block").html(loadingRes("Logando..."));
                window.location.href = BASE_URL4 + 'dashboard';
            } else {
                $("#btnLogin").siblings(".help-block").html(json['error']);
            }
        }
    });

    return false;
});