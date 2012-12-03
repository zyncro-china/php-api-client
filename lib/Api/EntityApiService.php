<?php
	include "../config.php";
	class EntityApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets the short profile information for all the active users. This is called from openfire
	 * 
	 * @param searchText Text to search in the appid. It can be null.
	 * @param firstResult The start range.
	 * @param itemsCount The maximum result of users.
	 * @return List of user profile information {@link ShortUserApi}
	 * @since 4.0
	 */
		function getUsers($searchText = null, $firstResult = null, $itemsCount = null) {

			$method = ENTITY . "/users";

			$verbmethod = "GET";

			$params = array("searchText" => $searchText,
							 "firstResult" => $firstResult,
							 "itemsCount" => $itemsCount);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the users count of all active users. This is called from openfire
	 * 
	 * @return Amount of active users.
	 * @since 4.0
	 */
		function getUsersCount() {

			$method = ENTITY . "/userscount";

			$verbmethod = "GET";

			$params = array();

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the users count of all active users. This is called from openfire
	 * 
	 * @return Amount of active users.
	 * @since 4.0
	 */
		function extends($iduser, $onlyShort = "false") {

			$method = ENTITY . "/users/$iduser";

			$verbmethod = "GET";

			$params = array("onlyShort" => $onlyShort);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>