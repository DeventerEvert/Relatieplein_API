<?php
	require_once("../config/DBconfig.php");

	class User {
		private PDO $db;
		public function __construct() {
			$this->db = Database::getInstance()->getConnection();
		}
		public function register(string $firstName, string $lastName, string $email, string $password, string $gender): bool {
			$stmt = $this->db->prepare("SELECT * FROM user WHERE email = :email");
			$stmt->execute(["email" => $email]);
			if($stmt->rowCount() > 0) {
				return false;
			}
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
			$stmt = $this->db->prepare("INSERT INTO user (firstName, lastName, email, password, gender) VALUES (:firstName, :lastName, :email, :password, :gender)");
			return $stmt->execute(["firstName" => $firstName, "lastName" => $lastName, "email" => $email,"password" => $hashedPassword, "gender" => $gender]);
		}
		public function validateLogin(string $email, string $password): bool {
			$stmt = $this->db->prepare("SELECT password FROM user WHERE email = :email");
			$stmt->execute(["email" => $email]);
			if($stmt->rowCount() === 0) {
				return false;
			}
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return password_verify($password, $result["password"]);
		}
		public function getIdByEmail(string $email): ?int {
			$stmt = $this->db->prepare("SELECT user_id FROM user WHERE email = :email");
			$stmt->execute(["email" => $email]);
			if($stmt->rowCount() === 0) {
				return null;
			}
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return (int) $result["user_id"];
		}
	}
?>  