<?php
	require_once("../models/user.php");
	require_once("sessionHandler.php");

	class LoginHandler {
		private User $user;
		private Session $sessionHandler;
		public function __construct() {
			$this->user = new User();
			$this->sessionHandler = new Session();
		}
		public function handleRequest(): ?string {
			if($_SERVER["REQUEST_METHOD"] === "POST") {
				$email = htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, 'UTF-8');
				$password = htmlspecialchars($_POST["password"] ?? "", ENT_QUOTES, 'UTF-8');
				if($this->user->validateLogin($email, $password)) {
					$userId = $this->user->getIdByEmail($email);
					$this->sessionHandler->setUserSession($userId);
					header("Location: userPage.php");
					exit;
				} else {
					return "Ongeldige gebruikersnaam of wachtwoord.";
				}
			}
			return null;
		}
	}
?>