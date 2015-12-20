/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	var clear = document.getElementById("clear");
	clear.addEventListener("click", allUnselected, false);
}
window.addEventListener("load", setupListeners, false);

/*
	remet le formulaire à zéro
*/
function allUnselected () {
	var input = document.getElementById("keywords");
	input.value = "";
	var tabId = ["author", "category", "collection"];
	var nbId = tabId.length;
	for (var j = 0; j < nbId; j++) {
		var option = document.querySelectorAll("select#"+tabId[j]+" > option");
		var nbOption = option.length;
		for (var i = 0; i < nbOption; i++) {
			option[i].selected = false;
		}
	}
}
