<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

require_once("lib/auth.php");
require_once("lib/biblio.php");
?>

<script src="js/clickParameter.js" type="text/javascript"></script>

<div id="parameter">
	<?php
	if (isConnect()) {
		require('lib/formuLogout.php');
	} else {
		require('lib/formuLogin.php');
	}
	?>
</div>
