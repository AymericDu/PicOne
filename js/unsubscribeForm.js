/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	var unsubscribe = document.getElementById("unsubscribe");
	unsubscribe.addEventListener("click", displayUnsubscribeConfirmation, false);
	var invalidUnsubscribe = document.getElementById("invalidUnsubscribe");
	invalidUnsubscribe.addEventListener("click", hiddenUnsubscribeConfirmation, false);
}
window.addEventListener("load", setupListeners, false);

/*
	affiche la confirmation de désinscription
*/
function displayUnsubscribeConfirmation () {
	var unsubscribeConfirmation = document.getElementById("unsubscribeConfirmation");
	unsubscribeConfirmation.style.display = "block";
}

/*
	cache la confirmation de désinscription
*/
function hiddenUnsubscribeConfirmation () {
	var unsubscribeConfirmation = document.getElementById("unsubscribeConfirmation");
	unsubscribeConfirmation.style.display = "none";
}
