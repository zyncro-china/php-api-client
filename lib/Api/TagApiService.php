<?php
	include_once "../config.php";
	class TagApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets all the tags in the organization
	 * 
	 * @param tagType The tags type to return. Defaults returns all tag types. {@link TagApiType}
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of tags {@link TagApi}
	 */
		function getTags($tagType = null, $pageNumber = null, $itemsPerPage = null) {

			$method = TAGS . "/";

			$verbmethod = "GET";

			$params = array("tagType" => $tagType,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets all the tags the calling user is following
	 * 
	 * @param tagType The tags type to return. Defaults returns all tag types. {@link TagApiType}
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of tags {@link TagApi}
	 */
		function getTagsForUser($tagType = null, $pageNumber = null, $itemsPerPage = null) {

			$method = TAGS . "/following";

			$verbmethod = "GET";

			$params = array("tagType" => $tagType,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new tags
	 * 
	 * @param name The name of the tag to create
	 * @param tagType The type of the tag {@link TagApiType} to create
	 * @return The created tag {@link TagApi}
	 */
		function createTag($name, $tagType) {

			$method = TAGS . "/";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "tagType" => $tagType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes a tag.
	 */
		function deleteTag($idtag) {

			$method = TAGS . "/$idtag";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * The calling user starts following the indicated tag.
	 */
		function followTag($idtag) {

			$method = TAGS . "/follow/$idtag";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * The calling user stops following the indicated tag.
	 */
		function unfollowTag($idtag) {

			$method = TAGS . "/unfollow/$idtag";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the departments associated with a tag.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of departments {@link DepartmentApi}
	 */
		function getDepartmentsForTag($idtag, $pageNumber = null, $itemsPerPage = null) {

			$method = TAGS . "/$idtag/departments";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>