/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	var img = document.querySelector("#parameter > img");
	var div = document.querySelector("#parameter > div");
	if (img != null && div != null) {
		if (div.style.display == "block") {
			img.addEventListener("click", hiddenDivParameter, false);
		} else {
			img.addEventListener("click", displayDivParameter, false);
		}
	}
}
window.addEventListener("load", setupListeners, false);

/*
	affiche la balise "div" d'id "parameter"
*/
function displayDivParameter () {
	var img = document.querySelector("#parameter > img");
	img.removeEventListener("click", displayDivParameter, false);
	var div = document.querySelector("#parameter > div");
	div.style.display = "block";
	img.addEventListener("click", hiddenDivParameter, false);
}

/*
	cache la balise "div" d'id "parameter"
*/
function hiddenDivParameter () {
	var img = document.querySelector("#parameter > img");
	img.removeEventListener("click", hiddenDivParameter, false);
	var div = document.querySelector("#parameter > div");
	div.style.display = "none";
	img.addEventListener("click", displayDivParameter, false);
}
