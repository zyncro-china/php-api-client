<?php
	include "dsf/dsf.php";
	include "oauth.php";
	
	$dsf = new DSF();
	
	/* Configuraciones */
	
	$dsf->configs["controller_var"] = "section";
	
	$dsf->configs["ZYNCRO_URL"]          = "https://" . $_SERVER['HTTP_HOST'];
	$dsf->configs["REQUEST_TOKEN_URL"]   = $dsf->configs["ZYNCRO_URL"] . "/tokenservice/oauth/v1/get_request_token";
	$dsf->configs["AUTHORIZE_URL"] = $dsf->configs["ZYNCRO_URL"] . "/tokenservice/oauth/v1/NoBrowserAuthorization";
	$dsf->configs["ACCESS_TOKEN_URL"]    = $dsf->configs["ZYNCRO_URL"] . "/tokenservice/oauth/v1/get_access_token";	
	$dsf->configs["THREELEGGED_LOGIN_URL"] = $dsf->configs["ZYNCRO_URL"] . "/tokenservice/jsps/login/login.jsp";
	$dsf->configs["CALLBACK_BASE_URL"] = "/zyncroapps/ext";
	
    $dsf->configs["debug_mode"] = true;
    $dsf->configs["API_KEY"]    = ""; 
    $dsf->configs["API_SECRET"] = ""; 
	    
	$dsf->refresh();

	if (!defined('DB_SERVER')) {
		define('OAUTH_PERSISTENCE', 'SESSION'); //DB OR SESSION
		define('DB_SERVER', '');
		define('DB_USER', '');
		define('DB_PWD', '');
		define('DB_NAME', '');
		define('DB_PORT', '');
		
		//////default authentication
		define('LOGIN_TYPE','userpass'); //session, userpass, threelegged
		
		define('ORGID',''); 
		define('EMAIL','');
		define('PASS','');
		//////
				
		define('API_1', "/api/v1/rest");

		define('GROUPS', API_1 . "/groups");
	
		define('DEPARTMENTS', API_1 . "/departments");
	
		define('INVITATIONS', API_1 . "/invitations");
	
		define('USERS', API_1 . "/users");
	
		define('EXTERNAL', API_1 . "/external");
	
		define('ORGANIZATION', API_1 . "/organization");
	
		define('WALL', API_1 . "/wall");
	
		define('TOKENSERVICE', "/oauth/v1");
	
		define('TASKS', API_1 . "/tasks");
	
		define('TAGS', API_1 . "/tags");
	
		define('ACTIONS', API_1 . "/actions");
	
		define('PUSH', API_1 . "/pushservice");
	
		define('ENTITY', API_1 . "/entity");
	
	}
?>