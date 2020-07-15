<?php
    use \PHPMailer\PHPMailer\{PHPMailer, Exception};

    namespace Mailer;
    
    require '__system__/functions/phpmailer/vendor/autoload.php';

    class Error extends \Mailer
    {
        public function __construct($dataError = array())
        {
            parent::__construct();

            $this->mail->setFrom(Error::EMAIL_FROM, Error::NAME_FROM);
            $this->mail->addAddress(Error::TO_ADDRESS, Error::TO_NAME);

            $html = file_get_contents("__system__/functions/phpmailer/templates/template-error.php");

            $html = str_replace("**PROJECT**", Error::PROJECT, $html);
            $html = str_replace("**HORARIO**", Date("d/m/Y") . " às " . Date("H:i"), $html);

            $jsonError = " &nbsp;&nbsp;&nbsp;{";
            foreach ($dataError as $k => $v) {
                $jsonError .= "<br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$k}: {$v}";
            }
            $jsonError .= "<br/> &nbsp;&nbsp;&nbsp;}";
            
            $html = str_replace("**JSON_ERROR**", $jsonError, $html);
            
            if (\Model\User::checkLogin() === true) {
                $user = "Usuário logado ao disparo de erro: " . $_SESSION[\Model\User::SESSION]['usu_first_name'] . " " . $_SESSION[\Model\User::SESSION]['usu_last_name'];
                $html = str_replace("**LOG**", $user, $html);
            } else {
                $user = "Não havia um usuário logado ao disparo de erro";
                $html = str_replace("**LOG**", $user, $html);
            }

            // Content
            $this->mail->isHTML(true);
            
            $this->mail->Subject = "OCORREU UM ERRO NO PROJETO " . strtoupper(Error::PROJECT);
            $this->mail->Body = $html;
            $this->mail->AltBody = "OCORREU UM ERRO NO PROJETO " . strtoupper(Error::PROJECT);
        }
    }
