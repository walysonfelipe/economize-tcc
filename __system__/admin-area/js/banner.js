var qtd_result = 5;
var page = 1;
var max_links = 2;

function dataBanners(page, qtd_result) {
    var dados = new FormData();
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
		cache: false,
		contentType: false,
		processData: false,
        url: BASE_URL4 + 'functions/banner',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="5" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                if(!json['empty']) {
                    $('.tbodyProd').html("");
                    for(var i = 0; json['banners'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td><img class="imgBanner" style="width:100%;" src="` + BASE_URL2 + `img/banner/` + json['banners'][i].banner_path + `"/></td>
                                <td class="tdCenter">` + json['banners'][i].banner_nome + `</td>
                                <td class="tdCenter">` + json['banners'][i].banner_status + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    
                    deleteBanner();
                    modalUpd();
                    updBanner();
                    uploadImg();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- NÃO HÁ BANNERS CADASTRADOS -</th>
                        </tr>
                    `);
                }
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` banners
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="dataBanners(1, qtd_result)">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataBanners(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="dataBanners(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="dataBanners(` + totPage + `, qtd_result)">Última</a>
            `);
        }
    });
}

function updBanner() {
    $(".btnEditBanner").click(function(e) {
        e.preventDefault();
        clearErrors();
        var dado = "updBanner_id=" + $(this).attr("id-banner");
        $.ajax({
            dataType: 'json',
                type: 'post',
                data: dado,
                url: BASE_URL4 + 'functions/banner',
                beforeSend: function() {
                    $('#banner_idUpd').val("");
                    $('#banner_nomeUpd').val("");

                    $('#status0').attr("selected", false);
                    $('#status1').attr("selected", false);
                    $('#status01').attr("selected", true);
                },
                success: function(json) {
                    $('#banner_idUpd').val(json['banner']['banner_id']);

                    if(json['banner']['banner_status'] == 1) {
                        $('#status0').attr("selected", false);
                        $('#status1').attr("selected", true);
                    } else {
                        $('#status1').attr("selected", false);
                        $('#status0').attr("selected", true);
                    }

                    $('#banner_nomeUpd').val(json['banner']['banner_nome']);

                    $('.imgUpload').attr("src", BASE_URL2 + `img/banner/` + json['banner']['banner_path']);
                    updateBanner();
                }
        });
    });
}

function updateBanner() {
    $('.formUpdateBanner').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/banner',
            type: 'POST',
            data: formData,
            beforeSend() {
                clearErrors();
                $("#btnUpdateBanner").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                if(json['status']) {
                    Swal.fire({
                        title: "Banner editado com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if(result.value) {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataBanners(page, qtd_result);
                        } else {
                            var modalUpd = document.getElementById('myModalUpd');
                            modalUpd.style.display = "none";
                            dataBanners(page, qtd_result);
                        }
                    });
                } else {
                    $("#btnUpdateBanner").siblings(".help-block").html(json['error']);
                }
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function () {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
            return myXhr;
            }
        });
    });
}

function insertBanner() {
    $('.formInserirBanner').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            dataType: 'json',
            url: BASE_URL4 + 'functions/banner',
            type: 'POST',
            data: formData,
            beforeSend() {
                clearErrors();
                $("#btnInsertBanner").siblings(".help-block").html(loadingRes("Verificando..."));
            },
            success: function(json) {
                clearErrors();
                dataBanners(page, qtd_result);
                
                if(json['status']) {
                    Swal.fire({
                        title: "Banner(s) cadastrado(s) com sucesso!",
                        text: "Deseja continuar cadastrando banner(s)?",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#333",
                        confirmButtonText: "Continuar",
                        cancelButtonColor: "#999",
                        cancelButtonText: "Sair"
                    }).then((result) => {
                        if(result.value) {
                            mostraModalAdd();
                        } else {
                            modalAdd.style.display = "none";
                        }
                    });
                } else {
                    $("#btnInsertBanner").siblings(".help-block").html(json['error']);
                }
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function () {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
            return myXhr;
            }
        });
    });
}

