<?php
	include "../config.php";
	class PushApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
 * Interface for push service
 * 
 * @author Matias Maenza
 * @since 3.5
 */
		function registerToPushService($phoneType = null, $deviceId = null) {

			$method = PUSH . "/register";

			$verbmethod = "POST";

			$params = array("phoneType" => $phoneType,
							 "deviceId" => $deviceId);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
 * Interface for push service
 * 
 * @author Matias Maenza
 * @since 3.5
 */
		function unregisterToPushService($deviceId = null) {

			$method = PUSH . "/unregister";

			$verbmethod = "POST";

			$params = array("deviceId" => $deviceId);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>