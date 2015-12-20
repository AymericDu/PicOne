/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	var accountPasswordForm = document.getElementById("accountPasswordForm");
	accountPasswordForm['newpassword'].addEventListener('input', verifPassword, false);
	accountPasswordForm['confirmation'].addEventListener('input', verifConfirmation, false);
	var accountIdentityForm = document.getElementById("accountIdentityForm");
	accountIdentityForm['name'].addEventListener('input',verifName, false);
	accountIdentityForm['firstname'].addEventListener('input', verifFirstname, false);
}
window.addEventListener("load", setupListeners, false);

/*
	vérifie la validité du mot de passe saisi
*/
function verifPassword (ev) {
	if (! this.validity.valueMissing && ! this.validity.patternMismatch) {
		this.setCustomValidity("");
	} else {
		this.setCustomValidity("Entrez un mot de passe valide");
	}
}

/*
	vérifie la validité de la confirmation saisie
*/
function verifConfirmation (ev) {
	if (! this.validity.valueMissing) {
		var password = document.getElementById("newpassword");
		if (this.value == password.value) {
			this.setCustomValidity("");
		} else {
			this.setCustomValidity("Entrez le même mot de passe");
		}
	}
}

/*
	vérifie la validité du prénom saisi
*/
function verifFirstname (ev) {
	if (! this.validity.patternMismatch) {
		this.setCustomValidity("");
	} else {
		this.setCustomValidity("Entrez un prénom valide");
	}
}

/*
	vérifie la validité du nom saisi
*/
function verifName (ev) {
	if (! this.validity.patternMismatch) {
		this.setCustomValidity("");
	} else {
		this.setCustomValidity("Entrez un nom valide");
	}
}
