<?php
	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);

	include_once("../handlers/loginhandler.php");
	$handler = new LoginHandler();
	$message = $handler->handleRequest();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
	<link rel="stylesheet" href="../assets/css/main.css">
	<link rel="stylesheet" href="../assets/css/account.css">
	<script defer src="../assets/js/main.js"></script>
	<?php include_once("../assets/img/favicons/loader.php") ?>
	<title>Gezondheidsmeter - Login</title>
</head>
<body>
	<div class="container">
		<form method="post" enctype="multipart/form-data">
			<p>Aanmelden bij je account</p>
			<input type="text" name="email" placeholder="E-Mail" required>
			<input type="password" name="password" placeholder="Password" required>
			<button type="submit">Doorgaan</button>
			<p>Heb je nog geen account? <a href="register.php">Meld je aan</a></p>
			<?php if (!empty($message)): ?>
				<div class="message">
					<?php echo htmlspecialchars($message); ?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</body>
</html>