<?php
    $sandbox = true; // DEFININDO SE ESTÁ EM PRODUÇÃO OU EM TESTE

    if ($sandbox) { // EM FASE DE TESTE
        define("EMAIL_PAGSEGURO", "aneru.contato@gmail.com");
        define("TOKEN_PAGSEGURO", "9E40B586074F402CA860948FF7020DD6");
        define("URL_PAGSEGURO", "https://ws.sandbox.pagseguro.uol.com.br/v2/");
        define("SCRIPT_PAGSEGURO", "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js");

        define("EMAIL_LOJA", "aneru.contato@gmail.com");
        define("MOEDA_PAGAMENTO", "BRL");
        define("URL_NOTIFICATION", Project::baseUrlPhp() . "compra/extrato");
    } else { // EM PRODUÇÃO
        define("EMAIL_PAGSEGURO", "aneru.contato@gmail.com");
        define("TOKEN_PAGSEGURO", "9E40B586074F402CA860948FF7020DD6");
        define("URL_PAGSEGURO", "https://ws.pagseguro.uol.com.br/v2/");
        define("SCRIPT_PAGSEGURO", "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js");
        
        define("EMAIL_LOJA", "aneru.contato@gmail.com");
        define("MOEDA_PAGAMENTO", "BRL");
        define("URL_NOTIFICATION", Project::baseUrlPhp() . "compra/extrato");
    }
