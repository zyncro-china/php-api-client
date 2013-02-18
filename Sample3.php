<?php
	/**
	 * Uploads a new file to a group (Multipart HTTP POST request). Note: This method does not require the use of the OAuth Authorization
	 * header.
	 * 
	 * @param file The file to upload.
	 * @param fileName The name of the new file to upload.
	 * @param length The size of the file to upload in bytes.
	 * @param iddocument ID of an existing document, if a new version of that document wants to be uploaded.
	 * @param description Text to add as a description for the file. Default is empty.
	 * @param parentDocumentUrn ID of the folder of the group where the new file will be uploaded. If it is not set, the new file will be
	 *            uploaded to the root of the group.
	 * @return Response of the upload {@link UploadDocumentResultApi}
	 * @since 3.5
	 */
	
	if (!empty($_FILES)){ 
		include_once "lib/config.php";
		include_once "lib/Api/GroupApiService.php";
			
		$dsf->start(new GroupApiService($dsf));
		
		function loginByUserPass($email,$pass){
			global $dsf;
			
			$dsf->controller->zyncroApi->email = $email;
			$dsf->controller->zyncroApi->pass = $pass;
			$dsf->controller->zyncroApi->logintype = 'userpass';	
		}
		
		function loginBySession($urnsession){
			global $dsf;
			
			$dsf->controller->zyncroApi->urnsession = $urnsession;
			$dsf->controller->zyncroApi->logintype = 'session';			
		}
		
		function loginByThreelegged(){
			global $dsf;
			
			$dsf->controller->zyncroApi->logintype = 'threelegged';	
		}
	
		loginByUserPass('email@domain.xx','password');
		
		$FileSample = "@{$_FILES["filesample"]["tmp_name"]};type={$_FILES["filesample"]["type"]}";
		$Filelength = filesize($_FILES['filesample']['tmp_name']);
			
		print_r ($dsf->controller->upload('syncrum:sharegroup:xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx', 
										  $_REQUEST['filename'],
										  $Filelength, 
										  $FileSample,
										  null,
										  null,
										  $_REQUEST['filedescription']
										  ));
	}
	
?>

<html>
	<head>
		<script type="text/javascript" language="javascript">
			window.onload = function () {
				document.getElementById("frmSample").action = document.location.href;					
			}
		</script>
	</head>
	<body>
		<form enctype="multipart/form-data" id="frmSample" method="POST">
		    File: <input name="filesample" type="file" /><br/>
		    File name: <input name="filename" type="text" value="file sample" /><br/>
		    File description: <input name="filedescription" type="text" value="file sample description" /><br/>
		    <input type="submit" value="Send File" />
		</form>
	</body>
</html>