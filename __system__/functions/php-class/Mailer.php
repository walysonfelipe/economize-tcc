<?php
    use \PHPMailer\PHPMailer\PHPMailer;
    use \PHPMailer\PHPMailer\Exception;
    
    abstract class Mailer
    {
        const HOST = "educa-mais-casadacrianca.com.br";
        const USERNAME = 'adm@educa-mais-casadacrianca.com.br';
        const PASSWORD = 'fIJKO~evKR4#';
        const NAME_FROM = "Suporte Economize";
        const EMAIL_FROM = "Contato@educa-mais-casadacrianca.com.br";
        const TO_ADDRESS = "urbancode.adm@gmail.com";
        const TO_NAME = "UrbanCode";
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
