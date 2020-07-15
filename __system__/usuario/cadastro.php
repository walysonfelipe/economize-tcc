<?php
	use Model\User;
	
	$sql = new Sql();
	$rows = $sql->select("SELECT * FROM tipo_tel");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <title>e.conomize | Cadastro</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= Project::baseUrl(); ?>style/img/e-dark-icon.png"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= Project::baseUrl(); ?>style/css/minified-main.css">
    <link href="<?= Project::baseUrl(); ?>style/libraries/fontawesome-free-5.8.0-web/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?= Project::baseUrl(); ?>style/fonts/Icons/icons_pack/font/flaticon.css">
</head>
<body>
	<div class="l-wrapper_cadastro">
		<div class="l-topNavCad" id="topNav">
		<?php
			include('__system__/functions/includes/topNav.php');
		?>    
		</div>
		<div class="l-headerNavMobile" id="headerNav">
		<?php
			include('__system__/functions/includes/header.php');
		?>    
		</div>
		<div class="l-mainCad">
		<!-- <div class="circleCad">
			<p>Junte-se a família e.conomize!</p>
		</div> -->
		<div class="zigzag">&#10650</div>
		<div class="zigzag2">&#10650</div>
		<div class="zigzag3">&#10650</div>
			<form id="form-cadastro" class="formCad">
				<div class="divBehindCad">
					<div class="divListaOnCad">
						<label for="">VANTAGENS</label>
						<ul class="listaOnCad">
							<li class="linkListaOnCad"><i class="far fa-check-circle"></i> Acesso às compras</li>
							<li class="linkListaOnCad"><i class="far fa-check-circle"></i> Receba notícias</li>
							<li class="linkListaOnCad"><i class="far fa-check-circle"></i> Promoções</li>
							<li class="linkListaOnCad"><i class="far fa-check-circle"></i> Devoluções</li>
							<li class="linkListaOnCad"><i class="far fa-check-circle"></i> Central de atendimento</li>
							<li class="linkListaOnCad"><i class="far fa-check-circle"></i> Cupons de desconto</li>
						</ul>
					</div>
					<div class="arrowCadTop"><img src="<?= Project::baseUrl(); ?>style/img/whitearrow.png" alt=""></div>
					<div class="divCadTop">
						<h2>Cadastre-se</h2>
						<div class="divCadLeft">
							<div class="divisorTitle">
								<h6>Dados Pessoais</h6>
							</div>
							<div class="divisorData"></div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" id="usu_nome" name="usu_nome" class="placeholder-shown" placeholder=" "/>
									<label class="labelFieldCad" for="usu_nome"><strong>NOME</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" id="usu_sobrenome" name="usu_sobrenome" class="placeholder-shown" placeholder=" "/>
									<label class="labelFieldCad" for="usu_sobrenome"><strong>SOBRENOME</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<label class="labelCadSex"><strong>SEXO:</strong></label>
								<input class="radioCad" type="radio" value="M" id="usu_sexo_m" name="usu_sexo" checked/> <label class="labelCadSexRadio" for="usu_sexo_m">MASC</label>
								<input class="radioCad" type="radio" value="F" id="usu_sexo_f" name="usu_sexo"/> <label class="labelCadSexRadio" for="usu_sexo_f">FEM</label>
								<input class="radioCad" type="radio" value="O" id="usu_sexo_o" name="usu_sexo"/> <label class="labelCadSexRadio" for="usu_sexo_o">OUTRO</label>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" class="cpf placeholder-shown" id="usu_cpf" name="usu_cpf" placeholder=" "/>
									<label class="labelFieldCad" for="usu_cpf"><strong>CPF</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" id="usu_email" name="usu_email" class="placeholder-shown" placeholder=" "/>
									<label class="labelFieldCad" for="usu_email"><strong>EMAIL</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="password" id="usu_senha" name="usu_senha" class="placeholder-shown" placeholder=" "/>
									<label class="labelFieldCad" for="usu_senha"><strong>SENHA</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="password" id="usu_senha2" name="usu_senha2" class="placeholder-shown" placeholder=" "/>
									<label class="labelFieldCad" for="usu_senha2"><strong>CONFIRMAR SENHA</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md" id="telefone">
									<input type="text" class="sp_celphones placeholder-shown" placeholder=" " name="tel_num[]" id="tel_id1"/>
									<label class="labelFieldCad" for="tel_id1"><strong>TELEFONE</strong></label>
									<div class="">
										<select class="selectTypeTel" name="tipo_tel[]">
											<optgroup label=" TIPO DE TELEFONE">
												<?php foreach ($rows as $row): ?>
													<option value="<?= $row['tpu_tel_id']; ?>">
														<?= $row['tpu_tel_nome']; ?>
													</option>
												<?php endforeach; ?>
											</optgroup>
										</select>
									</div>
									<button type="button" class="btnAddTel" id="add_telefone"><i class="fas fa-plus-circle"></i></button>
								</div>
								<div class="help-block-tel"></div>
							</div>
						</div>
					</div>
					<div class="divCadBottom">
						<div class="divCadRight">
							<div class="divisorTitle divisorMargin">
								<h6>Dados Residenciais</h6>
							</div>
							<div class="divisorData"></div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="form-control cep placeholder-shown" id="usu_cep" name="usu_cep"/>
									<label class="labelFieldCad" for="usu_cep"><strong>CEP</strong></label>
								</div>
								<span class="answer-cep"></span>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="placeholder-shown" id="usu_end" name="usu_end"/>
									<label class="labelFieldCad" for="usu_end"><strong>LOGRADOURO</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="placeholder-shown" id="usu_num" name="usu_num"/>
									<label class="labelFieldCad" for="usu_num"><strong>NÚMERO</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="placeholder-shown" id="usu_complemento" name="usu_complemento"/>
									<label class="labelFieldCad" for="usu_complemento"><strong>COMPLEMENTO</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="placeholder-shown" id="usu_bairro" name="usu_bairro"/>
									<label class="labelFieldCad" for="usu_bairro"><strong>BAIRRO</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="placeholder-shown" id="usu_cidade" name="usu_cidade"/>
									<label class="labelFieldCad" for="usu_cidade"><strong>CIDADE</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad">
								<div class="field -md">
									<input type="text" placeholder=" " class="placeholder-shown" id="usu_uf" name="usu_uf"/>
									<label class="labelFieldCad" for="usu_uf"><strong>ESTADO</strong></label>
								</div>
								<div class="help-block"></div><br/>
							</div>
							<div class="outsideSecInputCad" style="margin-bottom:-20px;">
								<input type="checkbox" class="radioCad" id="usu_cookie" name="usu_cookie"/> 
								<label class="labelCadSexRadio" style="font-size:8pt;" for="usu_cookie">Lembre de mim</label>
							</div>
							<div class="outsideSecInputCad">
								<input type="checkbox" class="radioCad" id="usu_mailmkt" name="usu_mailmkt"/> 
								<label class="labelCadSexRadio" style="font-size:8pt;" for="usu_mailmkt">Desejo receber notificações do e.conomize no meu email</label>
							</div>
						
							<div class="btnSendCad">
								<button class="btnSubCad" type="submit" id="btn-cad" value="Cadastrar">CADASTRAR</button>
								<div class="help-block"></div>
							</div>
						</div>
					</div>
				</div>
			</form>		
		</div>
		
		<?php
            include('__system__/functions/includes/modal.php');
		?>
		
		<div class="l-footer" id="footer">
        <?php
            include('__system__/functions/includes/footer.php');
		?>
		</div>
        <div class="l-footerBottomCad" id="footerBottom">
		<?php
            include('__system__/functions/includes/bottomFooter.html');
        ?>
		</div>
    </div>

	<script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-3.3.1.min.js"></script>
	<script src="<?= Project::baseUrl(); ?>style/libraries/OwlCarousel2-2.3.4/dist/owl.carousel.js"></script>
    <script src="<?= Project::baseUrl(); ?>style/libraries/sweetalert2.all.min.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/util.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/JQuery/jquery-mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/mask.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/verificaLogin.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/main.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/login.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/cadastroUsuario.js"></script>
    <script src="<?= Project::baseUrl(); ?>js/listArmazem.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var campos_max = 5;
			var x = 1;
			$('#add_telefone').click(function(e) {
				e.preventDefault();
				if (x < campos_max) {
					$('#telefone').append(`
						<div class="telPlus">
							<div class="outsideSecInputCadPlus">
								<div class="field -md">
									<input type="text" placeholder=" Número Tel" class="sp_celphones placeholder-shown" name="tel_num[]" id="tel_id` + (x + 1) + `"/>
									<label class="labelFieldCad" for="tel_id` + (x + 1) + `"><strong>TELEFONE</strong></label>
								</div>
								<div class="field -md">
									<select class="selectTypeTel" name="tipo_tel[]">
										<optgroup label="TIPO DO TELEFONE">
											<?php foreach ($rows as $row): ?>
												<option value="<?= $row['tpu_tel_id']; ?>">
													<?= $row['tpu_tel_nome']; ?>
												</option>
											<?php endforeach; ?>
										</optgroup>
									</select>
								</div>
								<div class="btnRemove">
									<a href="#" class="remover_campo"><i class="fas fa-times"></i></a>
								</div>
							</div>
						</div>
					`);
					x++;
				}
				mask();
			});
	
			// Remover o div anterior
			$('#telefone').on("click",".remover_campo",function(e) {
					e.preventDefault();
					$(this).parent().parent('div').remove();
					$(this).parent('div').remove();
					x--;
			});
		});
	</script>
	<?php
		if (isset($_SESSION[User::SESSION])):?>
			<script>
				Swal.fire({
					title: "e.conomize informa:",
					text: "Você já está logado! Por favor, primeiramente faça logout.",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: "#494949",
					cancelButtonText: "Cancelar",
					confirmButtonColor: "#9C45EB",
					confirmButtonText: "Ok, logout"
				}).then((result) => {
					if (result.value) {
						<?php $_SESSION["url_sair"] = Project::baseUrlPhp() . "usuario/cadastro"; ?>
						window.location.href = BASE_URL + "functions/logout";
					} else {
						window.location.href = BASE_URL;
					}
				});
			</script>
			<?php
		endif;
	?>
</body>
</html>