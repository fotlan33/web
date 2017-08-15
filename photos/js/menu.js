$(document).ready(function() {

	// Parent folder menu
	$('#pic-menu-parent.pic-menu').click(function() {
		location.href = './?f=' + $('#pic-parent-id').val();
	});

	// Subfolders menu
	$('#pic-menu-subfolders.pic-menu').click(function() {
		if($('#pic-subfolders').is(':hidden')) {
			$('#pic-subfolders').show('slow');
			$('.pic-subfolders-tip').hide('fast');
		} else {
			$('#pic-subfolders').hide('slow');
			$('.pic-subfolders-tip').show('fast');
		}
	});

	// Search menu
	$('#pic-menu-search.pic-menu').click(function() {
		location.href = 'search.php?f=' + $('#pic-folder-id').val();
	});

	// Import menu
	$('#pic-menu-import.pic-menu').click(function() {
		location.href = 'import.php?f=' + $('#pic-folder-id').val();
	});

	// Edit menu
	$('#pic-menu-edit.pic-menu').click(function() {
		location.href = 'folder.php?f=' + $('#pic-folder-id').val();
	});

});