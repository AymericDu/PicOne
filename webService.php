<?php
/*
Thibault Arloing
Aymeric Ducroquetz
*/

	header("Content-Type: application/json; charset=utf-8");
	
	require_once('lib/connexion.php');
	require_once('lib/biblio.php');
	
	$refineRequest = "";
	$nbImages = "";
	$conditions = array();
	$tabPhotos = array();
	$status = "ok";
	
	if (isset($_GET["collection"]) && strlen($_GET["collection"]) > 0) {
		$refineRequest .= " join picone.collections on photos.url = collections.url ";
		$conditions[] = "login = :collection";
	}
	
	if (isset($_GET["category"]) && strlen($_GET["category"]) > 0) {
		$conditions[] = "categories like :category";
	}
	
	if (isset($_GET["author"]) && strlen($_GET["author"]) > 0) {
		$conditions[] = "author = :author";
	}
	
	if (isset($_GET["keywords"]) && strlen($_GET["keywords"]) > 0) {
		$goodKeywords = strtolower($_GET["keywords"]);
		$goodKeywords = removeAccents($goodKeywords);
		$tabKeywords = explode(" ", $goodKeywords);
		foreach ($tabKeywords as $key => $keyword) {
			$conditions[] = "(keywords like :keyword$key or keywords like :subKeyword$key)";
		}
	}
	
	if (count($conditions) != 0) {
		$refineRequest .= " where ".implode(" and ", $conditions)." ";
	}
	
	if (isset($_GET["from"]) && strlen($_GET["from"]) > 0) {
		$nbImages .= " offset ".$_GET["from"]." ";
	}
	
	if (isset($_GET["limit"]) && strlen($_GET["limit"]) > 0) {
		$nbImages .= " limit ".$_GET["limit"]." ";
	}
	
	$stmt = $connexion->prepare(
		"select
			photos.url,
			photos.size,
			photos.mime_type,
			photos.licence,
			photos.thumbnail,
			photos.author,
			photos.url_author,
			photos.title,
			photos.categories,
			photos.keywords
		from picone.photos
		".$refineRequest."
		order by adding_date desc
		".$nbImages);
	if (isset($_GET["collection"]) && strlen($_GET["collection"]) > 0) {
		$stmt->bindValue(":collection", $_GET["collection"]);
	}
	if (isset($_GET["category"]) && strlen($_GET["category"]) > 0) {
		$stmt->bindValue(":category", '%'.$_GET["category"].'%');
	}
	if (isset($_GET["author"]) && strlen($_GET["author"]) > 0) {
		$stmt->bindValue(":author", $_GET["author"]);
	}
	if (isset($goodKeywords) && strlen($goodKeywords) > 0) {
		foreach ($tabKeywords as $key => $keyword) {
			$stmt->bindValue(":keyword$key", '%'.$keyword.'%');
			if (strlen($keyword) > 3) {
				$subKeyword = substr($keyword, 0, 3);
			} else {
				$subKeyword = $keyword;
			}
			$stmt->bindValue(":subKeyword$key", '%'.$subKeyword.'%');
		}
	}
	if (! $stmt->execute()) {
		$status = "error";
	}
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while ($photo = $stmt->fetch()) {
		if (isset($_GET["category"]) && strlen($_GET["category"]) > 0) {
			$i = 0;
			$tabCategories = explode(" ", $photo["categories"]);
			$nbCategories = count($tabCategories);
			while ($i < $nbCategories && strcmp($_GET["category"], $tabCategories[$i]) != 0) {
				$i++;
			}
			if ($i < $nbCategories) {
				$tabPhotos[] = $photo;
			}
		} else {
			$tabPhotos[] = $photo;
		}
	}
	
	echo '{"datetime":"'.date("Y-m-d H:i:s").'","status":"'.$status.'","thumbnails_dir":"addImage/images/thumbnails/","results":'.json_encode($tabPhotos).'}';
?>
