$(document).ready(function () {

	function uriDecodeString(inputString) {
		return inputString;
	}

	var prov_subj_number = document.getElementById("provider_subject_number");



	if (prov_subj_number.value == '') {

	} else {
		console.log(uriDecodeString(prov_subj_number.value));
	}
	
});