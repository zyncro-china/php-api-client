<?php
	include "../config.php";
	class ExternalApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

		function get($service = null) {

			$method = EXTERNAL . "/";

			$verbmethod = "GET";

			$params = array("service" => $service);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

		function post($service = null) {

			$method = EXTERNAL . "/";

			$verbmethod = "POST";

			$params = array("service" => $service);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

		function delete($service = null) {

			$method = EXTERNAL . "/";

			$verbmethod = "DELETE";

			$params = array("service" => $service);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

}
?>