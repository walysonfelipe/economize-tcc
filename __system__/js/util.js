String.prototype.stripHTML = function() {
    return this.replace(/<.*?>/g, '')
}

const BASE_URL = "http://localhost/economize/";
const BASE_URL2 = "http://localhost/economize/__system__/";
const BASE_URL3 = "http://localhost/economize/__system__/admin-area/img-produtos/";
const BASE_URL4 = "http://localhost/economize/admin-area/";

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000
});

function loadingRes(message = "") {
    return "<p class='p-loading'><i class='fa fa-circle-notch fa-spin'></i> &nbsp;" + message + "</p>";
}

function loadingResSmall(message = "") {
    return "<small><i class='fa fa-circle-notch fa-spin'></i> &nbsp;"+message+"</small>";
}

function clearErrors() {
    $(".has-error").removeClass("has-error");
    $(".help-block").html("");
    $(".help-block-login").html("");
}

function showErrors(error_list) {
    clearErrors();
    $.each(error_list, function(id, message) {
        $(id).parent().siblings(".help-block").html(message);
    })
}

function showErrorsAdmin(error_list) {
    clearErrors();
    $.each(error_list, function(id, message) {
        $(id).siblings(".help-block").html(message);
    })
}

function messages() {
    $.ajax({
        dataType: 'json',
        url: 'functions/messages.php',
        success: function(json) {
            if(json["message"]) {
                Swal.fire({
                    title: json["title"],
                    text: json["text"],
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#d9534f",
                    confirmButtonText: "Ok",
                });
            }
        }
    });
}