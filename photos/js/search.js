// Back Button
$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

//Init datepicker
$('.pic-date').datepicker({
    format: 'dd/mm/yyyy',
    language: 'fr'
});

