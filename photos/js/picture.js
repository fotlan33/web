// Back Button
$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

// Delete Button
$('#pic-delete').click(function() {
	swal({	title: 'Confirmation.',
			type: 'question',
			text: 'Supprimer définitivement cette photo ?',
			showCancelButton: true,
			confirmButtonText: 'Oui',
			cancelButtonText: 'Non'
	}).then(function() {
		$.post('ajax_pic_delete.php', {
			f: $('#pic-folder-id').val(),
			id: $('#pic-id').val()
		}, function(data) {
			var response = jQuery.parseJSON(data);
			swal(response.title, response.text, response.type).then(function() {
				location.href = './?f=' + $('#pic-folder-id').val();
			}, function (dismiss) {
				location.href = './?f=' + $('#pic-folder-id').val();
			});
		});						
	});
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
			folder: $('#frm-folder-value').val()
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
