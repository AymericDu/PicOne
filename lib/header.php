<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

require_once("lib/auth.php");
require_once("lib/biblio.php");
?>

<header>
	<a href="home.php" title="Accueil" id="logo"><img src="images/picone.png" alt="PicOne"/></a>
	<h1><?php if (isset($title)) { echo $title; } ?></h1>
	<?php
	if (! isset($register)) {
		require("lib/parameter.php");
	}
	?>
</header>
