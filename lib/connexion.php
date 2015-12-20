<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

	try {
		$connexion = new PDO("pgsql:host=localhost;dbname=ducroquetz", "ducroquetz", "XXXX");
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit();
	}
?>