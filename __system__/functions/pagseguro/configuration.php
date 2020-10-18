<?php
    $sandbox = true; // DEFININDO SE ESTÁ EM PRODUÇÃO OU EM TESTE

 # Adicionar suas credenciais do meio de pagamento PAGSEGURO
    if ($sandbox) { // EM FASE DE TESTE
        define("EMAIL_PAGSEGURO", "EMAIL");
        define("TOKEN_PAGSEGURO", "TOKEN");
        define("URL_PAGSEGURO", "https://ws.sandbox.pagseguro.uol.com.br/v2/");
        define("SCRIPT_PAGSEGURO", "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js");

        define("EMAIL_LOJA", "teste@siteteste.com.br");
        define("MOEDA_PAGAMENTO", "BRL");
        define("URL_NOTIFICATION", Project::baseUrlPhp() . "compra/extrato");
    } else { // EM PRODUÇÃO
        define("EMAIL_PAGSEGURO", "teste@siteteste.com.br");
        define("TOKEN_PAGSEGURO", "TOKEN");
        define("URL_PAGSEGURO", "https://ws.pagseguro.uol.com.br/v2/");
        define("SCRIPT_PAGSEGURO", "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js");
        
        define("EMAIL_LOJA", "teste@siteteste.com.br");
        define("MOEDA_PAGAMENTO", "BRL");
        define("URL_NOTIFICATION", Project::baseUrlPhp() . "compra/extrato");
    }
