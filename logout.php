<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

	header("Location: home.php");
	require('lib/auth.php');
	session_destroy();
	unset($_SESSION["ident"]);
?>
