// Back Button
$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

// Delete Button
$('#pic-delete').click(function() {
	if(confirm('Supprimer définitivement cette photo ?')) {
		$.post('ajax_pic_delete.php', {
			id: $('#pic-id').val(),
			f: $('#pic-folder-id').val()
		}, function(data) {
			location.href = './?f=' + $('#pic-folder-id').val();
		});		
	}
});

// Init datepicker
$('#frm-date').datepicker({
    format: 'dd/mm/yyyy',
    language: 'fr'
});

