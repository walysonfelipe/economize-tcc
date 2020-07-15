<?php
    namespace Model;
    
    class Storage extends \Model
    {
        const SESSION = "EconomizeStorageSession";
        const COOKIE = "EconomizeStorageCookie";

        public static function getFromCookie()
        {
            $sql = new \Sql();

            if (isset($_COOKIE[Storage::COOKIE]) && !isset($_SESSION[Storage::SESSION]['arm_id'])) {
                $results = Storage::getStorageById($_COOKIE[Storage::COOKIE]);

                if (count($results) > 0) {
                    $v = $results[0];
                    Storage::setStorage($v);
                }
            } else {
                if (!isset($_SESSION[Storage::SESSION]['arm_id'])) {
                    $results = $sql->select("SELECT c.cid_nome, e.est_uf, a.armazem_nome, a.armazem_id FROM armazem a JOIN cidade c ON a.cidade_id = c.cid_id JOIN estado e ON c.est_id = e.est_id LIMIT 1");

                    if (count($results) > 0) {
                        $v = $results[0];
                        Storage::setStorage($v);
                    } else {
                        echo "Não há armazém para realizar suas compras. Tente novamente mais tarde, por favor.";
                        exit;
                    }
                }
            }
        }

        public static function createCookie($arm_id)
        {
            Storage::destroyCookie();
            setcookie(Storage::COOKIE, $arm_id, time() + (86400 * 1825), Storage::COOKIE_PATH);
        }

        public static function destroyCookie()
        {
            if (isset($_COOKIE[Storage::COOKIE])) {
                setcookie(Storage::COOKIE, "", 0, Storage::COOKIE_PATH);
            }
        }

        public static function changeStorage($arm_id)
        {
            $data = [];
            $data['status'] = 1;

            $results = Storage::getStorageById($arm_id);
  
            if (count($results) > 0) {
                $v = $results[0];
                Storage::setStorage($v);
            } else {
                $data['status'] = 0;
            }

            return $data;
        }

        public static function setStorage($data = [])
        {
            $_SESSION[Storage::SESSION]['arm'] = $data['cid_nome'] . " - " . $data['est_uf'];
            $exp = explode(" ",$data['cid_nome']);
                    
            if (count($exp) > 1) {
                $qtd = strlen($data['cid_nome']) - (strlen($exp[0]) + 1);
                $_SESSION[Storage::SESSION]['arm_cm'] = substr($data['cid_nome'], 0, 1) . " " . substr($data['cid_nome'], -$qtd) . " - " . $data['est_uf'];
            } else {
                if (isset($_SESSION[Storage::SESSION]['arm_cm'])) {
                    unset($_SESSION[Storage::SESSION]['arm_cm']);
                }
            }

            $_SESSION[Storage::SESSION]['arm_nome'] = $data['armazem_nome'];
            $_SESSION[Storage::SESSION]['arm_id'] = $data['armazem_id'];
                
            Cart::clearCartToChangeArm();
            Storage::createCookie($_SESSION[Storage::SESSION]['arm_id']);
        }

        public static function getStorageById($arm_id)
        {
            $sql = new \Sql();

            $results = $sql->select("SELECT c.cid_nome, e.est_uf, a.armazem_nome, a.armazem_id FROM armazem a JOIN cidade c ON a.cidade_id = c.cid_id JOIN estado e ON c.est_id = e.est_id WHERE a.armazem_id = :arm_id", [
                ":arm_id" => $arm_id
            ]);

            return $results;
        }

        public static function getStorages()
        {
            $sql = new \Sql();

            $results = $sql->select("SELECT c.cid_nome, e.est_uf, a.armazem_nome, a.armazem_id FROM armazem a, cidade c, estado e WHERE a.cidade_id = c.cid_id AND c.est_id = e.est_id");

            return $results;
        }

        public static function listStoragesInModal()
        {
            $storages = null;

            $results = Storage::getStorages();

            foreach ($results as $k => $row) {
                if ($row['armazem_id'] == $_SESSION[Storage::SESSION]['arm_id']) {
                    $row['meuArm'] = true;
                } else {
                    $row['meuArm'] = false;
                }

                $storages[$k] = $row;
            }

            return $storages;
        }
    }