function uploadImg() {
    $("input[type=file]").on("change", function(e){
        e.preventDefault();
        var input = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return;

        if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);

            reader.onload = function() {
                input.siblings(".imgUpload").attr('src', this.result);
            }
        }
    });
}

function deleteBanner() {
    $('.btnDelBanner').click(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: "Deseja mesmo excuir este banner?",
            text: "Uma vez feito, não haverá volta!",
            type: "warning",
            showCancelButton: true,
            cancelButtonColor: "#494949",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#A94442",
            confirmButtonText: "Sim, excluir"
        }).then((result) => {
            if(result.value) {
                var dado = "delBanner_id=" + $(this).attr("id-banner");
                $.ajax({
                    dataType: 'json',
                    url: BASE_URL4 + 'functions/banner',
                    type: 'POST',
                    data: dado,
                    success: function(json) {
                        if(json['status']) {
                            Swal.fire({
                                title: "Banner excluido com sucesso!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#494949",
                                confirmButtonText: "Ok"
                            });
                            dataBanners(page, qtd_result);
                        } else {
                            Swal.fire({
                                title: "Ocorreu um erro ao excluir banner!",
                                text: json['error_del'],
                                type: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#494949",
                                confirmButtonText: "Ok"
                            });
                        }
                    }
                });
            }
        });
    });
}

