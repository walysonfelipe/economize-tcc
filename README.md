<p align="center">
     <img  src="https://github.com/walysonfelipe/economize-tcc/blob/master/__system__/style/img/banner/logo_economize.png?raw=true" align="center" width="300px">
</p>                 

## 📕 Índice
- [Introdução](#-introdução)
- [Tecnologia Utilizadas](#-tecnologia-utilizadas)
- [Testar Aplicação](#-testar-aplicação)

## 🚀 Introdução
A migração da prestação de serviços convencionais para o mundo digital já é uma realidade, que estabeleceu novas dinâmicas no mundo das comunicações e do comércio global. Este projeto, de maneira a acompanhar essas novas demandas, tem como objetivo proporcionar aos consumidores uma maneira moderna, barata e prática de se fazer compras do tipo usual. Consiste em um mercado com presença 100% digital voltado a atender as necessidades diárias das pessoas que buscam na tecnologia meios de facilitar processos já ultrapassados e que desperdiçãm tempo e dinheiro. É fundamentado sobre conceitos de marketing, mercadológicos e técnicos, otimizados para o segmento de comércio de varejo eletrônico de pequena e/ou grande escala. Junto com algumas outras poucas empresas, faz parte de uma tendência pioneira na Internet, que oferece serviços inéditos, como a venda de produtos pereciveis e congelados por meio digital, facilidades premium como o agendamento de delivery, entre diversos outros beneficios.


## 👨‍💻 Tecnologia Utilizadas


<table>
  <tr>
    <th><img height="20" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/php/php.png"></th>
    <th><img height="20" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/mysql/mysql.png"></th>
    <th><img height="20" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/javascript/javascript.png"></th>
    <th><img height="20" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/html/html.png"></th>
    <th><img height="20" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/css/css.png"></th>
 </tr>
</table>


## 🚀 Testar Aplicação

> **Requisito**: Você vai precisar do **PHP7** e **XAMPP** (ou WAMPP)

> **Diretorio:** economize-tcc/__system__/functions/php-class/Sql.php/

```bash
 # Importar banco de dados:  economize-tcc/__system__/functions/database/economize.sql
 # Adicionar suas credenciais do banco de dados 
    class Sql
    {
    const HOSTNAME = "HOSTNAME";
		const USERNAME = "NOME DO USUARIO";
		const PASSWORD = "SENHA DO USUARIO";
		const DBNAME = "NOME DO BANCO";
```


> **Diretorio:** economize-tcc/__system__/functions/php-class/Mailer.php/

```bash
   # Adicionar suas credenciais do email
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
```
  

> **Diretorio:**economize-tcc/__system__/functions/pagseguro/configuration.php/

```bash
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
```
  
 Criado em 2018/2019,  atualiazado em 2020.
Criado por [Walyson Felipe](https://github.com/walysonfelipe),  [Pedro Todorovski](https://github.com/PedroTodorovski), [Nicolas Carvalho ](https://github.com/nickcarva) e [Vitor Hugo]() 🚀.
 
