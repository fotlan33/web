function LoadContacts() {
	var table = $('#ctc-table').DataTable( {
    	ajax: '/contacts/ajax_list.php',
    	info: false,
    	pagingType: 'simple',
        language: {
            processing:		"Traitement en cours...",
            search:			"Rechercher&nbsp;:",
            lengthMenu:		"Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donn&eacute;e disponible dans le tableau",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        }
    } );
	if(screen.width < 768) {
		$('#ctc-table_length').hide();
		table.column(1).visible(false, false);
		table.column(2).visible(false, false);
		table.column(3).visible(false, false);
		table.columns.adjust().draw(false);		
	}
}

function InitEdition() {
	
	// Init datepicker
    $('#frm-naissance').datepicker({
        format: 'dd/mm/yyyy',
        language: 'fr'
    });
    
    // Init input "nom"
    $('#frm-nom').on('change', function(){
    	$('#ctc-nom-group').removeClass('has-error has-feedback');
		$('.form-control-feedback').removeClass('glyphicon-remove');
    });
    
    // Submit form
    $('#frm').submit(function(){
    	
    	// Check "nom"
    	nom = $('#frm-nom').val();
    	if(nom.trim() == '') {
    		$('#ctc-nom-group').addClass('has-error has-feedback');
    		$('.form-control-feedback').addClass('glyphicon-remove');
    		$('#frm-nom').focus();
    	} else {
    		
    		// Save contact
    		$.post('ajax_save.php', {
    			id: $('#frm-id').val(),
    			titre: $('#frm-titre').val(),
    			prenom: $('#frm-prenom').val(),
    			nom: $('#frm-nom').val(),
    			pseudo: $('#frm-pseudo').val(),
    			naissance: $('#frm-naissance').val(),
    			fonction: $('#frm-fonction').val(),
    			societe: $('#frm-societe').val(),
    			priv_email: $('#frm-priv-email').val(),
    			priv_web: $('#frm-priv-web').val(),
    			priv_tel: $('#frm-priv-tel').val(),
    			priv_gsm: $('#frm-priv-gsm').val(),
    			priv_adresse: $('#frm-priv-adresse').val(),
    			priv_adresse_ext: $('#frm-priv-adresse-ext').val(),
    			priv_cp: $('#frm-priv-cp').val(),
    			priv_ville: $('#frm-priv-ville').val(),
    			priv_pays: $('#frm-priv-pays').val(),
    			pro_email: $('#frm-pro-email').val(),
    			pro_web: $('#frm-pro-web').val(),
    			pro_tel: $('#frm-pro-tel').val(),
    			pro_gsm: $('#frm-pro-gsm').val(),
    			pro_adresse: $('#frm-pro-adresse').val(),
    			pro_adresse_ext: $('#frm-pro-adresse-ext').val(),
    			pro_cp: $('#frm-pro-cp').val(),
    			pro_ville: $('#frm-pro-ville').val(),
    			pro_pays: $('#frm-pro-pays').val(),
    			remarques: $('#frm-remarques').val()
    		}, function(data) {
    			var response = jQuery.parseJSON(data);
    			if(response.err_no == 0) {
    				$('#frm-id').val(response.id);
    				$('#frm-alert').removeClass('alert-danger').addClass('alert-success');
    				$('#frm-alert').text('Contact enregistré !').show('slow').delay(3000).hide('slow');
    			} else {
    				$('#frm-alert').removeClass('alert-success').addClass('alert-danger');
    				$('#frm-alert').text(response.err_text + ' (' + response.err_no + ')').show('slow').delay(3000).hide('slow');
    			}
    		});
    		location.href = "#ctc-bottom";
    	}
    	return false;
    });
}

function InitSynchro() {
	
	// Change sync way
	$('.ctc-way').click(function() {
		var o = $(this).find('i');
		if(o.hasClass('glyphicon-circle-arrow-right'))
			o.removeClass('glyphicon-circle-arrow-right').addClass('glyphicon-circle-arrow-left');
		else
			o.removeClass('glyphicon-circle-arrow-left').addClass('glyphicon-circle-arrow-right');
	});
	
	// Proceed sync
	$('.ctc-action').click(function() {
		$.post('ajax_sync.php', {
			group_id: $('#group-id').val(),
			parent_id: $(this).parent().attr('id'),
			google_id: $(this).find('[name="google-id"]').val(),
			fotlan_id: $(this).find('[name="fotlan-id"]').val(),
			google_value: $(this).find('[name="google-value"]').val(),
			fotlan_value: $(this).find('[name="fotlan-value"]').val(),
			property_name: $(this).find('[name="property-name"]').val(),
			sync_way: ($(this).parent().find('i').hasClass('glyphicon-circle-arrow-right')) ? 'G2F' : 'F2G'
		}, function(data) {
			var response = jQuery.parseJSON(data);
			var oparent = $('#' + response.parent_id);
			if(response.err_no > 0) {
				oparent.find('[class="ctc-action-off"]').removeClass('ctc-action-off').addClass('ctc-action-error');
			} else {
				oparent.remove();
			}
		});
		$(this).removeClass('ctc-action').addClass('ctc-action-off');
	});

}