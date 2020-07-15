<?php
    use Mailer\Error;
    
    ob_start();
	session_start();
	
	date_default_timezone_set("America/Recife");
    setlocale(LC_ALL, 'pt_BR');
    error_reporting(E_ALL);

    function errorHandler ($code, $message, $file, $line) {
        $json = [
            "code" => $code,
            "message" => $message,
            "line" => $line,
            "file" => $file
        ];
        
        echo json_encode($json) . "\r\n";

        // $mailer = new Error($json);
		// $mailer->send();
    }

    set_error_handler("errorHandler");

    spl_autoload_register(function($nameClass) {
        $nameClass = str_replace("\\", DIRECTORY_SEPARATOR, $nameClass);
        $dirClass = "__system__" . DIRECTORY_SEPARATOR . "functions" . DIRECTORY_SEPARATOR . "php-class";
        $filepath = $dirClass . DIRECTORY_SEPARATOR . $nameClass . ".php";

        if (file_exists($filepath)) {
            require_once "{$filepath}";
        }
    });
