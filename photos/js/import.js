/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        url: '/photos/ajax-upload.php'
    });

    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');
    $.ajax({
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done')
            .call(this, $.Event('done'), {result: result});
    });

    $('#fileupload').bind('fileuploadcompleted', function (e, data) {
    	var i = 0;
    	for(i = 0; i < data.result.files.length; i++) {
    		$.post('ajax_move2s3.php', {
    			f: $('#pic-folder-id').val(),
    			file: data.result.files[0].name
    		}, function(data) {
    			var response = jQuery.parseJSON(data);
    			if(response.type == 'success') {
    				$('button[data-filename="' + response.file + '"]').html('<i class="glyphicon glyphicon-flag"></i><span>&nbsp;Téléchargé</span>');
    				$('button[data-filename="' + response.file + '"]').removeClass('btn-info').addClass('btn-success');
    			} else {
    				$('button[data-filename="' + response.file + '"]').html('<i class="glyphicon glyphicon-ban-circle"></i><span>&nbsp;Erreur</span>Fait');
    				$('button[data-filename="' + response.file + '"]').removeClass('btn-info').addClass('btn-danger');
    			}
    		});
    	}
    })
});

//Back Button
$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

