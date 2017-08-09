// Back Button
$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

// Delete Button
$('#pic-delete').click(function() {
	if(confirm('Supprimer définitivement cette photo ?')) {
		$.post('ajax_pic_delete.php', {
			f: $('#pic-folder-id').val(),
			id: $('#pic-id').val()
		}, function(data) {
			alert('Photo supprimée.');
			location.href = './?f=' + $('#pic-folder-id').val();
		});		
	}
});

// Init datepicker
$('#frm-date').datepicker({
    format: 'dd/mm/yyyy',
    language: 'fr'
});

// Submit form
$('#frm').submit(function(){
	
	// Check label
	description = $('#frm-description').val();
	if(description.trim() == '') {
		$('#pic-description').addClass('has-error has-feedback');
		$('.form-control-feedback').addClass('glyphicon-remove');
		$('#frm-description').focus();
	} else {
		
		// Save picture
		$.post('ajax_pic_save.php', {
			f: $('#pic-folder-id').val(),
			id: $('#pic-id').val(),
			label: $('#frm-description').val(),
			keywords: $('#frm-keywords').val(),
			date: $('#frm-date').val(),
			width: $('#frm-width').val(),
			height: $('#frm-height').val(),
			size: $('#frm-size').val(),
			extension: $('#frm-extension').val(),
			folder: $('#frm-folder').val()
		}, function(data) {
			alert('Informations mises à jour.');
			location.href = './?f=' + $('#frm-folder').val();
		});
	}
	return false;
});
