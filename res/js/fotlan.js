$(function () {

	// Loading connection profile
	$('#profile-connect').click(function () {
		$.ajax({
			url : '/res/lib/profile-change.php',
			type : 'POST',
			data : 'user=' + $('#profile-user').val() + '&pswd=' + $('#profile-password').val(),
			dataType : 'xml',
			success : function(xmlData){
				if($("Profile Change", xmlData).text() == 'No')
					$("#profile-error").text('Echec de la connexion !').show("slow").delay(3000).hide("slow");
				else
					window.location.reload();
			},
			error : function(result, status, error){
				$("#profile-error").text('Connexion impossible : ' + error).show("slow").delay(3000).hide("slow");
			}
		});
	})

});
