<?php
    use \Model\{User, Storage, Cart};

    namespace Model;
    
    class Product extends \Model
    {
        public static function listAll($id = false)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT " . (($id) ? "produto_id" : "*") . " FROM produto");
            return $results;
        }

        public static function listSimplePromotionalProducts()
        {
            // PRODUTOS COM PROMOÇÕES COMUNS

            $products = [];
            $sql = new \Sql();

            $results = $sql->select("SELECT p.produto_id, p.produto_nome, d.produto_qtd, p.produto_img, p.produto_tamanho, d.produto_preco, d.produto_desconto_porcent FROM produto AS p JOIN dados_armazem AS d ON p.produto_id=d.produto_id WHERE d.produto_desconto_porcent IS NOT NULL AND d.produto_desconto_porcent <> '' AND d.armazem_id = :arm_id", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            if (count($results) > 0) {
                foreach ($results as $row) {
                    if ($row['produto_qtd'] > 0) {
                        $row['empty'] = false;
                    } else {
                        $row['empty'] = true;
                    }

                    $row["produto_desconto"] = \Project::promotionCalculation($row["produto_desconto_porcent"], $row["produto_preco"]);
                    $row["produto_preco"] = \Project::formatPriceToReal($row["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$row['produto_id']])) {
                        $row["carrinho"] = $_SESSION[Cart::SESSION][$row['produto_id']];
                    } else {
                        $row["carrinho"] = 0;
                    }

                    array_push($products, $row);
                }
            }

            return $products;
        }

        public static function listCustomPromotionalProducts()
        {
            // PRODUTOS COM PROMOÇÕES PERSONALIZADAS
            $products = [];
            $sql = new \Sql();

            $results = $sql->select("SELECT p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_preco FROM produto AS p JOIN dados_promocao AS dp ON p.produto_id=dp.produto_id JOIN promocao_temp AS pr ON pr.promo_id=dp.promo_id JOIN dados_armazem AS d ON dp.produto_id=d.produto_id WHERE pr.promo_status = 1 AND d.armazem_id = :arm_id ORDER BY pr.promo_id", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            if (count($results) > 0) {
                foreach ($results as $row) {
                    if ($row['produto_qtd'] > 0) {
                        $row['empty'] = false;
                    } else {
                        $row['empty'] = true;
                    }

                    $row["produto_desconto"] = \Project::promotionCalculation($row["promo_desconto"], $row["produto_preco"]);
                    $row["produto_preco"] = \Project::formatPriceToReal($row["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$row['produto_id']])) {
                        $row["carrinho"] = $_SESSION[Cart::SESSION][$row['produto_id']];
                    } else {
                        $row["carrinho"] = 0;
                    }
                    if ($row['promo_expira'] != '') {
                        $row['promo_expira'] = \Project::formatRegister($row['promo_expira']);
                    }

                    array_push($products, $row);
                }
            }

            return $products;
        }

        public static function getProductByIdAndArm($id, $crypt = true)
        {
            $sql = new \Sql();
            $product = "";

            $results = $sql->select("SELECT c.categ_nome, s.subcateg_nome, d.depart_nome, p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, da.produto_qtd, da.produto_desconto_porcent, da.produto_preco, m.marca_nome, pr.promo_desconto FROM produto p JOIN dados_armazem da ON da.produto_id = p.produto_id JOIN categ c ON c.categ_id = p.produto_categ JOIN subcateg s ON c.subcateg_id = s.subcateg_id JOIN departamento d ON s.depart_id = d.depart_id JOIN marca_prod m ON p.produto_marca = m.marca_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE da.armazem_id = :arm_id AND " . (($crypt) ? "md5(p.produto_id)" : "p.produto_id") . " = :prod_id", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                ":prod_id" => $id
            ]);
            
            if (count($results) > 0) {
                $v = $results[0];
                if ($v['produto_qtd'] > 0) {
                    $v['empty'] = false;
                } else {
                    $v['empty'] = true;
                }

                if ($v['produto_desconto_porcent'] <> "") {
                    $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                } elseif ($v['promo_desconto']) {
                    $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                }
                
                $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                    $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                } else {
                    $v["carrinho"] = 0;
                }
                
                $product = $v;
            }

            return $product;
        }

        public static function getProductsByDepartAndArm($depart)
        {
            $sql = new \Sql();
            $products = [];

            $results = $sql->select("SELECT p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE s.depart_id = :depart AND d.armazem_id = :arm_id", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                ":depart" => $depart
            ]);
            
            if (count($results) > 0) {
                foreach ($results as $v) {
                    if ($v['produto_qtd'] > 0) {
                        $v['empty'] = false;
                    } else {
                        $v['empty'] = true;
                    }

                    if ($v['produto_desconto_porcent'] <> "") {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                    } elseif ($v['promo_desconto']) {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                    }
                    
                    $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                        $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                    } else {
                        $v["carrinho"] = 0;
                    }
                    
                    array_push($products, $v);
                }
            }

            return $products;
        }

        public static function getProductsByIds($ids): array
        {
            $sql = new \Sql();
            $dados = [];

            $results = $sql->select("SELECT p.produto_id, p.produto_nome, d.produto_qtd, p.produto_img, p.produto_tamanho, d.produto_preco, d.produto_desconto_porcent, m.marca_nome, pr.promo_desconto FROM produto p JOIN dados_armazem d ON p.produto_id = d.produto_id JOIN marca_prod m ON p.produto_marca = m.marca_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE d.armazem_id = :arm_id AND p.produto_id IN ({$ids})", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            
            if (count($results) > 0) {
                foreach ($results as $v) {
                    array_push($dados, $v);
                }
            }

            return $dados;
        }

        public static function getProductsBySearch($search): array
        {
            $sql = new \Sql();
            $dados = [];

            $results = $sql->select("SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto p JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE d.armazem_id = :arm_id AND (MATCH (p.produto_nome, p.produto_descricao, p.produto_tamanho) AGAINST (:match) OR md5(p.produto_id) = :id OR p.produto_id = :id)", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                ":match" => "%{$search}%",
                ":id" => $search
            ]);

            foreach ($results as $v) {
                if ($v['produto_desconto_porcent'] <> "") {
                    $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                } elseif ($v['promo_desconto']) {
                    $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                }
                
                $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                array_push($dados, $v);
            }

            return $dados;
        }

        public static function getFavoriteProductsByArm()
        {
            $products = [];
            $sql = new \Sql();

            if (User::checkLogin()) {
                $results = $sql->select("SELECT p.produto_id, p.produto_img, p.produto_descricao, p.produto_nome, p.produto_tamanho, d.produto_qtd, d.produto_preco, d.produto_desconto_porcent, pr.promo_desconto FROM produto p JOIN produtos_favorito pf ON p.produto_id = pf.produto_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE d.armazem_id = :arm_id AND pf.usu_id = :usu_id", [
                    ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                    ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                ]);

                if (count($results) > 0) {
                    foreach ($results as $k => $v) {
                        if ($v['produto_qtd'] > 0) {
                            $v['empty'] = false;
                        } else {
                            $v['empty'] = true;
                        }
        
                        if ($v['produto_desconto_porcent'] <> "") {
                            $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                        } elseif ($v['promo_desconto']) {
                            $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                        }
                        
                        $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);
        
                        if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                            $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                        } else {
                            $v["carrinho"] = 0;
                        }

                        array_push($products, $v);
                    }
                }

                return $products;
            } else {
                return false;
            }
        }

        public static function getQuantityInArm($produto_id)
        {
            $sql = new \Sql();

            $results = $sql->select("SELECT produto_qtd FROM dados_armazem WHERE produto_id = :prod_id AND armazem_id = :arm_id", [
                ":prod_id" => $produto_id,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);

            if (count($results) > 0) {
                return $results[0];
            } else {
                return 0;
            }
        }

        public static function getFavoriteProducts()
        {
            $sql = new \Sql();
            $products = [];

            if (User::checkLogin()) {
                $results = $sql->select("SELECT produto_id FROM produtos_favorito WHERE usu_id = :usu_id", [
                    ":usu_id" => $_SESSION[User::SESSION]['usu_id']
                ]);
                
                if (count($results) > 0) {
                    foreach ($results as $v) {
                        array_push($products, $v['produto_id']);
                    }

                    return $products;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public static function addFavorite($produto_id)
        {
            $sql = new \Sql();

            $data['logado'] = true;
            $data['error'] = null;
    
            if (User::checkLogin()) {
                if (Product::checkFavorite($produto_id, $_SESSION[User::SESSION]["usu_id"])) {
                    $data['error'] = 'Este produto já está nos favoritos';
                } else {
                    $stmt = $sql->query("INSERT INTO produtos_favorito(produto_id, usu_id) VALUES(:p, :u)", [
                        ":p" => $produto_id,
                        ":u" => $_SESSION[User::SESSION]["usu_id"]
                    ]);
                    if (!$stmt) {
                        $data['error'] = "Erro inesperado: " . $ins->errorInfo();
                    } else {
                        $data['produto_id'] = $produto_id;
                    }
                }
            } else {
                $data['logado'] = false;
                $data['error'] = 'Você precisa estar logado';
            }

            return $data;
        }

        public static function removeFavorite($produto_id)
        {
            $sql = new \Sql();
            $data['error'] = null;
            
            if (User::checkLogin()) {
                if (Product::checkFavorite($produto_id, $_SESSION[User::SESSION]["usu_id"])) {
                    $stmt = $sql->query("DELETE FROM produtos_favorito WHERE produto_id = :p AND usu_id = :u", [
                        ":p" => $produto_id,
                        ":u" => $_SESSION[User::SESSION]["usu_id"]
                    ]);
                    
                    if ($stmt === true) {
                        $data['success'] = "Deletado com sucesso";
                        $data['produto_id'] = $produto_id;
                    } else {
                        $data['error'] = "Erro inesperado";
                    }
                } else {
                    $data['error'] = "Erro inesperado";
                }
            } else {
                $data['error'] = "Erro inesperado";
            }

            return $data;
        }

        public static function checkFavorite($produto_id, $usu_id): bool
        {
            $sql = new \Sql();

            $results = $sql->select("SELECT favorito_id FROM produtos_favorito WHERE produto_id = :prod_id AND usu_id = :usu_id", [
                ":prod_id" => (int)$produto_id,
                ":usu_id" => (int)$usu_id
            ]);
            
            
            if (count($results) > 0) return true;
            else return false;
        }

        public static function getPageDepart($depart, $page = 1, $itemsPerPage = 12)
        {
            $start = ($page - 1) * $itemsPerPage;
            $products = [];

            $sql = new \Sql();
            
            $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE s.depart_id = :depart AND d.armazem_id = :arm_id ORDER BY p.produto_nome LIMIT {$start}, {$itemsPerPage}", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                ":depart" => $depart
            ]);

            $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

            if (count($results) > 0) {
                foreach ($results as $v) {
                    if ($v['produto_qtd'] > 0) {
                        $v['empty'] = false;
                    } else {
                        $v['empty'] = true;
                    }

                    if ($v['produto_desconto_porcent'] <> "") {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                    } elseif ($v['promo_desconto']) {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                    }
                    
                    $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                        $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                    } else {
                        $v["carrinho"] = 0;
                    }
                    
                    array_push($products, $v);
                }
            }

            return [
                "data" => $products,
                "total" => (int)$resultsTotal[0]["nrtotal"],
                "pages" => ceil($resultsTotal[0]["nrtotal"] / $itemsPerPage)
            ];
        }

        public static function getPageSubcateg($subcateg, $page = 1, $itemsPerPage = 12)
        {
            $start = ($page - 1) * $itemsPerPage;
            $products = [];

            $sql = new \Sql();
            
            $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE s.subcateg_id = :subcateg AND d.armazem_id = :arm_id ORDER BY p.produto_nome LIMIT {$start}, {$itemsPerPage}", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                ":subcateg" => $subcateg
            ]);

            $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

            if (count($results) > 0) {
                foreach ($results as $v) {
                    if ($v['produto_qtd'] > 0) {
                        $v['empty'] = false;
                    } else {
                        $v['empty'] = true;
                    }

                    if ($v['produto_desconto_porcent'] <> "") {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                    } elseif ($v['promo_desconto']) {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                    }
                    
                    $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                        $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                    } else {
                        $v["carrinho"] = 0;
                    }
                    
                    array_push($products, $v);
                }
            }

            return [
                "data" => $products,
                "total" => (int)$resultsTotal[0]["nrtotal"],
                "pages" => ceil($resultsTotal[0]["nrtotal"] / $itemsPerPage)
            ];
        }

        public static function getPageCateg($categ, $page = 1, $itemsPerPage = 12)
        {
            $start = ($page - 1) * $itemsPerPage;
            $products = [];

            $sql = new \Sql();
            
            $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS p.produto_id, p.produto_nome, p.produto_img, p.produto_tamanho, pr.promo_id, pr.promo_desconto, pr.promo_nome, pr.promo_subtit, pr.promo_expira, d.produto_qtd, d.produto_desconto_porcent, d.produto_preco FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id LEFT JOIN dados_promocao dp ON p.produto_id = dp.produto_id LEFT JOIN promocao_temp pr ON dp.promo_id = pr.promo_id WHERE c.categ_id = :categ AND d.armazem_id = :arm_id ORDER BY p.produto_nome LIMIT {$start}, {$itemsPerPage}", [
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id'],
                ":categ" => $categ
            ]);

            $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

            if (count($results) > 0) {
                foreach ($results as $v) {
                    if ($v['produto_qtd'] > 0) {
                        $v['empty'] = false;
                    } else {
                        $v['empty'] = true;
                    }

                    if ($v['produto_desconto_porcent'] <> "") {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                    } elseif ($v['promo_desconto']) {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                    }
                    
                    $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                        $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                    } else {
                        $v["carrinho"] = 0;
                    }
                    
                    array_push($products, $v);
                }
            }

            return [
                "data" => $products,
                "total" => (int)$resultsTotal[0]["nrtotal"],
                "pages" => ceil($resultsTotal[0]["nrtotal"] / $itemsPerPage)
            ];
        }

        public static function searchDepartment($query, $page = 1, $itemsPerPage = 12)
        {
            $start = ($page - 1) * $itemsPerPage;
            $products = [];

            $sql = new \Sql();
            
            $results = $sql->select($query . "LIMIT {$start}, {$itemsPerPage}");

            $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

            if (count($results) > 0) {
                foreach ($results as $v) {
                    if ($v['produto_qtd'] > 0) {
                        $v['empty'] = false;
                    } else {
                        $v['empty'] = true;
                    }

                    if ($v['produto_desconto_porcent'] <> "") {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["produto_desconto_porcent"], $v["produto_preco"]);
                    } elseif ($v['promo_desconto']) {
                        $v["produto_desconto"] = \Project::promotionCalculation($v["promo_desconto"], $v["produto_preco"]);
                    }
                    
                    $v["produto_preco"] = \Project::formatPriceToReal($v["produto_preco"]);

                    if (isset($_SESSION[Cart::SESSION][$v['produto_id']])) {
                        $v["carrinho"] = $_SESSION[Cart::SESSION][$v['produto_id']];
                    } else {
                        $v["carrinho"] = 0;
                    }
                    
                    array_push($products, $v);
                }
            }

            $data = [
                "data" => $products,
                "total" => (int)$resultsTotal[0]["nrtotal"],
                "pages" => ceil($resultsTotal[0]["nrtotal"] / $itemsPerPage)
            ];

            $pages = [];
            
            for ($x = 0; $x < $data['pages']; $x++) {
                array_push($pages, ($x + 1));
            }

            $data['pagesUrl'] = $pages;

            return $data;
        }
    }
