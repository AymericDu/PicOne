<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/
?>
<img src="images/log_in.svg" alt="Log In" title="Connexion/Inscription"/>
<div <?php if (isset($_POST['valid']) && (isset($error_login))) { echo "style=\"display: block;\""; } ?>>
	<form method="post" action="#" id="signin" > 
		<input type="text" name="login" id="login" placeholder="Pseudo" <?php if (isset($_POST['login'])) { echo "value=\"{$_POST['login']}\""; } ?> required="required" />
		<br/>
		<input type="password" name="password" id="password" placeholder="Mot de passe" required="required" />
		<br/>
		<button type="submit" name="valid">Connexion</button>
	</form>
	<?php
	if (isset($_POST['valid']) && (isset($error_login))) {
		echo "<span id=\"error_login\">$error_login</span>";
	}
	?>
	<p><a id="register" href="register.php">Inscription</a></p>
</div>
