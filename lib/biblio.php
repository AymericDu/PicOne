<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

require('lib/connexion.php');
require('lib/hashUtil.php');

function __autoload($className) 
{
  include 'lib/'.$className . '.class.php';
}

/*
  Si login et password sont corrects, alors 
  le résultat est une instance d'Identite décrivant cet utilisateur
  Sinon le résultat vaut null
*/
function authentifie($login, $password)
{
	/*SQL*/
	global $connexion;
	$stmt = $connexion->prepare(
		"select	
			login,
			password,
			name,
			firstname
			from picone.identity
			where
				login = :login");
	$stmt->bindValue(":login", $login);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	
	/* control */
	if ($identity = $stmt->fetch()) {
		if ($identity['password'] == crypt($password, $identity['password'])) {
			return new Identite($identity['login'],$identity['name'],$identity['firstname']);
		}
	}
	return null;
}

/*
 Verifie l'authentification 
 La fonction se termine normalement
 - Si l'état de la session indique que l'authentification a déjà eu lieu
 - Si des paramètres login/password corrects ont été fournis
 Après exécution correcte,  $_SESSION['ident'] contient l'identité de l'utilisateur
 Dans tous les autres cas, une exception est déclenchée
*/
function controleAuthentification()
{
  if (isset($_SESSION['ident'])) {
		return true;
	}
	if (! (isset($_POST['login'])  && isset($_POST['password']))) {
		throw new Exception('champs vide');
	}
	$login = $_POST['login'];
	$password = $_POST['password'];
	$personne = authentifie($login,$password);
	if (! $personne) {
		throw new Exception('login/password incorrects');
	}
	$_SESSION['ident'] = $personne;
	return true;
}

function regexValid ($input, $regex) {
	return filter_input(INPUT_POST, $input, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/".$regex."/")));
}

/* INSCRIPTION */

/*
	vérifie si le nom est valide
*/
function nameIsValid ($name) {
	return isset($_POST[$name]) && regexValid($name, "^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-]{3,30}$");
}

/*
	vérifie si le pseudo est valide
*/
function loginIsValid ($login) {
	return isset($_POST[$login]) && regexValid($login, "^[a-zA-Zéèçàùïëäöüÿîêâôûŷ\-0-9]{3,30}$");
}

/*
	vérifie si le mot de passe est valide
*/
function passwordIsValid ($password) {
	return isset($_POST[$password]) && regexValid($password, "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,30}$");
}

/*
	vérifie si la confirmation est identique au mot de passe
*/
function confirmationIsValid ($password, $confirmation) {
	return isset($_POST[$confirmation]) && $_POST[$confirmation] == $_POST[$password];
}

/*
	controle le pseudo
*/
function controlLogin ($login) {
	global $connexion;
	$stmt = $connexion->prepare(
		"select
			login
			from picone.identity 
			where login = :login");
	$stmt->bindValue(":login", $login);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch();
}

/*
	inscrit un utilisateur
	*/
function inscription ($login, $password, $name, $firstname) {
	global $connexion;
	$insertion = $connexion->prepare(
		'insert
			into picone.identity
			("login", "password", "name", "firstname")
			values (:login, :password, :name, :firstname)');
	$insertion->bindValue(":login", $login);
	$insertion->bindValue(":password", crypt($password, aleatSalt()));
	$insertion->bindValue(":name", $name);
	$insertion->bindValue(":firstname", $firstname);
	return $insertion->execute();
}

/* MON COMPTE */

/*
	vérifie si l'utilisateur est connecté
*/
function isConnect () {
	return isset($_SESSION['ident']);
}

/*
	controle le mot de passe
*/
function controlPassword ($login, $password) {
	global $connexion;
	$stmt = $connexion->prepare(
		"select
			login,
			password
			from picone.identity
			where
				login = :login");
	$stmt->bindValue(":login", $login);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	if ($identity = $stmt->fetch()) {
		$truepasscrypt = $identity['password'];
		return crypt($password, $truepasscrypt) == $truepasscrypt;
	} else {
		return FALSE;
	}
}

/*
	met à jour le mot de passe
*/
function updatePassword ($login, $password) {
	global $connexion;
	$update = $connexion->prepare(
		'update
			picone.identity
			set "password" = :password
			where "login" = :login');
	$update->bindValue(":login", $login);
	$update->bindValue(":password", crypt($password, aleatSalt()));
	return $update->execute() && $update->rowCount()==1;
}

/*
	met à jour l'identité
*/
function updateIdentity ($login, $name, $firstname, $changes) {
	global $connexion;
	$update = $connexion->prepare(
		"update
			picone.identity
			set ".implode(", ", $changes).
			" where login = :login");
  $update->bindValue(":login", $login);
  if ($name) {
    $update->bindValue(":name", $name);
	}
  if ($firstname) {
    $update->bindValue(":firstname", $firstname);
	}
	if ($update->execute() && $update->rowCount()==1){
    if ($name) {
			$_SESSION['ident']->nom = $name;
		}
    if ($firstname) {
			$_SESSION['ident']->prenom = $firstname;
		}
    return true;
  } else {
		return false;
	}
}

/*
	crée le code HTML pour avoir toutes les "option" des catégories
*/
function makeOptionFormCategory () {
	$tabCategories = array("animaux", "astronomie", "monument", "nature", "transport", "sport", "inclassable", "livre", "nourriture", "portrait");
	usort($tabCategories, strcmp);
	$html = "<select id=\"category\" name=\"category\">\n";
	$html .= "<option value=\"\">Catégorie</option>\n";
	foreach ($tabCategories as $category) {
		$html .= "<option value=\"".$category."\" ";
		if (isset($_GET["category"]) && $_GET["category"] == $category) {
			$html .= "selected=\"selected\"";
		}
		$html .= ">".ucfirst($category)."</option>\n";
	}
	$html .= "</select>\n";
	return $html;
}

/*
	crée le code HTML pour avoir toutes les "option" des pseudo
*/
function makeOptionFormPseudo () {
	global $connexion;
	$html = "<select id=\"collection\" name=\"collection\">\n";
	$html .= "<option value=\"\">Collection</option>\n";
	$stmt = $connexion->prepare(
		'select
			login
		from picone.identity
		order by login asc');
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while($identity = $stmt->fetch()){
		$login = $identity['login'];
		$html .= "<option value=\"".$login."\" ";
		if (isset($_GET["collection"]) && $_GET["collection"] == $login) {
			$html .= "selected=\"selected\"";
		}
		$html .= ">".$login."</option>\n";
	}
	$html .= "</select>\n";
	return $html;
}

/*
	crée le code HTML pour avoir toutes les "option" des auteurs
*/
function makeOptionFormAuthor () {
	global $connexion;
	$html = "<select id=\"author\" name=\"author\">\n";
	$html .= "<option value=\"\">Auteur</option>\n";
	$stmt = $connexion->prepare(
		'select
			author
		from picone.photos
		group by author
		order by author asc');
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while($photos = $stmt->fetch()){
		$author = $photos['author'];
		$html .= "<option value=\"".$author."\" ";
		if (isset($_GET["author"]) && $_GET["author"] == $author) {
			$html .= "selected=\"selected\"";
		}
		$html .= ">".$author."</option>\n";
	}
	$html .= "</select>\n";
	return $html;
}

/*
	ajoute des images à la photothèque personne
*/
function addImagesMyLibrary () {
	if (isConnect() && isset($_POST["validCollection"]) && isset($_POST["imagesSelection"])) {
		global $connexion;
		$cpt = 0;
		$login = $_SESSION["ident"]->login;
		foreach ($_POST["imagesSelection"] as $url) {
			$insertion = $connexion->prepare(
				'insert
					into picone.collections
					("login", "url")
					values (:login, :url)');
			$insertion->bindValue(":login", $login);
			$insertion->bindValue(":url", $url);
			if ($insertion->execute()) {
				$cpt++;
			}
		}
		return $cpt;
	}
	return 0;
}

/*
	Controle que la librairie envoyé est bien celle de l'utilisateur connécté
*/
function controlLibrary(){
	return ($_SESSION['ident']->login == $_GET['collection']);
}

/*
	Supprime une image
*/
function deleteFromLibrary(){
	if (isConnect() && isset($_POST["validCollection"]) && isset($_POST["imagesSelection"])) {
		global $connexion;
		$cpt = 0;
		$login = $_SESSION["ident"]->login;
		foreach ($_POST["imagesSelection"] as $url) {
			$delete = $connexion->prepare(
				'delete
					from picone.collections
					where login=:login and url=:url');
			$delete->bindValue(":login", $login);
			$delete->bindValue(":url", $url);
			if ($delete->execute()) {
				$cpt++;
			}
		}
		return $cpt;
	}
	return 0;
}

/*
	supprime les accents d'une chaine de caractère en fonction de son encodage
*/
function removeAccents($str, $charset='utf-8')
{
	$str = htmlentities($str, ENT_NOQUOTES, $charset);
	$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
	return $str;
}

/*
	descris la personne connecté et la déconnecte
*/
function unsubscribe () {
	if (! isConnect()) {
		return;
	}
	global $connexion;
	$login = $_SESSION["ident"]->login;
	$delete = $connexion->prepare(
		'delete
			from picone.collections
			where login=:login');
	$delete->bindValue(":login", $login);
	if (! $delete->execute()) {
		echo "<h2>Une erreur s'est produite. Veuillez recommencer !</h2>";
		return;
	}
	$delete = $connexion->prepare(
		'delete
			from picone.identity
			where login=:login');
	$delete->bindValue(":login", $login);
	if (! $delete->execute()) {
		echo "<h2>Une erreur s'est produite. Veuillez recommencer !</h2>";
		return;
	}
	require("logout.php");
}
?>
