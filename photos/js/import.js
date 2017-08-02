var uploadfiles = document.querySelector('#pic-uploadfiles');
uploadfiles.addEventListener('change', function () {
    var files = this.files;
    $('.pic-transfer').html('téléchargements en cours...<br />');
    for(var i=0; i<files.length; i++) {
        uploadFile(this.files[i]);
    }
}, false);
	
function uploadFile(file){
    var url = 'ajax_upload.php';
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open('POST', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
        	$('.pic-transfer').html($('.pic-transfer').html() + xhr.responseText);
            // Every thing ok, file uploaded
            //console.log(xhr.responseText); // handle response.
        }
    };
    fd.append('f', $('#pic-folder-id').val());
    fd.append('upload_file', file);
    xhr.send(fd);
}