<?php
	use Mailer\Error;

    class Sql
    {
        const HOSTNAME = "localhost";
		const USERNAME = "root";
		const PASSWORD = "";
		const DBNAME = "economize";

		private $conn;

		public function __construct()
		{
			try {

				$this->conn = new \PDO(
					"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
					Sql::USERNAME,
					Sql::PASSWORD
				);

				$this->conn->exec("SET CHARACTER SET utf8");
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			} catch(PDOException $e) {

				$json['code'] = $e->getCode();
				$json['message'] = $e->getMessage();
				$json['file'] = $e->getFile();
				$json['line'] = $e->getLine();

				// $mailer = new Error($json);
				// $mailer->send();

				echo json_encode($json);
				exit;

			}
		}

		private function setParams($statement, $parameters = array())
		{
			foreach ($parameters as $key => &$value) {
				$this->bindParam($statement, $key, $value);
			}
		}

		private function bindParam($statement, $key, $value)
		{
			$statement->bindParam($key, $value);
		}

		public function query($rawQuery, $params = array())
		{
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);
			
			$result = $stmt->execute();

			if($result === true) return true;
			else return false;
		}

		public function select($rawQuery, $params = array()):array
		{
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);

			$stmt->execute();
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		public function selectAll($rawQuery, $params = array()):array
		{
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);

			$stmt->execute();
			return $stmt->fetchAll();
		}
    }
