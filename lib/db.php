<?php
	include_once 'config.php';
	
	class oAuthDB {
		public $token;
		public $token_secret;
		public $email;
		public $orgid;
		
		public function getDBConn(){			
			return new mysqli(DB_SERVER,DB_USER,DB_PWD,DB_NAME,DB_PORT);
		}
		
		public function getToken(){
			try {
				$oAuthDB = $this->getDBConn();
				
				$data = array();
				
				if ($oAuthDB->errno != 0) {
					exit();
				}
				
				$res = $oAuthDB->query(
					"CALL SP_ZAPI_GET_TOKEN ('$this->orgid', '$this->email')"
				);
				
				if ($res != FALSE){
					$data = $res->fetch_assoc();
					$this->token = $data['TOKEN'];
					$this->token_secret = $data['TOKEN_SECRET'];
				}		
				
				//$res->close();		
				$oAuthDB->close();							
			} catch (Exception $e){
				$this->token = '';
			}			
		}
	
		public function saveToken (){
			try {
				$oAuthDB = $this->getDBConn();
				
				$res = $oAuthDB->query(
					"CALL SP_ZAPI_SAVE_TOKEN ('$this->orgid', '$this->email', '$this->token', '$this->token_secret')"
				);
				
				return $res;
		
				$oAuthDB->close();	
			} catch (Exception $e){
				return FALSE;				
			}
		}
	}
?>