$(function () {

	// Loading connection profile
	$('#profile-connect').click(function () {
		$.post('/res/php/profile-change.php', {
			user: $('#profile-user').val(),
			pswd: $('#profile-password').val()
		}, function(xmlData) {
			if($('Profile Change', xmlData).text() == 'No')
				$('#profile-error').text('Echec de la connexion !').show('slow').delay(3000).hide('slow');
			else
				window.location.reload();				
		});
	});

});
