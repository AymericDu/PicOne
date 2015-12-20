<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

$title = "<span class=\"green\">M</span>on <span class=\"green\">C</span>ompte";
$account = "";

require_once("lib/auth.php");
require_once("lib/biblio.php");

unset($errorPassword);
unset($fatalError);

if (isConnect()) {
	
	$login = $_SESSION['ident']->login;
	$name = $_SESSION['ident']->nom;
	$firstname = $_SESSION['ident']->prenom;

	if (isset($_POST['validPassword'])) {
		if (! isset($_POST['actualpassword'])) {
			$errorPassword .= "<li>Le mot de passe actuel est vide.</li>\n";
		} else {
			$actpass = $_POST['actualpassword'];
			if (! controlPassword($login, $actpass)) {
				$errorPassword .= "<li>Le mot de passe actuel est incorrect.</li>\n";
			}
		}
		
		if (! passwordIsValid("newpassword")) {
			$errorPassword .= "<li>Entrez un mot de passe valide</li>\n";
		} else {
			$newpass = $_POST['newpassword'];
		}
		
		if (! confirmationIsValid("newpassword", "confirmation")) {
			$errorPassword .= "<li>Entrez le même mot de passe</li>\n";
		}
	}
	
	if (isset($_POST['validIdentity'])) {
		$changes = array();
		if (isset($_POST['name']) && strlen($_POST['name']) > 0 && $_POST['name'] != $name) {
			if (nameIsValid("name")) {
				$changes[] = "name=:name";
			} else {
				$errorIdentity .= "<li>Entrez un nom valide</li>\n";
			}
		} else {
			unset($_POST['name']);
		}
		if (isset($_POST['firstname']) && strlen($_POST['firstname']) > 0 && $_POST['firstname'] != $firstname) {
			if (nameIsValid("firstname")) {
				$changes[] = "firstname=:firstname";
			} else {
				$errorIdentity .= "<li>Entrez un prénom valide</li>\n";
			}
		} else {
			unset($_POST['firstname']);
		}
	}
	
}
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<?php
require("lib/head.html");
?>

<body>
	<?php
	require("lib/header.php");
	require("lib/menu.php");
	?>
	
	<script src="js/accountVerif.js" type="text/javascript"></script>
	
	<section class="main" id="account">
		<?php
		if (! isConnect()) {
			echo "<h2>Vous n'êtes pas connecté</h2>\n</section>\n</body>\n</html>\n";
			exit();
		}
		
		if (isset($_POST['validPassword'])) {
			if (isset($errorPassword)) {
				echo "<ul>\n$errorPassword</ul>\n";
			} else {
				if (updatePassword($login, $newpass)) {
					echo "<h2>Changement de mot de passe réussi</h2>";
				} else {
					echo "<h2>Une erreur s'est produite. Veuillez recommencer, s'il vous plait !</h2>";
				}
			}
		}
		
		if (isset($_POST['validIdentity'])) {
			if (isset($errorIdentity)) {
				echo "<ul>\n$errorIdentity</ul>\n";
			} else {
				if (count($changes) == 0) {
					echo "<h2>Aucune modification de l'identité</h2>";
				} else if (updateIdentity($login, $_POST['name'], $_POST['firstname'], $changes)) {
					echo "<h2>Changement d'identité réussi</h2>";
				} else {
					echo "<h2>Une erreur s'est produite. Veuillez recommencer, s'il vous plait !</h2>";
				}
			}
		}
		
		if (isset($_POST["validUnsubscribe"])) {
			unsubscribe();
		}
		?>
		
		<form method="post" action="myaccount.php" id="accountPasswordForm">
			<fieldset>
				<legend>Changement de mot de passe</legend>
				<input type="password" name="actualpassword" id="actualpassword" placeholder="Mot de passe actuel" required="required" />
				<hr/>
				<input type="password" name="newpassword" id="newpassword" placeholder="Nouveau mot de passe" required="required" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,30}$" />
				<br/>
				Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial (8 à 30 caractères).
				<hr/>
				<input type="password" name="confirmation" id="confirmation" placeholder="Confirmation du mot de passe" required="required" />
				<br/>
				La confirmation doit être strictement identique au mot de passe au-dessus.
				<hr/>
				<button type="submit" name="validPassword">Valider</button>
			</fieldset>
		</form>
		
		<form method="post" action="myaccount.php" id="accountIdentityForm">
			<fieldset>
				<legend>Changement d'identité</legend>
				<input type="text" name="name" id="name" placeholder="Nom" pattern="^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-]{3,30}$" <?php echo "value=\"{$_SESSION['ident']->nom}\""; ?> />
				<hr/>
				<input type="text" name="firstname" id="firstname" placeholder="Prénom" pattern="^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-]{3,30}$" <?php echo "value=\"{$_SESSION['ident']->prenom}\""; ?> />
				<hr/>
				<button type="submit" name="validIdentity">Valider</button>
			</fieldset>
		</form>
		
		<script src="js/unsubscribeForm.js" type="text/javascript"></script>
		
		<form method="post" action="myaccount.php" id="accountUnsubscribeForm">
			<fieldset>
				<legend>Fermeture du compte</legend>
				<button type="button" id="unsubscribe">Se désinscrire</button>
				<div id="unsubscribeConfirmation">
					<p>Toutes vos données et informations seront définitivement perdues. Êtes-vous sûr ?</p>
					<button type="button" id="invalidUnsubscribe">Non</button>
					<button type="submit" name="validUnsubscribe">Oui</button>
				</div>
			</fieldset>
		</form>
	</section>
</body>

</html>
