<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

 require_once('lib/biblio.php');
 
 session_name("PicOne_arloing_ducroquetz");
 session_start();
 try {
   controleAuthentification();
 }
 catch(Exception $e)
 {
	 $error_login = "Pseudo ou mot de passe incorect";
 }
?>