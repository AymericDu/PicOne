/*
Thibault Arloing
Aymeric Ducroquetz
*/

/*
	abonne les fonctions au chargement de la page
*/
function setupListeners () {
	var inscripForm = document.getElementById("registerForm");
	inscripForm['name'].addEventListener('input',verifName, false);
	inscripForm['firstname'].addEventListener('input', verifFirstname, false);
	inscripForm['login'].addEventListener('input', verifLogin, false);
	inscripForm['password'].addEventListener('input', verifPassword, false);
	inscripForm['confirmation'].addEventListener('input', verifConfirmation, false);
}
window.addEventListener("load", setupListeners, false);

/*
	vérifie la validité du prénom saisi
*/
function verifFirstname (ev) {
	if (! this.validity.valueMissing && ! this.validity.patternMismatch) {
		this.setCustomValidity("");
	} else {
		this.setCustomValidity("Entrez un prénom valide");
	}
}

/*
	vérifie la validité du nom saisi
*/
function verifName (ev) {
	if (! this.validity.valueMissing && ! this.validity.patternMismatch) {
		this.setCustomValidity("");
	} else {
		this.setCustomValidity("Entrez un nom valide");
	}
}

/*
	vérifie la validité du login saisi
*/
function verifLogin (ev) {
	if (! this.validity.valueMissing && ! this.validity.patternMismatch) {
		this.setCustomValidity("");
	} else {
		this.setCustomValidity("Entrez un pseudo valide");
	}
}

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
	vérifie la validité du confirmation saisie
*/
function verifConfirmation (ev) {
	if (! this.validity.valueMissing) {
		var password = document.getElementById("password");
		if (this.value == password.value) {
			this.setCustomValidity("");
		} else {
			this.setCustomValidity("Entrez le même mot de passe");
		}
	}
}
