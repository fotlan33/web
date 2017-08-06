var nImport = 0;
var iImport = 0;

var uploadfiles = document.querySelector('#pic-uploadfiles');
uploadfiles.addEventListener('change', function () {
    var files = this.files;
    $('.pic-transfer').html('Téléchargements en cours. Merci de patienter...<br />');
	$('.progress-bar').css('width', '0%').attr('aria-valuenow', 0).html('0%');
    nImport = files.length;
    iImport = 0;
    for(var i=0; i<files.length; i++) {
        uploadFile(this.files[i]);
    }
}, false);

$('#pic-back').click(function() {
	location.href = './?f=' + $('#pic-folder-id').val();
});

function uploadFile(file){
    var url = 'ajax_upload.php';
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open('POST', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
        	iImport++;
        	valeur = Math.round(iImport * 100 / nImport);
        	$('.pic-transfer').html($('.pic-transfer').html() + xhr.responseText);
        	$('.progress-bar').css('width', valeur + '%').attr('aria-valuenow', valeur).html(valeur + '%');
        	if(iImport == nImport)
        		location.href = './?f=' + $('#pic-folder-id').val();
        }
    };
    fd.append('f', $('#pic-folder-id').val());
    fd.append('pic-uploadfile', file);
    xhr.send(fd);
}