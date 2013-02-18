<?php
	include_once "../config.php";
	class UserApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets the list of all contacts for the user, including internal contacts (those users that are in the same organization) and external
	 * users (contacts from other organizations). This filter can be changed using the parameter "type" in the request.
	 * 
	 * @param pageNumber Page to return. Default set to 1
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param type Type of contact users to return. Default returns all contacts. {@link UserFilterApiType}
	 * @return A list of contact users {@link UserApi}
	 */
		function getContacts($type = "0", $pageNumber = null, $itemsPerPage = null) {

			$method = USERS . "/";

			$verbmethod = "GET";

			$params = array("type" => $type,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits the information about the user.
	 * 
	 * @param department Department of the user.
	 * @param departmentVisibility Visibility for the department of the user.
	 * @param country Country of the user.
	 * @param province Province of the user.
	 * @param city City of the user.
	 * @param timezone Timezone.
	 * @param dateFormat Date format.
	 * @param telephone1 First work telephone's user.
	 * @param ext1 First extension for telephone's user.
	 * @param telephone1Visibility Visibility of first telephone's user.
	 * @param telephone2 Secondary work telephone's user.
	 * @param ext2 Secondary extension for telephone's user.
	 * @param telephone2Visibility Visibility of secondary telephone's user.
	 * @param mobile Mobile's user.
	 * @param mobileVisibility Visibility of mobile's user.
	 * @param address Address of the user.
	 * @param website Website of the user.
	 * @param skype Skype name of the user.
	 * @param skypeShowIfConnected Shows status of skype user.
	 * @param others Others.
	 * @param skills Skills.
	 * @param experience Experience.
	 * @param education Education.
	 * @param emailVisibility Email visibility.
	 * @since 4.0
	 */
		function editUser($department = null, $departmentVisibility = null, $country = null, $province = null, $city = null, $timezone = null, $dateFormat = null, $telephone1 = null, $ext1 = null, $telephone1Visibility = null, $telephone2 = null, $ext2 = null, $telephone2Visibility = null, $mobile = null, $mobileVisibility = null, $address = null, $website = null, $skype = null, $skypeShowIfConnected = null, $others = null, $skills = null, $experience = null, $education = null, $emailVisibility = null) {

			$method = USERS . "/";

			$verbmethod = "POST";

			$params = array("department" => $department,
							 "departmentVisibility" => $departmentVisibility,
							 "country" => $country,
							 "province" => $province,
							 "city" => $city,
							 "timezone" => $timezone,
							 "dateFormat" => $dateFormat,
							 "telephone1" => $telephone1,
							 "ext1" => $ext1,
							 "telephone1Visibility" => $telephone1Visibility,
							 "telephone2" => $telephone2,
							 "ext2" => $ext2,
							 "telephone2Visibility" => $telephone2Visibility,
							 "mobile" => $mobile,
							 "mobileVisibility" => $mobileVisibility,
							 "address" => $address,
							 "website" => $website,
							 "skype" => $skype,
							 "skypeShowIfConnected" => $skypeShowIfConnected,
							 "others" => $others,
							 "skills" => $skills,
							 "experience" => $experience,
							 "education" => $education,
							 "emailVisibility" => $emailVisibility);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of users that the indicated user is following.
	 * 
	 * @param pageNumber Page to return. Default set to 1
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param followType The follow type of users to return. Default returns all types. {@link UserFollowApiType}
	 * @return A list of contact users {@link UserApi}
	 */
		function getFollowing($iduser, $followType = "2", $pageNumber = null, $itemsPerPage = null) {

			$method = USERS . "/$iduser/following";

			$verbmethod = "GET";

			$params = array("followType" => $followType,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of users that are following the indicated user.
	 * 
	 * @param pageNumber Page to return. Default set to 1
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param followType The follow type of users to return. Default returns all types. {@link UserFollowApiType}
	 * @return A list of contact users {@link UserApi}
	 */
		function getFollowedBy($iduser, $followType = "2", $pageNumber = null, $itemsPerPage = null) {

			$method = USERS . "/$iduser/followedby";

			$verbmethod = "GET";

			$params = array("followType" => $followType,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the profile information for a user.
	 * 
	 * @param onlyShort Determines whether short or complete profile information is returned. Default is false and returns complete profile.
	 * @return User profile information {@link UserApi}
	 */
		function getUser($iduser, $onlyShort = "false") {

			$method = USERS . "/$iduser";

			$verbmethod = "GET";

			$params = array("onlyShort" => $onlyShort);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the full or short profile information for the logged user.
	 * 
	 * @param onlyShort Determines whether short or complete profile information is returned. Default is false and returns complete profile.
	 * @return User profile information {@link UserApi}
	 */
		function getProfile($onlyShort = "false") {

			$method = USERS . "/profile";

			$verbmethod = "GET";

			$params = array("onlyShort" => $onlyShort);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the full or short profile information for a list of ID of users.
	 * 
	 * @param users comma-separated list of ID of users or emails to be returned.
	 * @param onlyShort Determines whether short or complete profile information is returned. Default is false and returns complete profile.
	 * @return User profile information {@link UserApi}
	 */
		function getUsersById($onlyShort = "false", $users = null) {

			$method = USERS . "/profiles";

			$verbmethod = "GET";

			$params = array("onlyShort" => $onlyShort,
							 "users" => $users);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Searches in the user's contacts for a specific text.
	 * 
	 * @param text Text to search matching the name and last name of the user's contacts.
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10
	 * @param type Type of contact users to return. {@link UserFilterApiType} Default returns all contacts. {@since 3.4}
	 * @param onlyShort Determines whether short or complete profile information is returned. Default is true and returns small profile info. {@since 4.0}
	 * @return A list of users {@link UserApi} matching the search.
	 */
		function searchContacts($onlyShort = "true", $text = null, $pageNumber = null, $itemsPerPage = null, $type = null) {

			$method = USERS . "/searchcontacts";

			$verbmethod = "GET";

			$params = array("onlyShort" => $onlyShort,
							 "text" => $text,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "type" => $type);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * The calling user starts following the indicated user.
	 * 
	 * @param followType The Follow type to follow. {@link UserFollowApiType}
	 * @return The followState in the response, may be "1" meaning the process was successful, or "2" that means a following invitation has
	 *         been sent to the user (depends on the target user configuration).
	 */
		function followUser($iduser, $followType) {

			$method = USERS . "/$iduser/follow";

			$verbmethod = "POST";

			$params = array("followType" => $followType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * The calling user stops following the indicated user.
	 * 
	 * @param followType The Follow type to unfollow. Default unfollow all types. {@link UserFollowApiType}
	 */
		function unfollowUser($iduser, $followType = "2") {

			$method = USERS . "/$iduser/unfollow";

			$verbmethod = "POST";

			$params = array("followType" => $followType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Uploads a new profile image for the user.
	 * 
	 * @param file The file to upload (valid files are png, gif, jpeg)
	 * @param length The size of the file to upload in bytes.
	 * @since 3.5
	 */
		function uploadProfileImage($length, $file) {

			$method = USERS . "/uploadimage/@oauthtoken";

			$verbmethod = "POST";

			$params = array("length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 *
	 */
		function getShortContacts($type = "0", $pageNumber = null, $itemsPerPage = null) {

			$method = USERS . "/small";

			$verbmethod = "GET";

			$params = array("type" => $type,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Obtains a list of associated users.
	 * 
	 * @return A list of associated users. {@link AssociateUserApi}
	 * @since 4.0
	 */
		function getAssociatedUsers() {

			$method = USERS . "/associated";

			$verbmethod = "GET";

			$params = array();

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Associate users from diferent organizations.
	 * 
	 * @param email email of the new associated user.
	 * @param password password of the new associated user.
	 * @since 4.0
	 */
		function associateUser($email, $password) {

			$method = USERS . "/associate";

			$verbmethod = "POST";

			$params = array("email" => $email,
							 "password" => $password);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of the Apps for the logged user.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param availabilityType Availability Type. Default set to 0. {@link AppAvailabilityApiType}
	 * @return A list of apps {@link AppApi}
	 * @since 4.0
	 */
		function getApps($availabilityType = "0", $pageNumber = null, $itemsPerPage = null) {

			$method = USERS . "/apps";

			$verbmethod = "GET";

			$params = array("availabilityType" => $availabilityType,
							 "pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>