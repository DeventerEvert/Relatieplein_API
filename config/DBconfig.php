<?php
	class Database {
		private static ?Database $instance = null;
		private PDO $connection;
		private function __construct() {
			// localhost voor db
			$host = "localhost";
			$username = "root";
			$password = "";
			$dbname = "relatieplein";

			// online server voor db
			// $host = "localhost";
			// $username = "klas4s21_519546";
			// $password = "greengiants1";
			// $dbname = "klas4s21_519546";
			try {
				$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
				$this->connection = new PDO($dsn, $username, $password, [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES => false,
				]);
			} catch(PDOException $e) {
				throw new RuntimeException("Database connection error: " . $e->getMessage());
			}
		}
		public static function getInstance(): Database {
			if(self::$instance === null) {
				self::$instance = new Database();
			}
			return self::$instance;
		}
		public function getConnection(): PDO {
			return $this->connection;
		}
	}
?>