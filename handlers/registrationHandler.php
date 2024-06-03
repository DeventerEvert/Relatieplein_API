<?php
	require_once("../models/user.php");

	class RegistrationHandler {
		private User $user;
		public function __construct() {
			$this->user = new User();
		}
		public function handleRequest(): ?string {
			if($_SERVER["REQUEST_METHOD"] === "POST") {
				$firstName = htmlspecialchars($_POST["firstName"] ?? "", ENT_QUOTES, "UTF-8");
				$lastName = htmlspecialchars($_POST["lastName"] ?? "", ENT_QUOTES, "UTF-8");
				$email = htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8");
				$password = htmlspecialchars($_POST["password"] ?? "", ENT_QUOTES, "UTF-8");
				$gender = htmlspecialchars($_POST["gender"] ?? "", ENT_QUOTES, "UTF-8");
				$success = $this->user->register($firstName, $lastName, $email, $password, $gender);
				if($success) {
					header("Location: ../views/login.php");
					exit();
				} else {
					return "Username or email already in use.";
				}
			}
			return null;
		}
	}
?>