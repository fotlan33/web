function LoadContacts() {
    $('#ctc-table').DataTable( {
    	ajax: '/contacts/ajax_list.php',
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
    	}
    	return false;
    });
}