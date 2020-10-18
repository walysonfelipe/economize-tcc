<?php
    use \PHPMailer\PHPMailer\PHPMailer;
    use \PHPMailer\PHPMailer\Exception;
    
    abstract class Mailer
    {
        const HOST = "testesite.com.br";
        const USERNAME = 'teste@testesite.com.br';
        const PASSWORD = 'SENHA';
        const NAME_FROM = "teste";
        const EMAIL_FROM = "contato@testesite.com.br";
        const TO_ADDRESS = "teste.adm@gmail.com";
        const TO_NAME = "teste";
        const PROJECT = "Mercado Digital e.conomize";
        protected $mail;

        public function __construct($dataError = array())
        {
            $this->mail = new PHPMailer(true);
            
            $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();
            $this->mail->Host = Mailer::HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = Mailer::USERNAME;
            $this->mail->Password = Mailer::PASSWORD;
            $this->mail->SMTPSecure = "ssl";

            $this->mail->Port = 465;
            $this->mail->setLanguage('pt-br', '/optional/path/to/language/directory/');
            $this->mail->CharSet = 'UTF-8';
        }

        public function send()
        {
            return $this->mail->send();
        }
    }
