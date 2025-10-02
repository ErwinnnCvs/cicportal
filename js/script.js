$(document).ready(function(){

	$('#csv_registered_contacts').click(function(){
		var timestamp = Date.now();
		$.ajax({
	       type: 'POST',
	       url: 'ajax/extractcontactdetails.php',
	       data: {datetime : timestamp},
	       success: function(response){
	       	window.location.href = "downloadfile_contacts.php?file=" + response;
	       }
	    });
	});


	$('#extractContractsExcel').click(function(){
		var timestamp = Date.now();
		var contracts = document.getElementById('contracts_array').value;
		$.ajax({
	       type: 'POST',
	       url: 'ajax/extractcontracts.php',
	       data: {contracts : contracts, datetime : timestamp},
	       success: function(response){
	       	window.location.href = "downloadfile_contracts.php?file=" + response;
	       }
	    });
	});
});