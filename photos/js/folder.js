// Back Button
$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

// Delete Button
$('#pic-delete').click(function() {
	swal({	title: 'Confirmation.',
			type: 'question',
			text: 'Supprimer définitivement ce dossier ?',
			showCancelButton: true,
			confirmButtonText: 'Oui',
			cancelButtonText: 'Non'
	}).then(function() {
		$.post('ajax_folder_delete.php', {
			f: $('#pic-folder-id').val()
		}, function(data) {
			var response = jQuery.parseJSON(data);
			swal(response.title, response.text, response.type).then(function() {
				location.href = './?f=' + $('#pic-parent-id').val();
			});
		});
	});
});

// Delete Authorization Button
$('.pic-auth-button').click(function() {
	id_auth = $(this).attr('data-auth');
	swal({	title: 'Confirmation.',
			type: 'question',
			text: 'Supprimer définitivement ce gestionnaire ?',
			showCancelButton: true,
			confirmButtonText: 'Oui',
			cancelButtonText: 'Non'
	}).then(function() {
		$.post('ajax_auth_delete.php', {
			f: $('#pic-folder-id').val(),
			id: id_auth
		}, function(data) {
			var response = jQuery.parseJSON(data);
			swal(response.title, response.text, response.type).then(function() {
				location.href = 'folder.php?f=' + $('#pic-folder-id').val();
			});
		});
	});
});

// Add Authorization Button
$('#pic-add-auth').click(function() {
	swal({	title: 'Nouveau gestionnaire.',
			type: 'question',
			input: 'text',
			inputPlaceholder: 'Login du gestionnaire',
			showCancelButton: true,
			confirmButtonText: 'Ajouter',
			cancelButtonText: 'Annuler'
	}).then(function(text) {
		$.post('ajax_auth_add.php', {
			f: $('#pic-folder-id').val(),
			login: text,
		}, function(data) {
			var response = jQuery.parseJSON(data);
			if(response.type == 'success') {
				swal(response.title, response.text, response.type).then(function() {
					location.href = 'folder.php?f=' + response.id;
				});
			} else {
				swal(response.title, response.text, response.type);
			}
		});
	});
});

// Add Button
$('#pic-add').click(function() {
	swal({	title: 'Nouveau sous-dossier.',
			type: 'question',
			input: 'text',
			inputPlaceholder: 'Nom du sous-dossier',
			showCancelButton: true,
			confirmButtonText: 'Créer',
			cancelButtonText: 'Annuler'
	}).then(function(text) {
		$.post('ajax_folder_add.php', {
			f: $('#pic-folder-id').val(),
			name: text,
		}, function(data) {
			var response = jQuery.parseJSON(data);
			if(response.type == 'success') {
				swal(response.title, response.text, response.type).then(function() {
					location.href = './?f=' + response.id;
				});				
			} else {
				swal(response.title, response.text, response.type);								
			}
		});				
	});
});

// Submit form
$('#frm-nommage').submit(function(){
	
	// Check label
	nom_dossier = $('#frm-nom').val();
	if(nom_dossier.trim() == '') {
		$('#pic-nom').addClass('has-error has-feedback');
		$('.form-control-feedback').addClass('glyphicon-remove');
		$('#frm-nom').focus();
	} else {
		
		// Save folder
		$.post('ajax_folder_save.php', {
			f: $('#pic-folder-id').val(),
			nom: $('#frm-nom').val(),
			parent: $('#frm-parent').val()
		}, function(data) {
			var response = jQuery.parseJSON(data);
			swal(response.title, response.text, response.type).then(function() {
				location.href = './?f=' + $('#pic-folder-id').val();
			}, function (dismiss) {
				location.href = './?f=' + $('#pic-folder-id').val();
			});
		});
	}
	return false;
});

// Duplicate description value
$('.pic-dup-description').click(function() {
	txt = $(this).parent().parent().find('input[name="frm-description"]').val();
	$('input[name="frm-description"]').val(txt);
});

// Duplicate keywords value
$('.pic-dup-keywords').click(function() {
	txt = $(this).parent().parent().find('input[name="frm-keywords"]').val();
	$('input[name="frm-keywords"]').val(txt);
});

// Duplicate folder value
$('.pic-dup-folder').click(function() {
	txt = $(this).parent().parent().find('input[name="frm-folder-name"]').val();
	$('input[name="frm-folder-name"]').val(txt);
	value = $(this).parent().parent().find('input[name="frm-folder-value"]').val();
	$('input[name="frm-folder-value"]').val(value);
});

// Picture Save Button
$('.frm-pic-save-button').click(function() {
	pic_root = $(this).parent().parent();
	$.post('ajax_pic_update.php', {
		f: $('#pic-folder-id').val(),
		id: pic_root.find('input[name="frm-pic-id"]').val(),
		label: pic_root.find('input[name="frm-description"]').val(),
		keywords: pic_root.find('input[name="frm-keywords"]').val(),
		folder: pic_root.find('input[name="frm-folder-value"]').val()
	}, function(data) {
		var response = jQuery.parseJSON(data);
		swal(response.title, response.text, response.type);
	});
});