function ordenarBannerSec(page, qtd_result, sortType) {
    var tipoSort = sortType;

    var dados = new FormData();
    dados.append("data_sort",  sortType);
    dados.append("page", page);
    dados.append("qtd_result", qtd_result);
    dados.append("sec",  "1");

    if($('#searchBanner').val().length > 0) {
        $('.divResetSearch').html(``);
        $('#searchBanner').val(``);
    }

    $.ajax({
        dataType: 'json',
        type: 'post',
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        url: BASE_URL4 + 'functions/banner',
        beforeSend: function() {
            $('.tbodyProd').html(`
                <tr>
                    <th colspan="5" class="thNoData">
                        - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                    </th>
                </tr>
            `);
        },
        success: function(json) {
            if(json['status']) {
                $('.tbodyProd').html("");

                for(var i = 0; json['banners'].length > i; i++) {
                    $('.tbodyProd').append(`
                        <tr>
                            <td><img class="imgBanner" style="width:100%;" src="` + BASE_URL2 + `img/banner/` + json['banners'][i].banner_path + `"/></td>
                            <td class="tdCenter">` + json['banners'][i].banner_nome + `</td>
                            <td class="tdCenter">` + json['banners'][i].banner_status + `</td>
                            <td class="tdCenter">
                                <button class="myBtnUpd btnEditBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-edit"></i></button>
                                <button class="btnDelBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `);
                }
                
                deleteBanner();
                modalUpd();
                updBanner();
                uploadImg();
            } else {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                    </tr>
                `);
            }
            $('.registShow').html(`
                Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` banners
            `);

            var totPage = Math.ceil(json['registrosTotal'] / qtd_result);

            $('.paginacao').html(`
                <a href="#" class="linkPaginacao" onclick="ordenarBannerSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
            `);

            for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                if(pag_ant >= 1) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarBannerSec(` + pag_ant + `, qtd_result, '` + tipoSort + `')">` + pag_ant + `</button> 
                    `);
                }
            }

            $('.paginacao').append(` ` + page + ` `);

            for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                if(pag_dep <= totPage) {
                    $('.paginacao').append(`
                        <button class="btnPaginacao" onclick="ordenarBannerSec(` + pag_dep + `, qtd_result, '` + tipoSort + `')">` + pag_dep + `</button> 
                    `);
                }
            }

            $('.paginacao').append(`
                <a href="#" class="linkPaginacao" onclick="ordenarBannerSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
            `);
        }
    });
}

function ordenarBanner(page, qtd_result) {
    $('.sort').click(function(e) {
        e.preventDefault();

        var elementoPai = $(this).parent();
        var elementosFilho = elementoPai.find(".span_sort");
        elementosFilho.html("");

        var sort = $(this).find(".span_sort");
        
        var tipoSort = $(this).attr("data-sort");

        var dados = new FormData();
        dados.append("data_sort",  $(this).attr("data-sort"));
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        if($('#searchBanner').val().length > 0) {
            $('.divResetSearch').html(``);
            $('#searchBanner').val(``);
        }

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/banner',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">
                            - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                        </th>
                    </tr>
                `);
            },
            success: function(json) {
                if(json['status']) {
                    $('.tbodyProd').html("");
                    if(json['sort'] == "up") {
                        sort.html(` &nbsp;&nbsp;<i class="fas fa-sort-up"></i>`);
                    } else if(json['sort'] == "down") {
                        sort.html(` &nbsp;&nbsp;<i class="fas fa-sort-down"></i>`);
                    }

                    for(var i = 0; json['banners'].length > i; i++) {
                        $('.tbodyProd').append(`
                            <tr>
                                <td><img class="imgBanner" style="width:100%;" src="` + BASE_URL2 + `img/banner/` + json['banners'][i].banner_path + `"/></td>
                                <td class="tdCenter">` + json['banners'][i].banner_nome + `</td>
                                <td class="tdCenter">` + json['banners'][i].banner_status + `</td>
                                <td class="tdCenter">
                                    <button class="myBtnUpd btnEditBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-edit"></i></button>
                                    <button class="btnDelBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `);
                    }
                    
                    deleteBanner();
                    modalUpd();
                    updBanner();
                    uploadImg();
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` banners
                `);

                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="ordenarBannerSec(1, qtd_result, '` + tipoSort + `')">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarBannerSec(` + pag_ant + `,qtd_result,  '` + tipoSort + `')">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="ordenarBannerSec(` + pag_dep + `,qtd_result,  '` + tipoSort + `')">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="ordenarBannerSec(` + totPage + `, qtd_result, '` + tipoSort + `')">Última</a>
                `);
            }
        });
    });
}

function searchBannerSec(page, qtd_result) {
    if($('#searchBanner').val().length > 0) {
        $('.divResetSearch').html(`
            <button type="reset" class="inputResetSearch">
                <i class="far fa-times-circle"></i>
            </button>
        `);
        
        var dados = new FormData();
        dados.append("searchBanner",  $('#searchBanner').val());
        dados.append("page", page);
        dados.append("qtd_result", qtd_result);

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            url: BASE_URL4 + 'functions/banner',
            beforeSend: function() {
                $('.tbodyProd').html(`
                    <tr>
                        <th colspan="5" class="thNoData">
                            - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                        </th>
                    </tr>
                `);
            },
            success: function(json) {
                if(json['status']) {
                    if(!json['empty']) {
                        $('.tbodyProd').html("");
                        for(var i = 0; json['banners'].length > i; i++) {
                            $('.tbodyProd').append(`
                                <tr>
                                    <td><img class="imgBanner" style="width:100%;" src="` + BASE_URL2 + `img/banner/` + json['banners'][i].banner_path + `"/></td>
                                    <td class="tdCenter">` + json['banners'][i].banner_nome + `</td>
                                    <td class="tdCenter">` + json['banners'][i].banner_status + `</td>
                                    <td class="tdCenter">
                                        <button class="myBtnUpd btnEditBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-edit"></i></button>
                                        <button class="btnDelBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            `);
                        }
                        
                        deleteBanner();
                        modalUpd();
                        updBanner();
                        uploadImg();
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="5" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                            </tr>
                        `);
                    }
                } else {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                        </tr>
                    `);
                }
                $('.registShow').html(`
                    Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` banners
                `);
    
                var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
    
                $('.paginacao').html(`
                    <a href="#" class="linkPaginacao" onclick="searchBannerSec(1, qtd_result)">Primeira</a> 
                `);
    
                for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                    if(pag_ant >= 1) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchBannerSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(` ` + page + ` `);
    
                for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                    if(pag_dep <= totPage) {
                        $('.paginacao').append(`
                            <button class="btnPaginacao" onclick="searchBannerSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                        `);
                    }
                }
    
                $('.paginacao').append(`
                    <a href="#" class="linkPaginacao" onclick="searchBannerSec(` + totPage + `, qtd_result)">Última</a>
                `);
            }
        });
    } else {
        $('.divResetSearch').html(``);
        dataBanners(1, qtd_result);
    }
}

