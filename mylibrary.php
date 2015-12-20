<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

$title = "<span class=\"green\">M</span>a <span class=\"green\">P</span>hotothèque";
$mylibrary = "";
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<?php
require_once("lib/biblio.php");
require("lib/head.html");
?>

<body>
	<?php
	require("lib/header.php");
	require("lib/menu.php");
	if(isConnect()){
		if(controlLibrary())
			require("lib/library.php");
		else
			echo "<section class=\"main\"><h2>Vous n'êtes pas autorisé à accéder à cette page</h2></section>\n";
	} else
		echo "<section class=\"main\"><h2>Vous n'êtes pas connectés</h2></section>\n";
	?>
</body>

</html>
