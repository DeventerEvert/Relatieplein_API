<?php
	class Session {
		public function __construct() {
			if(session_status() === PHP_SESSION_NONE) {
				session_start();
			}
		}
		public function setUserSession(int $userId): void {
			$_SESSION["user_id"] = $userId;
		}
		public function isAuthenticated(): bool {
			return isset($_SESSION["user_id"]);
		}
		public function getUserId(): ?int {
			return $_SESSION["user_id"] ?? null;
		}
		public function destroySession(): void {
			session_unset();
			$_SESSION = array();
			session_destroy();
		}
	}
?>