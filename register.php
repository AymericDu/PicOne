<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

$title = "<span class=\"green\">I</span>nscription";
$register = "";

require('lib/auth.php');
session_destroy();
unset($_SESSION["ident"]);

require_once("lib/biblio.php");

unset($error);

if (isset($_POST['valid'])) {
	
	if (! nameIsValid('name')) {
		$error .= "<li>Entrez un nom valide</li>\n";
	} else {
		$name = $_POST['name'];
	}
	
	if (! nameIsValid("firstname")) {
		$error .= "<li>Entrez un prénom valide</li>\n";
	} else {
		$firstname = $_POST['firstname'];
	}
	
	if (! loginIsValid("login")) {
		$error .= "<li>Entrez un pseudo valide</li>\n";
	} else {
		$login = $_POST['login'];
		if (controlLogin($login)) {
			$error .= "<li>Pseudo déjà utilisé</li>";
		}
	}
	
	if (! passwordIsValid("password")) {
		$error .= "<li>Entrez un mot de passe valide</li>\n";
	} else {
		$password = $_POST['password'];
	}
	
	if (! confirmationIsValid("password", "confirmation")) {
		$error .= "<li>Entrez le même mot de passe</li>\n";
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
	
	<script src="js/registerVerif.js" type="text/javascript"></script>
	
	<section class="main" id="register">
		<?php
		if (isset($_POST['valid'])) {
			if (isset($error)) {
				echo "<ul>\n$error</ul>\n";
			} else {
				if (inscription($login, $password, $name, $firstname)) {
					echo "<h2>Vous êtes inscrit</h2>\n</section>\n</body>\n</html>\n";
					exit();
				} else {
					echo "<h2>Une erreur s'est produite. Veuillez recommencer, s'il vous plait !</h2>\n";
				}
			}
		}
		?>

		<form method="post" action="register.php" id="registerForm">
			<fieldset>
				<legend>Création d'un utilisateur</legend>
				<input type="text" name="name" id="name" placeholder="Nom" required="required" pattern="^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-]{3,30}$" <?php if (isset($_POST['name'])) { echo "value=\"{$_POST['name']}\""; } ?> autofocus="autofocus"/>
				<br/>
				Le nom doit être composé uniquement de caractères alphabétiques et/ou de tiret "-" (3 à 30 caractères).
				<hr/>
				<input type="text" name="firstname" id="firstname" placeholder="Prénom" required="required" pattern="^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-]{3,30}$" <?php if (isset($_POST['firstname'])) { echo "value=\"{$_POST['firstname']}\""; } ?> />
				<br/>
				Le prénom doit être composé uniquement de caractères alphabétiques et/ou de tiret "-" (3 à 30 caractères).
				<hr/>
				<input type="text" name="login" id="login" placeholder="Pseudo" required="required" pattern="^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-0-9]{3,30}$" <?php if (isset($_POST['login'])) { echo "value=\"{$_POST['login']}\""; } ?> />
				<br/>
				Le pseudo doit être composé uniquement de caractères alphanumériques et/ou de tiret "-" (3 à 30 caractères).
				<hr/>
				<input type="password" name="password" id="password" placeholder="Mot de passe" required="required" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,30}$" />
				<br/>
				Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial (8 à 30 caractères).
				<hr/>
				<input type="password" name="confirmation" id="confirmation" placeholder="Confirmation du mot de passe" required="required" />
				<br/>
				La confirmation doit être strictement identique au mot de passe au-dessus.
				<hr/>
				<button type="submit" name="valid">S'insrire</button>
			</fieldset>
		</form>
	</section>
</body>

</html>
