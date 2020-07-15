<ul class="progress-tracker progress-tracker--word progress-tracker--word-left progress-tracker--center anim-ripple-large">
    <li class="progress-step is-complete compCart">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 1</h4>
            <i class="fas fa-shopping-cart"></i> CARRINHO
        </span>
    </li>
    <li class="progress-step is-complete compEnd">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 2</h4>
            <i class="fas fa-map-marker-alt"></i> ENDEREÇO
        </span>
    </li>
    <li class="progress-step is-active">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 3</h4>
            <i class="far fa-clock"></i> AGENDAMENTO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 4</h4>
            <i class="far fa-credit-card"></i> PAGAMENTO
        </span>
    </li>
    <li class="progress-step">
        <span class="progress-marker"></span>
        <span class="progress-text">
            <h4 class="progress-title">PASSO 5</h4>
            <i class="fas fa-file-alt"></i> EXTRATO
        </span>
    </li>
</ul>
<h2 align="center" class="tituloOfertas"><i class="far fa-clock"></i> AGENDAMENTO</h2>
<div class="divAgend">
    <h4 class="titleStep2_1">Escolha o horário que você quer que entreguemos a compra (prazo máximo de uma hora e meia)!</h4>
    <div class="agendTime">
        <i class="fas fa-map-marker-alt"></i>
        <p class="endAgendName">
            <?php
                foreach ($_SESSION['end_agend'] as $k => $v) {
                    if (($v != "") && ($k != (count($_SESSION['end_agend']) - 1))) {
                        echo $v . ", ";
                    } else {
                        echo $v;
                    }
                }
            ?>
        </p>
    </div>
    <div class="inputsRadioAgend">
        
    </div>
</div>
<script>
    $('.is-complete').css({'cursor': 'pointer'});
    $('.compCart').click(function(e) {
        e.preventDefault();
        
        buscaCarrinho();
    });
    $('.compEnd').click(function(e) {
        e.preventDefault();
        
        buscaEndereco();
    });

    $(function() {
        $.ajax({
            url: BASE_URL + 'functions/agendamento',
            success: function(response) {
                $('.inputsRadioAgend').html(response);
                $("#hora_agend").submit(function() {
                    $.ajax({
                        dataType: 'json',
                        url: BASE_URL + 'functions/agendamento',
                        type: 'post',
                        data: $(this).serialize(),
                        success: function(json) {
                            if(json['status']) {
                                buscaPagamento();
                            } else {
                                Swal.fire({
                                    title: "E.conomize informa:",
                                    text: json['error_list'][0],
                                    type: "error",
                                    confirmButtonColor: "#A94442",
                                    confirmButtonText: "Ok"
                                });
                            }
                        }
                    });
                    return false;
                });
            }
        });
    });
</script>