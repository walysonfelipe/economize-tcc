<?php
    use \PHPMailer\PHPMailer\{PHPMailer, Exception};

    namespace Mailer;
    
    require '__system__/functions/phpmailer/vendor/autoload.php';

    class Message extends \Mailer
    {
        public function __construct(
            $email_from, $name_from, $to_address, $to_name, $subject, $template, $params = array()
        )
        {
            parent::__construct();

            $this->mail->setFrom($email_from, $name_from);
            $this->mail->addAddress($to_address, $to_name);

            $html = file_get_contents("__system__/functions/phpmailer/templates/{$template}.php");

            foreach ($params as $k => $v) {
                $html = str_replace($k, $v, $html);
            }

            // Content
            $this->mail->isHTML(true);
            
            $this->mail->Subject = $subject;
            $this->mail->Body = $html;
            $this->mail->AltBody = $subject;
        }
    }
