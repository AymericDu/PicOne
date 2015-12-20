<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

require_once("lib/auth.php");
require_once("lib/biblio.php");
?>

<section id="menu">
	<?php
	if (isConnect()) {
		echo "<p>Connecté : ".$_SESSION["ident"]->prenom." ".$_SESSION["ident"]->nom." (".$_SESSION["ident"]->login.")</p>";
	}
	?>
	
	<h2><span class="green">P</span>age(s)</h2>
	
	<p><a href="home.php">Accueil</a></p>
	<?php
	if (isConnect()) {
		echo "<p><a href=\"mylibrary.php?collection=".$_SESSION["ident"]->login."\">Ma Photothèque</a></p>";
	}
	?>
	
	<?php
	if (! isset($register) && ! isset($account)) {
		require("lib/search.php");
	}
	?>
</section>
