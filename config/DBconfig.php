<?php
	class Database {
		private static ?Database $instance = null;
		private PDO $connection;
		private function __construct() {
			$host = "localhost";
			$username = "root";
			$password = "";
			$dbname = "relatieplein";
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