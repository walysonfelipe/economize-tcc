$(document).ready(function() {
    $('.btn-arm').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Deseja mesmo trocar o armazém?",
            text: "Qualquer compra não finalizada será perdida permanentemente!",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#9C45EB",
            confirmButtonText: "Sim, trocar"
        }).then((result) => {
            if(result.value) {
                var dado = 'arm_id=' + $(this).attr('id-armazem');
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: dado,
                    url: BASE_URL + 'functions/escolherArmazem',
                    success: function(json) {
                        if(json['status']) {
                            window.location.href = BASE_URL;
                        } else {
                            Swal.fire({
                                title: "e.conomize informa:",
                                text: "Um erro inesperado aconteceu! Estamos trabalhando para consertá-lo.",
                                type: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#9C45EB",
                                confirmButtonText: "Ok"
                            });
                        }
                    }
                });
            }
        });
    });
});