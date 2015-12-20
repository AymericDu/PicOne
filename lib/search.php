<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

require_once("lib/auth.php");
require_once("lib/biblio.php");
?>

<script src="js/menuForm.js" type="text/javascript"></script>

<h2><span class="green">R</span>echerche</h2>

<form method="get" action="#" id="search">
	<input type="text" name="keywords" id="keywords" placeholder="Mots-clés" <?php if (isset($_GET["keywords"])) { echo "value=\"".$_GET["keywords"]."\""; } ?> autofocus="autofocus"/>
	<br/>
	<?php
	echo makeOptionFormAuthor();
	echo "<br/>\n";
	echo makeOptionFormCategory();
	if (! isset($mylibrary)) {
		echo "<br/>\n";
		echo makeOptionFormPseudo();
	} else {
		if (isConnect()) {
			echo "<input type=\"hidden\" name=\"collection\" value=\"".$_SESSION["ident"]->login."\"/>\n";
		}
	}
	?>
	<br/>
	<br/>
	<button type="button" id="clear">Effacer</button>
	<button type="submit" name="validSearch">Rechercher</button>
</form>

<h2><span class="green">D</span>iaporama</h2>

<form>
	<button type="button" id="diapoButton">Activer</button>
</form>
