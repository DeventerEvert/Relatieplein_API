<?php
	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	error_reporting(E_ALL);
	require_once("../handlers/sessionhandler.php");
	$sessionHandler = new Session();
    $userId = $sessionHandler->getUserId();

    if ($userId !== null) {
        // Continue with the rest of your code using $userId
        echo "Welcome, user with ID: " . htmlspecialchars($userId);
    } else {
        // Handle the case where the user_id is not set in the session
        echo "User is not logged in.";
        // Optionally, redirect to the login page
        header("Location: login.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>relatieplein - homepage</title>
</head>
<body>  
<form method="post" action="../handlers/logoutHandler.php">
		<button type="submit">Logout</button>
	</form>
</body>
</html>