function searchBanner(page, qtd_result) {
    $('#searchBanner').keyup(function(e) {
        e.preventDefault();

        if($(this).val().length > 0) {
            $('.divResetSearch').html(`
                <button type="reset" class="inputResetSearch">
                    <i class="far fa-times-circle"></i>
                </button>
            `);
            
            var dados = new FormData();
            dados.append("searchBanner",  $(this).val());
            dados.append("page", page);
            dados.append("qtd_result", qtd_result);

            $.ajax({
                dataType: 'json',
                type: 'post',
                data: dados,
                cache: false,
                contentType: false,
                processData: false,
                url: BASE_URL4 + 'functions/banner',
                beforeSend: function() {
                    $('.tbodyProd').html(`
                        <tr>
                            <th colspan="5" class="thNoData">
                                - <i class='fa fa-circle-notch fa-spin'></i> PROCESSANDO -
                            </th>
                        </tr>
                    `);
                },
                success: function(json) {
                    if(json['status']) {
                        if(!json['empty']) {
                            $('.tbodyProd').html("");
                            for(var i = 0; json['banners'].length > i; i++) {
                                $('.tbodyProd').append(`
                                    <tr>
                                        <td><img class="imgBanner" style="width:100%;" src="` + BASE_URL2 + `img/banner/` + json['banners'][i].banner_path + `"/></td>
                                        <td class="tdCenter">` + json['banners'][i].banner_nome + `</td>
                                        <td class="tdCenter">` + json['banners'][i].banner_status + `</td>
                                        <td class="tdCenter">
                                            <button class="myBtnUpd btnEditBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-edit"></i></button>
                                            <button class="btnDelBanner btnProductConfigAdm" id-banner="` + json['banners'][i].banner_id + `"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                `);
                            }
                            
                            deleteBanner();
                            modalUpd();
                            updBanner();
                            uploadImg();
                        } else {
                            $('.tbodyProd').html(`
                                <tr>
                                    <th colspan="5" class="thNoData">- NÃO HOUVE RESPOSTA -</th>
                                </tr>
                            `);
                        }
                    } else {
                        $('.tbodyProd').html(`
                            <tr>
                                <th colspan="5" class="thNoData">- OCORREU UM ERRO -</th>
                            </tr>
                        `);
                    }
                    $('.registShow').html(`
                        Mostrando ` + json['registrosMostra'] + ` de ` + json['registrosTotal'] + ` banners
                    `);
        
                    var totPage = Math.ceil(json['registrosTotal'] / qtd_result);
        
                    $('.paginacao').html(`
                        <a href="#" class="linkPaginacao" onclick="searchBannerSec(1, qtd_result)">Primeira</a> 
                    `);
        
                    for(var pag_ant = (page - max_links); pag_ant <= (page - 1); pag_ant++) {
                        if(pag_ant >= 1) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchBannerSec(` + pag_ant + `, qtd_result)">` + pag_ant + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(` ` + page + ` `);
        
                    for(var pag_dep = (page + 1); pag_dep <= (page + max_links); pag_dep++) {
                        if(pag_dep <= totPage) {
                            $('.paginacao').append(`
                                <button class="btnPaginacao" onclick="searchBannerSec(` + pag_dep + `, qtd_result)">` + pag_dep + `</button> 
                            `);
                        }
                    }
        
                    $('.paginacao').append(`
                        <a href="#" class="linkPaginacao" onclick="searchBannerSec(` + totPage + `, qtd_result)">Última</a>
                    `);
                }
            });
        } else {
            $('.divResetSearch').html(``);
            dataBanners(1, qtd_result);
        }
    });
}

dataBanners(page, qtd_result);
searchBanner(1, qtd_result);
ordenarBanner(1, qtd_result);