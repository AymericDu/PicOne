<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/
?>
<img src="images/parameter.svg" alt="Parameter" title="Paramètre"/>
<div>
	<p><?php require_once("lib/biblio.php"); echo $_SESION["ident"]->login; ?></p>
	<p><a id="myaccount" href="myaccount.php">Mon compte</a></p>
	<p><a id="logout" href="logout.php">Déconnexion</a></p>
</div>
