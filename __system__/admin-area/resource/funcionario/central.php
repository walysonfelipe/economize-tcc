<?php
    require_once '__system__/functions/connection/conn.php';
    if(!isset($_SESSION['inf_func']['funcionario_id'])) {
        header("Location: " . base_url_adm_php() . "login");
    }
    if(isset($_SESSION['data_sort'])) {
        unset($_SESSION['data_sort']);
    }

    function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos) {
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$&*_+="; // $si contem os símbolos
        $senha = "";
       
        if ($maiusculas) {
              // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
              $senha .= str_shuffle($ma);
        }
       
        if ($minusculas) {
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($mi);
        }
    
        if ($numeros) {
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($nu);
        }
    
        if ($simbolos) {
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($si);
        }
    
        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return substr(str_shuffle($senha),0,$tamanho);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>e.conomize | Central de funcionários</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="<?= base_url(); ?>img/e_icon.png"/>
    <link href="<?= base_url_adm(); ?>style/admin.css" rel="stylesheet"/>
    <link href="<?= base_url(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet"/>
</head>
<body>
    <div class="l-wrapper">
    <?php
            require '__system__/admin_area/functions/includes/menu.php';
        ?>
        <section class="l-main">
            <div id="conteudo">
                <h3 class="titleAdm">GERENCIADOR DE FUNCIONÁRIOS</h3>
                <div id="conteudo">

                </div>
                <button class="linkAlterAdm"><i class="fa fa-plus"></i> &nbsp;Adicionar funcionário</button>
                <div class="divSearch">
                    <form class="formSearch">
                        <label for="searchFunc">Procure: </label>
                        <input type="text" class="inputSearch" id="searchFunc"/>
                        <div class="divResetSearch"></div>
                    </form>
                </div>
                <div class="divEcoTable">
                    <table width="80%" class="tableView tableProdConfigAdm" align="center">
                        <thead>
                            <th class="thTitle sort" data-sort="f.funcionario_nome" width="20%">NOME <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="f.funcionario_cpf" width="15%">CPF <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="f.funcionario_datanasc" width="18%">DATA NASC <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="f.funcionario_registro" width="18%">REGISTRO <span class="span_sort"></span></th>
                            <th class="thTitle sort" data-sort="s.setor_nome" width="16%">SETOR <span class="span_sort"></span></th>
                            <th class="thTitle" width="13%">AÇÕES</th>
                        </thead>
                        <tbody class="tbodyProd">

                        </tbody>
                    </table>
                    <span class="paginacao"></span>

                    <span class="registShow"></span>
                </div>
                <div class="dataProds">
                    
                </div>
            </div>
        </section>

        <div class="myModalAdd" id="myModalAdd">
            <div class="modalAddContent">
                <span class="closeModalAdd">&times;</span>
                <div class="showAddModal">
                    <div class="divCadProduto divCadFuncionario">
                        <form class="formInserir formInserirFuncionario">
                            <div class="divFuncionario">
                                <div style="margin-bottom:60px;">
                                    <div>
                                        <table class="tableSectionConfigArm" width="80%" align="center">
                                            <tr align="center">
                                                <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE FUNCIONÁRIOS</h2></td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                                                <td>
                                                    <input type="text" class="selectConfigArm" name="funcionario_nome[]"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>EMAIL</b></td>
                                                <td>
                                                    <input type="text" class="selectConfigArm" name="funcionario_email[]"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>SENHA</b></td>
                                                <td>
                                                    <?php
                                                        $senha_valida = FALSE;
                                                        while(!$senha_valida) {
                                                            $senha = gerar_senha(10, true, true, true, true);
                                                            $sel = $conn->prepare("SELECT funcionario_senha FROM funcionario");
                                                            $sel->execute();
                                                            if($sel->rowCount() > 0) {
                                                                $res = $sel->fetchAll();
                                                                foreach($res as $v) {
                                                                    if(password_verify($senha, $v['funcionario_senha'])) {
                                                                        $invalida = TRUE;
                                                                        break;
                                                                    }
                                                                }
                                                                if(!isset($invalida)) {
                                                                    $senha_valida = TRUE;
                                                                }
                                                            } else {
                                                                $senha_valida = TRUE;
                                                            }
                                                        }
                                                        echo $senha;
                                                    ?>
                                                    <input type="hidden" value="<?= $senha; ?>" name="funcionario_senha[]"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>CPF</b></td>
                                                <td><input type="text" class="selectConfigArm cpf" name="funcionario_cpf[]"/></td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>DATA DE NASCIMENTO</b></td>
                                                <td><input type="text" class="selectConfigArm date" name="funcionario_datanasc[]"/></td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="text-align:center;color:#9C45EB;"><b>SETOR</b></td>
                                                <td>
                                                    <select class="selectConfigArm" name="funcionario_setor[]">
                                                        <option value="*000*"> -- Selecione o setor: --</option>
                                                        <?php
                                                            $sel = $conn->prepare("SELECT * FROM setor");
                                                            $sel->execute();
                                                            if($sel->rowCount() > 0):
                                                                $results = $sel->fetchAll();
                                                                foreach($results as $k => $v):?>
                                                                    <option value="<?= $v['setor_id'] ?>"><?= $v['setor_nome']; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            endif;
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="button" class="addCadFuncionario">Adicionar mais funcionários</button>
                            </div>
                            <div class="divSubmit" align="center">
                                <button type="submit" id="btnInsertFuncionario"><i class="fas fa-save"></i> Cadastrar</button>
                                <div class="help-block"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= base_url(); ?>js/mask.js"></script>
    <script src="<?= base_url(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= base_url(); ?>js/util.js"></script>
    <script src="<?= base_url_adm(); ?>js/admin.js"></script>
    <script src="<?= base_url_adm(); ?>js/funcionario.js"></script>
    <script>
        $('.addCadFuncionario').click(function(e) {
            e.preventDefault();
            $('.divFuncionario').append(`
            <div class="newAdd">
                <table class="tableSectionConfigArm" width="80%" align="center">
                    <tr align="center">
                        <td colspan="8"><h2 style="text-align:center;color:#9C45EB;font-size:14px;">CADASTRO DE FUNCIONÁRIOS</h2></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>NOME</b></td>
                        <td>
                            <input type="text" class="selectConfigArm" name="funcionario_nome[]"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>SENHA</b></td>
                        <td>
                            <?php
                                $senha_valida = FALSE;
                                while(!$senha_valida) {
                                    $senha = gerar_senha(10, true, true, true, true);
                                    $sel = $conn->prepare("SELECT funcionario_senha FROM funcionario");
                                    $sel->execute();
                                    if($sel->rowCount() > 0) {
                                        $res = $sel->fetchAll();
                                        foreach($res as $v) {
                                            if(password_verify($senha, $v['funcionario_senha'])) {
                                                $invalida = TRUE;
                                                break;
                                            }
                                        }
                                        if(!isset($invalida)) {
                                            $senha_valida = TRUE;
                                        }
                                    } else {
                                        $senha_valida = TRUE;
                                    }
                                }
                                echo $senha;
                            ?>
                            <input type="hidden" value="<?= $senha; ?>" name="funcionario_senha[]"/>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>PERMIÇÕES</b></td>
                        <td>
                            <input type="checkbox" value="L" class="" id="per_ler" name="funcionario_permicao"/> <label for="per_ler">LER</label> <input type="checkbox" value="A" class="" id="per_alt" name="funcionario_permicao"/> <label for="per_alt">ALTERAR</label> <input type="checkbox" value="E" class="" id="per_exc" name="funcionario_permicao"/> <label for="per_exc">EXCLUIR</label> <input type="checkbox" value="R" class="" id="per_res" name="funcionario_permicao"/> <label for="per_res">RESPONDER MENSAGENS</label>
                        </td>
                    </tr> -->
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>CPF</b></td>
                        <td><input type="text" class="selectConfigArm cpf" name="funcionario_cpf[]"/></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>DATA DE NASCIMENTO</b></td>
                        <td><input type="text" class="selectConfigArm date" name="funcionario_datanasc[]"/></td>
                    </tr>
                    <tr>
                        <td align="center" style="text-align:center;color:#9C45EB;"><b>SETOR</b></td>
                        <td>
                            <select class="selectConfigArm" name="funcionario_setor[]">
                                <option value="*000*"> -- Selecione o setor: --</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM setor");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['setor_id'] ?>"><?= $v['setor_nome']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="btnRemove">
                    <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                </div>
            </div>
            `);
            mask();
        });

        // Remover o div anterior
        $('.divCadProduto').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });

        mask();
        insertFuncionario();
    </script>
    <?php
        if(isset($_GET['fnc'])):
            if($_GET['fnc'] == "IF"):?>
                <script>
                    modalAdd.style.display = "block";
                </script>
                <?php
            endif;
        endif;
    ?>
</body>
</html>