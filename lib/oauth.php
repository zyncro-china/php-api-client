<?php
	include_once 'db.php';
	include_once 'config.php';
	
	require_once "oauth/OAuthStore.php";
	require_once "oauth/OAuthRequester.php";
	require_once "oauth/OAuthUtil.php";
	
	class DSF_Library extends DSF_ {
		static function factory(&$dsf,$arguments){
	        $name = $arguments[0];
	
			if (!class_exists($name)){
	            //Recorrer libraries del usuario y public e incluir lo que haya dentro
	        }
	        
	        return new $name($dsf);
	        //return new $name($dsf, $arguments[1]);
		}
	}
	
	class oAuth extends DSF_ {
		protected $requestUrl;
		public $email;
		public $pass;
		public $logintype;
		public $urnsession;
		public $orgid;
		public $token;
		
	    //function __construct(&$DSF, &$params){
	    function __construct(&$DSF){
	        parent::DSF_($DSF);
	    }
	    
	    function getStore(){
		    $options = array(
	            'consumer_key' => $this->dsf->configs["zyncroapi_consumer_key"],
	            'consumer_secret' => $this->dsf->configs["zyncroapi_consumer_secret"],
	            'server_uri' => $this->dsf->configs["zyncroapi_oauth_host"],
	            'request_token_uri' => $this->dsf->configs["zyncroapi_oauth_request_url"],
	            'authorize_uri' => $this->dsf->configs["zyncroapi_oauth_authorize_url"],
	            'zyncroapi_oauth_threelegged_login' => $this->dsf->configs["zyncroapi_oauth_threelegged_login"],
	            'zyncroapi_oauth_callback_base' => $this->dsf->configs["zyncroapi_oauth_callback_base"],
	            'access_token_uri' => $this->dsf->configs["zyncroapi_oauth_access_url"],
	        	'DB_SERVER' => $this->dsf->configs['DB_SERVER'],
				'DB_USER' => $this->dsf->configs['DB_USER'],
				'DB_PWD' => $this->dsf->configs['DB_PWD'],
				'DB_NAME' => $this->dsf->configs['DB_NAME'],
				'DB_PORT' => $this->dsf->configs['DB_PORT'],
				'zyncrouser' => $this->dsf->configs['zyncrouser']
	        );
	        
	        
	        return ZyncroOAuthStore::instance("MySQLi", $options);
	    }
		
		function callApiDirect($service, $params = array(), $type = 'GET', $ForceNewToken = false){
			try {
				$currentservice = $service;
				$this->getAccessToken($ForceNewToken);	
				 
				$service = str_replace('@oauthtoken', $this->token, $service);
		    		
		    	$urlRequest = $this->dsf->configs['ZYNCRO_URL'] . $service;
				
				if ($type == 'GET'){
					$params_s = '?';
					$params_s .= http_build_query($params);
					
					$ch = curl_init($urlRequest . $params_s);
					
					curl_setopt($ch, CURLOPT_POST, false);
				} else {
					$ch = curl_init($urlRequest);
					
					curl_setopt($ch, CURLOPT_POST, true);	
					curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				}
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				 
				$response = curl_exec($ch);
				$query_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				
				curl_close($ch);
				
				if ($query_response_code>300)
				{
					throw new Exception("Error code $query_response_code");
				}	
				return $response;
			} catch (Exception $e){				
				if (!$ForceNewToken){
			    	//try to get a new access token			    	
			        return $this->callApiDirect( $currentservice, $params, $type, true);
		        } else {
			        throw new Exception("Error calling api direct " . $e->getMessage());
		        }
			}
		}
		
		function callAPI( $service, $params = array(), $type = 'GET', $ForceNewToken = false)
	    {
	    	try{	    		
	    		$this->getAccessToken($ForceNewToken);	
	    		
		    	$urlRequest = $this->dsf->configs['ZYNCRO_URL'] . $service;
		    	
		    	if( $type == 'GET' )
		        {
		            $params_s = '?' . http_build_query($params);
		            $request = new OAuthRequester( $urlRequest . $params_s, $type );
		        }
		        else if ( $type == 'POST' )
		        {
		            $request = new OAuthRequester( $urlRequest, $type, OAuthUtil::build_http_body( $params ) );
		        }
		        else
		        {
		            $request = new OAuthRequester( $urlRequest, $type );
		            $type = 'GET';
		        }
		        
		        $result = $request->doRequest(0);	        
		        
		        if ( $result[ 'code' ] == 200)
		        {
		        	return $result['body'];	        	
		        } 
		        else if ($result[ 'code' ] == 100) 
		        {
			        $result = my_curl_parse ( $result['body'] );
			        
			        if ($result[ 'code' ] == 200)
			        {
			        	return $result['body'];	  			        
			        } else {
				        throw new Exception("OAuth Error " . $result['body']);
			        }
		        }
		        
	    	} catch (OAuthException2 $e){    		
	    		if (!$ForceNewToken){
			    	//try to get a new access token
			        return $this->callAPI( $service, $params, $type, true);
		        } else {
			        throw new Exception("Error authorizing OAuth " . $e->getMessage());
		        }
	    	}
	    	
	    }
    
		function getAccessToken($forceNewToken = false)
		{
			try 
			{
				$pass = '';
				$orgid = '';
				$tipo = '';
				
				if (OAUTH_PERSISTENCE == 'DB') {
					if (empty($this->orgid) || empty($this->email)){
						if (ORGID == '' || EMAIL == '') {
							throw new Exception('orgid and email must be set when persistence mode is database');
							return;	
						} else {
							$orgid = ORGID;
							$email = EMAIL;							
						}
					} else {
						$orgid = $this->orgid;
						$email = $this->email;
					}
				}
				
				if (empty($this->logintype)) {
					if (LOGIN_TYPE == ''){
						$tipo = 'session';
					} else {
						$tipo = LOGIN_TYPE;
					}					
				} else {
					$tipo = $this->logintype;
				}
				
				//  Init the OAuthStore
				$options = array(
					'consumer_key' => $this->dsf->configs['API_KEY'], 
					'consumer_secret' => $this->dsf->configs['API_SECRET'],
					'server_uri' => $this->dsf->configs['ZYNCRO_URL'],
					'request_token_uri' => $this->dsf->configs['REQUEST_TOKEN_URL'],
					'authorize_uri' => $this->dsf->configs['AUTHORIZE_URL'],
					'access_token_uri' => $this->dsf->configs['ACCESS_TOKEN_URL'],
					'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
				);
			
				$exception = null;
				
				$store = OAuthStore::instance("Session", $options);
				
				if (OAUTH_PERSISTENCE == 'DB') {
					// get current access token
					$db = new oAuthDB();					
					$db->orgid = $orgid;
					$db->email = $this->email;
					
					$data = $db->getToken();		
				} else {
					$db = new oAuthDB();
					
					if (!empty($_SESSION['oauth_'.$this->dsf->configs['API_KEY']]['token']) || !empty($_SESSION['oauth_'.$this->dsf->configs['API_KEY']]['token_secret'])) {
						$db->token = $_SESSION['oauth_'.$this->dsf->configs['API_KEY']]['token'];
						$db->token_secret = $_SESSION['oauth_'.$this->dsf->configs['API_KEY']]['token_secret'];					
					} else {
						$db->token = "";
						$db->token_secret = "";
					}
				}
				////////////
				
				if ($db->token != "" && $db->token_secret != "" && !$forceNewToken){	
					$this->token = $db->token;					
					$store->addServerToken($this->dsf->configs['API_KEY'], 'access', $db->token, $db->token_secret, 0);	
				} else {			
					//if there are no current accesstoken get a new one
				 	if ($tipo == 'session') {
				 		if (empty($this->urnsession)){
					 		throw new Exception ('urnsession must be set when login type is session');
					 		return;
				 		}
				 		$token = OAuthRequester::requestRequestToken($this->dsf->configs['API_KEY'], 0, array());
				 		$requestToken = $token['token'];
				 		
						$getAccessTokenParams  = array( 'oauth_verifier' => $this->urnsession );				
						OAuthRequester::requestAccessToken( $this->dsf->configs['API_KEY'], $requestToken , $email, 'POST', $getAccessTokenParams );
						
					} elseif ($tipo == 'userpass') {
						if (empty($this->pass)){
							if (PASS == '') {
								throw new Exception ('pass must be set when login type is userpass');
								return;	
							} else {
								$pass = base64_decode(PASS);
							}					 		
				 		} else {
					 		$pass = $this->pass;	
				 		}
				 		
						$token = OAuthRequester::requestRequestToken($this->dsf->configs['API_KEY'], 0, array());
						$requestToken = $token['token'];
						
						$this->authorizeToken($this->email, $pass , $requestToken);
						OAuthRequester::requestAccessToken($this->dsf->configs['API_KEY'], $requestToken, $this->email, 'POST', NULL);
						
					} else {
						//three legged		
						if (isset($_REQUEST['oauth_token'])){
							try {
								OAuthRequester::requestAccessToken($this->dsf->configs['API_KEY'], $_REQUEST['oauth_token'], $this->email, 'POST', NULL);	
							} catch (OAuthException2 $e){								
								$this->getRequestTokenCallback();								
							}
						} else {
							$this->getRequestTokenCallback();							
						}
					}
					
					$db->token = $_SESSION['oauth_'.$this->dsf->configs['API_KEY']]['token'];
					$db->token_secret = $_SESSION['oauth_'.$this->dsf->configs['API_KEY']]['token_secret'];
					
					$this->token = $db->token;	
					//save access token in db					
					$db->saveToken();		
				}				
			}
			catch (OAuthException2 $e)
			{			
			    throw new Exception("Error authorizing OAuth " . $e->getMessage());
			}					
		}
		
		function authorizeToken($user, $pass, $requestToken) 
		{		
			try 
			{
				$data = "username=".$user."&password=".$pass."&request_token=".$requestToken;				
				$result = OAuthUtil::do_post_request($this->dsf->configs['AUTHORIZE_URL'], $data);
				
				return true;			
			}
			catch (Exception $e)
			{			
				return false;
			}		
		}
		
		function getRequestTokenCallback()
	    {
	    	
        	$url_callback = $this->dsf->configs['CALLBACK_BASE_URL'] . $_SERVER['REQUEST_URI']; 
        	$url_callback = $this->remove_querystring($url_callback,'oauth_token');
        	$url_callback = $this->remove_querystring($url_callback,'oauth_userid');
        	$url_callback = $this->remove_querystring($url_callback,'oauth_lang');
        	
        	$getRequestTokenParams  = array( 'oauth_callback' => $url_callback );

        	$token = OAuthRequester::requestRequestToken($this->consumer_key, $this->email, $getRequestTokenParams);
            
			$requestToken = $token['token'];
			
			$authorize_threelegged_login = $this->dsf->configs['THREELEGGED_LOGIN_URL'] . '?oauth_token='.rawurlencode($token['token']) ."&oauth_callback=" . rawurlencode($url_callback);
			
			header("Location: " . $authorize_threelegged_login);
	    }
	    
	    function remove_querystring($url, $key) { 
			$pattern = '/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i';
			$limit = -1;
			$count = 0;
			return preg_replace ($pattern, '$1', $url, $limit, $count);			
		}
		
		function my_curl_parse ($response)
		{
			if (empty($response))
			{
				return array();
			}
		
			@list($headers,$body) = explode("\r\n\r\n",$response,2);
			$lines = explode("\r\n",$headers);
	
			if (preg_match('@^HTTP/[0-9]\.[0-9] +100@', $lines[0]))
			{
				/* HTTP/1.x 100 Continue
				 * the real data is on the next line
				 */
				@list($headers,$body) = explode("\r\n\r\n",$body,2);
				$lines = explode("\r\n",$headers);
			}
		
			// first line of headers is the HTTP response code 
			$http_line = array_shift($lines);
			if (preg_match('@^HTTP/[0-9]\.[0-9] +([0-9]{3})@', $http_line, $matches))
			{
				$code = $matches[1];
			}
		
			// put the rest of the headers in an array
			$headers = array();
			foreach ($lines as $l)
			{
				list($k, $v) = explode(': ', $l, 2);
				$headers[strtolower($k)] = $v;
			}
		
			return array( 'code' => $code, 'headers' => $headers, 'body' => $body);
		}
	}
?>