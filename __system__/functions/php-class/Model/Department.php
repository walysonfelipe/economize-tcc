<?php
    use Model\Storage;

    namespace Model;
    
    class Department extends \Model
    {
        const SESSION = "EconomizeDepartmentSession";

        public static function listAll()
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT * FROM departamento");
            return $results;
        }

        public static function getFromUrl($url)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT * FROM departamento WHERE depart_nome = :url", [
                ":url" => $url
            ]);
            return $results;
        }

        public static function getFromUrlAndSub($depart, $subcateg)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT * FROM subcateg WHERE subcateg_url = :subcateg AND depart_id = :depart", [
                ":depart" => $depart,
                ":subcateg" => $subcateg
            ]);
            return $results;
        }

        public static function getFromCategAndSub($subcateg, $categ)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT * FROM categ WHERE categ_url = :categ AND subcateg_id = :subcateg", [
                ":categ" => $categ,
                ":subcateg" => $subcateg
            ]);
            return $results;
        }

        public static function getAllSubcategByDepart($depart)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT * FROM subcateg WHERE depart_id = :depart ORDER BY subcateg_nome", [
                ":depart" => $depart
            ]);
            return $results;
        }

        public static function getDistinctSubcategProductTamByDepart($depart)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT DISTINCT(p.produto_tamanho) tam FROM produto p JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id WHERE s.depart_id = :depart AND d.armazem_id = :arm_id", [
                ":depart" => $depart,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            return $results;
        }

        public static function getDistinctSubcategProductMarcaByDepart($depart)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN subcateg s ON s.subcateg_id = c.subcateg_id JOIN dados_armazem d ON p.produto_id = d.produto_id WHERE s.depart_id = :depart AND d.armazem_id = :arm_id", [
                ":depart" => $depart,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            return $results;
        }

        public static function getAllSubcategBySubcateg($subcateg)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT * FROM categ WHERE subcateg_id = :subcateg ORDER BY categ_nome", [
                ":subcateg" => $subcateg
            ]);
            return $results;
        }

        public static function getDistinctSubcategProductTamBySubcateg($subcateg)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto p JOIN categ c ON p.produto_categ = c.categ_id JOIN dados_armazem d ON p.produto_id = d.produto_id WHERE c.subcateg_id = :subcateg AND d.armazem_id = :arm_id", [
                ":subcateg" => $subcateg,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            return $results;
        }

        public static function getDistinctSubcategProductMarcaBySubcateg($subcateg)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN categ c ON p.produto_categ = c.categ_id JOIN dados_armazem d ON p.produto_id = d.produto_id WHERE c.subcateg_id = :subcateg AND d.armazem_id = :arm_id", [
                ":subcateg" => $subcateg,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            return $results;
        }

        public static function getDistinctSubcategProductTamByCateg($categ)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT DISTINCT(p.produto_tamanho) AS tam FROM produto p JOIN dados_armazem d ON p.produto_id = d.produto_id WHERE p.produto_categ = :categ AND d.armazem_id = :arm_id", [
                ":categ" => $categ,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            return $results;
        }

        public static function getDistinctSubcategProductMarcaByCateg($categ)
        {
            $sql = new \Sql();
            $results = $sql->select("SELECT DISTINCT(p.produto_marca), m.marca_nome FROM produto p JOIN marca_prod m ON p.produto_marca = m.marca_id JOIN dados_armazem d ON p.produto_id = d.produto_id WHERE p.produto_categ = :categ AND d.armazem_id = :arm_id", [
                ":categ" => $categ,
                ":arm_id" => $_SESSION[Storage::SESSION]['arm_id']
            ]);
            return $results;
        }
    }
