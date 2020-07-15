<?php
date_default_timezone_set('America/Sao_Paulo');
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
function env_email_rec($email, $name, $new_password) {
    $hour = Date("H");
    if(($hour > 05) && ($hour < 12)) {
        $cump = "Bom dia";
    } elseif(($hour > 12) && ($hour < 18)) {
        $cump = "Boa tarde";
    } else {
        $cump = "Boa noite";
    }

	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = 0;                                       // Enable verbose debug output
		$mail->isSMTP();                                            // Set mailer to use SMTP
		$mail->Host       = 'host.sdserver18.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = 'accounts@economize.top';                     // SMTP username
		$mail->Password   = 'M7QrPaongDxu';                               // SMTP password
		$mail->SMTPSecure = "ssl";                                  // Enable TLS encryption, `ssl` also accepted
		$mail->Port       = 465;                                    // TCP port to connect to
		$mail->setLanguage('pt-br', '/optional/path/to/language/directory/');
		$mail->CharSet = 'UTF-8';
		//Recipients
		$mail->setFrom('accounts@economize.top', 'e.conomize');
		$mail->addAddress($email, $name);     // Add a recipient

		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Recupere uma nova senha';
        $mail->Body = '
            <!DOCTYPE html>
            <head>
                <meta charset="utf-8"/>
                <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
                <title></title>
                <meta name="viewport" content="width=device-width, initial-scale=1"/>
            </head>
            <body>
                <h3>' . $cump . ', ' . $name . '!</h3>
                <p>Aqui está sua nova senha: ' . $new_password . '</p>
                <p>Fique à vontade para mudá-la quando quiser em nosso sistema</p>
                <img src="www.economize.top/__system__/style/img/banner/logo_economize.png"/>
            </body>
            </html>
        ';
		$mail->AltBody = 'e.conomize';
	
		$mail->send();
		
	} catch (Exception $e) {
		//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
 }
?>
