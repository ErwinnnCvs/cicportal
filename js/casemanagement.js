$(document).ready(function(){

    $.ajax({
        type: 'POST',
        url: 'ajax/casemanagement/seps.php',
        data: {datetime : timestamp},
        success: function(response){
            alert(response)
        }
     });
});