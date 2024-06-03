<?php
	require_once("sessionHandler.php");
	$sessionHandler = new Session();
	$sessionHandler->destroySession();
 	header("Location: ../Views/login.php");
	exit;
?>