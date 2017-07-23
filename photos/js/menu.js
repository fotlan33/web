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

});