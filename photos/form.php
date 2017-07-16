<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<input type="file" id="fileinput" multiple="multiple" accept="image/*" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript">
	var uploadfiles = document.querySelector('#uploadfiles');
	uploadfiles.addEventListener('change', function () {
	    var files = this.files;
	    for(var i=0; i<files.length; i++){
	        uploadFile(this.files[i]); // call the function to upload the file
	    }
	}, false);
	</script>
</body>
</html>