<?php
	/**
	 * Downloads a file from a group. This works just like a direct download link. However, this link should not be displayed to anyone
	 * except the authenticated user, as the oauthtoken here can be used in other API calls. Note: This method does not require the use of
	 * the OAuth Authorization header.
	 */
	 
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
	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"fileSample\"");

	print_r ($dsf->controller->downloadDocument('syncrum:sharegroup:xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx', 
											  'syncrum:document:xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx'));

?>

	