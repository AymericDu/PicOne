/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	var allCheckedButton = document.getElementById("allChecked");
	allCheckedButton.addEventListener("click", allChecked, false);
}
window.addEventListener("load", setupListeners, false);

/*
	coche toutes les "checkbox" dans le formulaire contenu dans la balise d'id "library"
*/
function allChecked () {
	var input = document.querySelectorAll("#library > form input");
	var nbInput = input.length;
	for (var i = 0; i < nbInput; i++) {
		if (input[i].type == "checkbox") {
			input[i].checked = true;
		}
	}
}
