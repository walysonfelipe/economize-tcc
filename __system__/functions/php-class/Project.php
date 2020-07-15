<?php
    // Nesta classe vai as funções gerais do projeto
    class Project
    {
        const SECRET_IV = "SecretEconomize+";
        const SECRET = "SecretEconomize+";

        public static function systemVersion()
        {
            $version = "VERSÃO 2.0.0";
            
            return $version;
        }

        public static function footerInf()
        {
            $inf = '<i class="far fa-copyright"></i> 2019. Todos os Direitos Reservados. Software desenvolvido por UrbanCode.';

            return $inf;
        }

        public static function validarCPF($cpf = null)
        {
            if(empty($cpf)) return false;
            
            $cpf = preg_replace("/[^0-9]/", "", $cpf);
            $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
            
            if (strlen($cpf) != 11) {
                return false;
            } elseif (
                $cpf == '00000000000' || 
                $cpf == '11111111111' || 
                $cpf == '22222222222' || 
                $cpf == '33333333333' || 
                $cpf == '44444444444' || 
                $cpf == '55555555555' || 
                $cpf == '66666666666' || 
                $cpf == '77777777777' || 
                $cpf == '88888888888' || 
                $cpf == '99999999999') {
                return false;
            } else {
                for ($t = 9; $t < 11; $t++) {
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf[$c] != $d) {
                        return false;
                    }
                }

                return true;
            }
        }

        public static function isXmlHttpRequest()
        {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
            return (strtolower($isAjax) === 'xmlhttprequest');
        }

        public static function baseUrl()
        {
            return "http://localhost/economize/__system__/";
        }

        public static function baseUrlPhp()
        {
            return "http://localhost/economize/";
        }

        public static function baseUrlAdm() {
            return "http://localhost/economize/__system__/admin-area/";
        }
    
        public static function baseUrlAdmPhp() {
            return "http://localhost/economize/admin-area/";
        }

        public static function descobrirIdade($datanasc = null)
        {
            $dia = Date('d');
            $mes = Date('m');
            $ano = Date('Y');

            $nasc = explode("-", $datanasc);
            $idade = $ano - $nasc[0];

            if($mes < $nasc[1]) $idade--;
            elseif(($mes == $nasc[1]) && ($dia <= $nasc[2])) $idade--;

            return $idade;
        }

        public static function arrayReplaceKey(&$array, $old, $new, $overwrite = true): bool {
            foreach ($array as &$k) {
                if (isset($k[$new]) and !$overwrite) {
                    return false;
                }
            
                $k[$new] = $k[$old];
                unset($k[$old]);
            }

            return true;
        }

        public static function formatRegister($date = null)
        {
            $format = Date("d/m/Y H:i:s", strtotime($date));

            return $format;
        }

        public static function formatDate($date = null)
        {
            $format = Date("d/m/Y", strtotime($date));

            return $format;
        }

        public static function formatDateToSql($date = null)
        {
            $format = Date("Y-m-d", strtotime($date));

            return $format;
        }

        public static function formatGenero($gen = null)
        {
            $gen = ($gen === "M") ? "Masculino" : "Feminino";

            return $gen;
        }

        public static function formatPriceToReal($price = null)
        {
            $price = number_format($price, 2, ",", ".");

            return $price;
        }

        public static function formatPriceToDolar($price = null)
        {
            $price = number_format($price, 2, ".", "");

            return $price;
        }
        
        public static function formatPriceToSqlFormat($price = null)
        {
            $price = str_replace(",", ".", str_replace(".", "", $price));

            return $price;
        }

        public static function PromotionCalculation($discount = null, $price = null, $real = true)
        {
            $priceDiscount = $price * ($discount / 100);

            $priceDiscount = Project::formatPriceToDolar($priceDiscount);
            $priceDiscount = $price - $priceDiscount;

            if ($real) return Project::formatPriceToReal($priceDiscount);
            else return Project::formatPriceToDolar($priceDiscount);
        }

        public static function formatFirstName($fname = null)
        {
            $fname = trim($fname);
            $exp = explode(" ", $fname);
            $fname = "";

            foreach($exp as $v) {
                $v = str_replace(" ", "", $v);
                $v = ucfirst(mb_strtolower($v));

                if($v != "") $fname .= $v . " ";
            }

            return trim($fname);
        }

        public static function formatLastName($lname = null)
        {
            $lname = trim($lname);
            $exp = explode(" ", $lname);
            $lname = "";

            foreach($exp as $v) {
                $v = str_replace(" ", "", $v);
                $v = mb_strtolower($v);
                
                if(($v != "do") && ($v != "dos") && ($v != "das") && ($v != "da") && ($v != "de")) {
                    $v = ucfirst($v);
                }

                if($v != "") $lname .= $v . " ";
            }

            return trim($lname);
        }

        public static function setToUtf8($data = array())
        {
            foreach ($data as $key => $value) {
                foreach ($data[$key] as $k => &$v) {
                    $v = utf8_encode($v);
                }
            }

            return $data;
        }

        public static function opensslCrypt($string, $encrypt = true)
        {
            if ($encrypt) {
                return base64_encode(openssl_encrypt(
                    $string,
                    'AES-128-CBC',
                    Project::SECRET,
                    0,
                    Project::SECRET_IV
                ));
            } else {
                return openssl_decrypt(
                    base64_decode($string),
                    'AES-128-CBC',
                    Project::SECRET,
                    0,
                    Project::SECRET_IV
                );
            }
        }

        public static function passwordGenerator(
            int $tamanho = 1, bool $maiusculas = true, bool $minusculas = true, 
            bool $numeros = true, bool $simbolos = true
        )
        {
            $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
            $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
            $nu = "0123456789"; // $nu contem os números
            $si = "!@#$&*_+="; // $si contem os símbolos
            $senha = "";
           
            if ($maiusculas) {
                // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
                $senha .= str_shuffle($ma);
            }
           
            if ($minusculas) {
                // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
                $senha .= str_shuffle($mi);
            }
        
            if ($numeros) {
                // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
                $senha .= str_shuffle($nu);
            }
        
            if ($simbolos) {
                // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
                $senha .= str_shuffle($si);
            }
        
            // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
            return substr(str_shuffle($senha), 0, $tamanho);
        }

        public static function hashGenerator()
        {
            $sql = new Sql();

            $c = 0;
            while ($c == 0) {
                $hash = rand(001, 999);
                if (strlen($hash) < 3) {
                    if (strlen($hash) == 2) $hash = "0" . $hash;
                    else $hash = "00" . $hash;
                }
                $hash = Date("y") . $hash;
                
                $results = $sql->select("SELECT hash_atend FROM jovem_espera WHERE hash_atend = :h", [
                    ":h" => $hash
                ]);
                
                if (count($results) === 0) $c = 1;
            }

            return $hash;
        }

        public static function hashPasswordGenerator($pass)
        {
            return password_hash($pass, PASSWORD_DEFAULT);
        }

        public static function removeAccent($string = null) {
            if(strpos($string," "))
                str_replace(" ", "-", $string);
            $string = strtolower($string);

            return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a a e e i i o o u u n n c c"),$string);
        }

        public static function dayOfWeek($day = 1)
        {
            switch ($day) {
                case 1:
                    $dia = "SEGUNDA";
                break;
                case 2:
                    $dia = "TERÇA";
                break;
                case 3:
                    $dia = "QUARTA";
                break;
                case 4:
                    $dia = "QUINTA";
                break;
                case 5:
                    $dia = "SEXTA";
                break;
                case 6:
                    $dia = "SÁBADO";
                break;
                case 7:
                    $dia = "DOMINGO";
                break;
                default:
                    $dia = "INVÁLIDO";
            }

            return $dia;
        }

        public static function resizeImage($resourceType, $imageWidth, $imageHeight) {
            $resizeWidth = 50;
            $resizeHeight = 50;

            $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
            imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $imageWidth, $imageHeight);
            return $imageLayer;
        }
    }
