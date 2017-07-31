<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<input type="file" id="uploadfiles" multiple="multiple" accept="image/*" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript">
	
	var uploadfiles = document.querySelector('#uploadfiles');
	uploadfiles.addEventListener('change', function () {
	    var files = this.files;
	    for(var i=0; i<files.length; i++) {
	        uploadFile(this.files[i]); // call the function to upload the file
	    }
	}, false);
	
	function uploadFile(file){
	    var url = '/photos/upload.php';
	    var xhr = new XMLHttpRequest();
	    var fd = new FormData();
	    xhr.open('POST', url, true);
	    xhr.onreadystatechange = function() {
	        if (xhr.readyState == 4 && xhr.status == 200) {
	            // Every thing ok, file uploaded
	            console.log(xhr.responseText); // handle response.
	        }
	    };
	    fd.append('photo_upload', file);
	    xhr.send(fd);
	}
	</script>
</body>
</html>