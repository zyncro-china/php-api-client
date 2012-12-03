<?php
	include "../config.php";
	class ActionApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

		function getActionsFromSequence($idgroup = null, $sequenceNumber = null, $pageNumber = null, $pageSize = null) {

			$method = ACTIONS . "/fromsequence";

			$verbmethod = "GET";

			$params = array("idgroup" => $idgroup,
							 "sequenceNumber" => $sequenceNumber,
							 "pageNumber" => $pageNumber,
							 "pageSize" => $pageSize);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>