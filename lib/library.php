<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

require_once("lib/auth.php");
require_once("lib/biblio.php");
?>

<script src="js/searchRequest.js" type="text/javascript"></script>
<script src="js/libraryForm.js" type="text/javascript"></script>

<section class="main" id="library">
	<?php
	if (isConnect() && isset($_POST["validCollection"])) {
		if (isset($home)) {
			echo addImagesMyLibrary()." image(s) ajoutée(s)";
		} else if (isset($mylibrary)) {
			echo deleteFromLibrary()." image(s) supprimée(s)";
		}
	}
	?>
	<form method="post" action="#">
		<div id="collectionButtons">
			<button type="button" id="allChecked" <?php if (! isConnect()) { echo "disabled=\"disabled\""; } ?>>Sélectionner tout</button>
			<button type="reset" <?php if (! isConnect()) { echo "disabled=\"disabled\""; } ?>>Déselectionner tout</button>
			<?php
			if (isset($home)) {
				echo "<button type=\"submit\" name=\"validCollection\" ";
				if (! isConnect()) {
					echo "disabled=\"disabled\"";
				}
				echo ">Ajouter à ma photothèque</button>";
			} else if (isset($mylibrary)) {
				echo "<button type=\"submit\" name=\"validCollection\" ";
				if (! isConnect()) {
					echo "disabled=\"disabled\"";
				}
				echo ">Supprimer de ma photothèque</button>";
			}
			?>
		</div>
	</form>
	<button type="button">Chargement...</button>
</section>
