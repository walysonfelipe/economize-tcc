        <div class="myModalArmazem" id="myModalArmazem">
			<div class="modalArmazemContent">
                <div class="modalArmTopContent">
                    <div class="meuArmazem">
                        
                    </div>
                    <span class="closeModalArmazem">&times;</span>
                </div>
                <div class="modalArmBottomContent">
                    <div class="Armazens">
                        <h1 align="center"><i class='fa fa-circle-notch fa-spin'></i></h1>
                    </div>
                </div>
			</div>
		</div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modalLeftContent">
                    <form id="form-login">
                        <h4 class="titleModalLogin">LOG IN</h4>
                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="text" name="usu_email_login" id="usu_email_login" class="placeholder-shown" placeholder=" "/>
                                <label class="labelFieldCad" for="usu_email_login"><strong><i class="fas fa-envelope"></i> EMAIL</strong></label>
                            </div>
                            <div class="help-block"></div><br/>
                        </div>
                        <div class="outsideSecInputCad">
                            <div class="field -md">
                                <input type="password" name="usu_senha_login" id="usu_senha_login" class="placeholder-shown" placeholder=" "/>
                                <label class="labelFieldCad" for="usu_senha_login"><strong><i class="fas fa-unlock"></i> SENHA</strong></label>
                            </div>
                            <div class="help-block"></div><br/>
                        </div>
                        <div class="outsideSecInputCad mantCon">
                            <input type="checkbox" class="radioCad" id="usu_cookie_login" name="usu_cookie_login"/> 
                            <label class="labelCadSexRadio" style="font-size:10px;" for="usu_cookie_login">Lembre de mim</label>
                        </div>
                        <button class="btnSend" type="submit" id="btn-login">ENTRAR</button>
                        <div class="help-block-login"></div>
                        <p class="linkForgotPassword">
                            <a href="<?= Project::baseUrlPhp(); ?>usuario/esqueceu-senha">Esqueceu a senha?</a>
                        </p>
                    </form>
                </div>
                <div class="modalRightContent">
                    <span class="close">&times;</span>
                    <p class="textModal">Olá, amigo!</p>
                    <p class="textModalBottom">Entre com seus detalhes pessoais e comece sua jornada conosco</p>
                    <div class="divLinkCad">
                        <a class="linkCadModal" href="<?= Project::baseUrlPhp(); ?>usuario/cadastro">Cadastre-se já</a>
                    </div>    
                </div>
            </div>
        </div>

        <div class="myModalProduto" id="myModalProduto">
            <div class="modalProdutoContent">
            <span class="closeModalProduto">&times;</span>
                <div class="showProdutoModal">
                    
                </div>
            </div>
        </div>
        <!-- -------------------- -->