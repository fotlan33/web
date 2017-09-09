folder_name = null;
folder_value = null;

$('.pic-folder-name').focus(function() {
	folder_name = $(this);
	folder_value = $(this).parent().parent().find('input[data-pic="folder"]');
	$.post('ajax_folder_list.php', {
		f: folder_value.val(),
	}, function(data) {
		$('#pic-folder-list').html(data);
		$('#pic-folder-selector').modal('show');
	});
});

$('#pic-folder-list').on('click', 'div.folder-item', function() {
	$.post('ajax_folder_list.php', {
		f: $(this).attr('data-id'),
	}, function(data) {
		$('#pic-folder-list').html(data);
	});
});

$('#pic-folder-select').click(function() {
	folder_name.val($('#folder-selection').html());
	folder_value.val($('.folder-selected').attr('data-id'));
	$('#pic-folder-selector').modal('hide');
});
