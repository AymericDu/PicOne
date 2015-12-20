/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	sendRequest();
	var diapoButton = document.getElementById("diapoButton");
	diapoButton.addEventListener("click", turnOnModeDiapo, false);
}
window.addEventListener("load", setupListeners, false);

/*
	supprime le bouton pour ajouter plus de photo
*/
function deleteButton () {
	var button = document.querySelector("#library > button");
	var library = document.getElementById("library");
	library.removeChild(button);
}

/*
	change le bouton en écrivant "Chargement..."
*/
function changeStateButtonLoading () {
	var button = document.querySelector("#library > button");
	button.removeEventListener("click", sendRequest, false);
	button.innerHTML = "Chargement...";
}

/*
	change le bouton en écrivant "Plus d'images"
*/
function changeStateButtonMoreImages () {
	var button = document.querySelector("#library > button");
	button.addEventListener("click", sendRequest, false);
	button.innerHTML = "Plus d'images";
}

var cpt = 0;
var nbImagesDisplay = 20;

/*
	envoie la requete pour récupérer la recherche d'images
*/
function sendRequest (ev) {
	changeStateButtonLoading();
	var get = "";
	var search = location.search;
	var limit = "from="+cpt*nbImagesDisplay+"&limit="+nbImagesDisplay;
	cpt++;
	if (search.length == 0) {
		get = "?"+limit;
	} else {
		get = search+"&"+limit;
	}
	var requete = new XMLHttpRequest();
	requete.open("GET", "http://webtp.fil.univ-lille1.fr/~ducroquetz/PicOne/webService.php"+get, true);
	requete.addEventListener("load", loadImages);
	if (document.getElementById("diapo") != null) {
		requete.addEventListener("load", initTabInputAndDisplay);
	}
	requete.send(null);
}

/*
	affiche la description des photos
*/
function displayDescription () {
	this.lastElementChild.style.display = "";
}

/*
	cache la description des photos
*/
function hiddenDescription () {
	this.lastElementChild.style.display = "none";
}

/*
	charge les images dans le formulaire contenant dans la balise d'id "library"
*/
function loadImages (ev) {
	var request = JSON.parse(this.responseText);
	if (request.status != "ok") {
		error();
		return;
	}
	var form = document.querySelector("#library form");
	var imagesTab = request.results;
	var nbImages = imagesTab.length;
	if (nbImages == 0 && document.querySelectorAll("#library form article").length == 0) {
		var p = document.createElement("p");
		p.innerHTML = "Aucun résultat";
		form.parentNode.appendChild(p);
	}
	for (var i = 0; i < nbImages; i++) {
		var article = document.createElement("article");
		var input = document.createElement("input");
		input.name = "imagesSelection[]";
		input.type = "checkbox";
		input.value = imagesTab[i].url;
		article.appendChild(input);
		var image = document.createElement("img");
		image.src = request.thumbnails_dir+imagesTab[i].thumbnail;
		image.alt = imagesTab[i].title;
		article.appendChild(image);
		var div = document.createElement("div");
		div.innerHTML = '<p>Titre : '+imagesTab[i].title+'</p><p>Auteur : <a href="'+imagesTab[i].url_author+'" target="_blank">'+imagesTab[i].author+'</a></p><p>Dimensions : '+imagesTab[i].size+'</p><p>Licence : '+imagesTab[i].licence+'</p><p><a href="'+imagesTab[i].url+'" target="_blank">Original</a></p>';
		div.style.display = "none";
		article.appendChild(div);
		article.addEventListener("mouseover", displayDescription, false);
		article.addEventListener("mouseout", hiddenDescription, false);
		form.appendChild(article);
	}
	if (nbImages != nbImagesDisplay) {
		deleteButton();
	} else {
		changeStateButtonMoreImages();
	}
}

/*
	affiche un message d'erreur
*/
function error () {
	deleteButton();
	var library = document.getElementById("library");
	var p = document.createElement("p");
	p.innerHTML = "Erreur de chargement !";
	library.appendChild(p);
}

var indiceImage = 0;
var tabInput;
var nbInput;

/*
	initialise le tableau d'"input" du forulaire contenu dans la balise d'id "library" ainsi que le nombre d'images
*/
function initTabInputAndDisplay () {
	tabInput = document.querySelectorAll("#library form input");
	nbInput = tabInput.length;
	displayImage();
}

/*
	active le mode diaporama
*/
function turnOnModeDiapo () {
	var diapoButton = document.getElementById("diapoButton");
	diapoButton.removeEventListener("click", turnOnModeDiapo, false);
	indiceImage = 0;
	var body = document.querySelector("body");
	var diapo = document.createElement("section");
	diapo.id = "diapo";
	var next = document.createElement("img");
	next.alt = "Suivant";
	next.title = "Suivant";
	next.src = "images/chevron_right.svg";
	next.id = "next";
	diapo.appendChild(next);
	next.addEventListener("click", nextImage, false);
	var previous = document.createElement("img");
	previous.alt = "Précédent";
	previous.title = "Précédent";
	previous.src = "images/chevron_left.svg";
	previous.id = "previous";
	diapo.appendChild(previous);
	previous.addEventListener("click", previousImage, false);
	var cross = document.createElement("img");
	cross.alt = "Quitter";
	cross.title = "Quitter";
	cross.src = "images/cross.svg";
	cross.id = "cross";
	diapo.appendChild(cross);
	cross.addEventListener("click", turnOffModeDiapo, false);
	window.addEventListener("keydown", echap, false);
	var img = document.createElement("img");
	img.id = "imgDiapo";
	diapo.appendChild(img);
	body.appendChild(diapo);
	
	initTabInputAndDisplay();
}

/*
	affiche une image dans le diaporama
*/
function displayImage () {
	if (indiceImage >= nbInput-1 && document.querySelector("#library > button") != null) {
		sendRequest();
	}
	
	var imgDiapo = document.getElementById("imgDiapo");
	imgDiapo.src = ""; // permet sur certain navigateur d'éffacer l'image précèdente pour voir le chargement de la nouvelle
	if (indiceImage >= 0 && indiceImage < nbInput) {
		imgDiapo.src = tabInput[indiceImage].value;
	}
	
	var previous = document.getElementById("previous");
	if (indiceImage <= 0) {
		previous.style.display = "none";
	} else {
		previous.style.display = "";
	}
	
	var next = document.getElementById("next");
	if (indiceImage >= nbInput-1) {
		next.style.display = "none";
	} else {
		next.style.display = "";
	}
}

/*
	passe à l'image suivante dans le diaporama
*/
function nextImage () {
	indiceImage++;
	displayImage();
}

/*
	passe à l'image précèdente dans le diaporama
*/
function previousImage () {
	indiceImage--;
	displayImage();
}

/*
	désactive le mode diaporama si la touche Echap est appuyée
*/
function echap (ev) {
	if (ev.keyCode == 27) {
		turnOffModeDiapo();
	}
}

/*
	désactive le mode diaporama
*/
function turnOffModeDiapo () {
	var cross = document.getElementById("cross");
	cross.removeEventListener("click", turnOffModeDiapo, false);
	window.removeEventListener("keydown", echap, false);
	var diapo = document.getElementById("diapo");
	diapo.parentNode.removeChild(diapo);
	var diapoButton = document.getElementById("diapoButton");
	diapoButton.addEventListener("click", turnOnModeDiapo, false);
}
