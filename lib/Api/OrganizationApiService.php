<?php
	include_once "../config.php";
	class OrganizationApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets the information about the organization to which this user belongs.
	 * 
	 * @return A organization {@link OrganizationApi}
	 */
		function getOrganization() {

			$method = ORGANIZATION . "/";

			$verbmethod = "GET";

			$params = array();

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits the information about the organization to which this user belongs.
	 * 
	 * @param organizationName Name of the organization
	 * @param backgroundColor Background color for the header
	 * @param textColor Text color for the header
	 * @param loginPage Type of login page
	 * @param url URL of the personalized login page
	 * @param subdomain Subdomain within Zyncro
	 * @param zlinksFooter Hide Zyncro links and logo in your organization's Zlinks
	 * @param canCreateZlink Allow users to create direct links (Zlinks)
	 * @param contactInvitation Invitations to contacts {@link ContactInvitationFilterApiType}
	 * @param canPublicSearch List in public search
	 * @param organizationStructure Organization structure {@link OrganizationStructureApiType}
	 * @param canAddUser Company/department owners can add users to their company/department.
	 * @param canDeleteMessages Company/department owners can delete messages and comments from their company/department.
	 * @param canChangeNames Company/department owners can change their company/department names.
	 * @param canHideMembers Allow group/department owners to hide the members list
	 * @param canDeleteMessagesInGroup Group owners can delete messages
	 * @param allowHideParticipants Allow group owners to hide members list
	 * @param hideTask Hide task functionality
	 * @param allowComments All users can comment on corporate feed, not just corporate users.
	 * @param allowPersonalFeed Users will have their own personal feed to publish their activity in the organization
	 * @param allowSeeNews Allow users in your organization to see Zyncro news.
	 * 
	 * @since 4.0
	 */
		function editOrganization($organizationName = null, $backgroundColor = null, $textColor = null, $loginPage = null, $url = null, $subdomain = null, $zlinksFooter = null, $canCreateZlink = null, $contactInvitation = null, $canPublicSearch = null, $organizationStructure = null, $canAddUser = null, $canDeleteMessages = null, $canChangeNames = null, $hideMembers = null, $canDeleteMessagesInGroup = null, $hideParticipants = null, $hideTask = null, $allowComments = null, $havePersonalFeed = null, $allowSeeNews = null) {

			$method = ORGANIZATION . "/";

			$verbmethod = "POST";

			$params = array("organizationName" => $organizationName,
							 "backgroundColor" => $backgroundColor,
							 "textColor" => $textColor,
							 "loginPage" => $loginPage,
							 "url" => $url,
							 "subdomain" => $subdomain,
							 "zlinksFooter" => $zlinksFooter,
							 "canCreateZlink" => $canCreateZlink,
							 "contactInvitation" => $contactInvitation,
							 "canPublicSearch" => $canPublicSearch,
							 "organizationStructure" => $organizationStructure,
							 "canAddUser" => $canAddUser,
							 "canDeleteMessages" => $canDeleteMessages,
							 "canChangeNames" => $canChangeNames,
							 "hideMembers" => $hideMembers,
							 "canDeleteMessagesInGroup" => $canDeleteMessagesInGroup,
							 "hideParticipants" => $hideParticipants,
							 "hideTask" => $hideTask,
							 "allowComments" => $allowComments,
							 "havePersonalFeed" => $havePersonalFeed,
							 "allowSeeNews" => $allowSeeNews);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new corporate user in a Zyncro organization.
	 * 
	 * @param name Name of the new user.
	 * @param lastname Last name of the new user.
	 * @param email Email of the new user.
	 * @param password Password of the new user. If it is not set, an email will be sent to the new user so that they can set their
	 *            password.
	 * @param storage Storage for the new user, in bytes.
	 * @param canPublishCorporateFeed Determines whether the new user can publish on the Company feed. Default is false.
	 * @param canCreateGroups Determines whether the new user can create Groups. Default is false.
	 * @param canCreateDepartments Determines whether the new user can create Departments. Default is false.
	 * @param isAdministrator Determines whether the new user is Admin of the organization. Default is false.
	 * @param lang Default language the user will use. If it is not set, the default language of the organization will be used
	 *            {@link LanguageApiType}
	 * @param attributes Additional attributes to store in the user. They must be in JSON format.
	 */
		function addUser($name = null, $lastname = null, $email = null, $password = null, $storage = null, $canPublishCorporateFeed = "false", $canCreateGroups = "false", $canCreateDepartments = "false", $isAdministrator = "false", $lang = null, $attributes = null) {

			$method = ORGANIZATION . "/users";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "lastname" => $lastname,
							 "email" => $email,
							 "password" => $password,
							 "storage" => $storage,
							 "canPublishCorporateFeed" => $canPublishCorporateFeed,
							 "canCreateGroups" => $canCreateGroups,
							 "canCreateDepartments" => $canCreateDepartments,
							 "isAdministrator" => $isAdministrator,
							 "lang" => $lang,
							 "attributes" => $attributes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Changes the user password. Only organization admins can change passwords.
	 * 
	 * @param password New password.
	 */
		function changePassword($password = null, $iduser) {

			$method = ORGANIZATION . "/users/$iduser/password";

			$verbmethod = "POST";

			$params = array("password" => $password);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits the information of a corporate user of a Zyncro organization.
	 * 
	 * @param name Changes the name of the user.
	 * @param lastname Changes the last name of the user.
	 * @param email Changes the email of the user. {@since 3.4}
	 * @param area Changes the area of the user.
	 * @param position Changes the position of the user.
	 * @param telephone1 Changes the telephone number 1 of the user.
	 * @param telephone1ext Changes the extension of telephone number 1 of the user.
	 * @param telephone2 Changes the telephone number 2 of the user.
	 * @param telephone2ext Changes the extension of telephone number 2 of the user.
	 * @param mobile Changes the mobile information of the user.
	 * @param address Changes the address information of the user.
	 * @param skype Changes the skype login information of the user.
	 * @param other Changes the additional information of the user.
	 * @param enabled Changes whether or not this user has been enabled for login.
	 * @param attributes Additional attributes to store in the user. They must be in JSON format.
	 */
		function editUser($name = null, $lastname = null, $iduser, $email = null, $area = null, $position = null, $telephone1 = null, $telephone1ext = null, $telephone2 = null, $telephone2ext = null, $mobile = null, $address = null, $skype = null, $other = null, $enabled = "true", $attributes = null) {

			$method = ORGANIZATION . "/users/$iduser";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "lastname" => $lastname,
							 "email" => $email,
							 "area" => $area,
							 "position" => $position,
							 "telephone1" => $telephone1,
							 "telephone1ext" => $telephone1ext,
							 "telephone2" => $telephone2,
							 "telephone2ext" => $telephone2ext,
							 "mobile" => $mobile,
							 "address" => $address,
							 "skype" => $skype,
							 "other" => $other,
							 "enabled" => $enabled,
							 "attributes" => $attributes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Deletes a user from a Zyncro organizations
	 * 
	 * @param idReceptionUser ID of user who will become the new main owner of all the groups/departments where the user to be deleted is
	 *            main owner. If not given all these groups/departments will be deleted.
	 */
		function deleteUser($idReceptionUser = null, $iduser) {

			$method = ORGANIZATION . "/users/$iduser";

			$verbmethod = "DELETE";

			$params = array("idReceptionUser" => $idReceptionUser);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Uploads a new profile image for the organization.
	 * 
	 * @param file The file to upload (valid files are png, gif, jpeg)
	 * @param length The size of the file to upload in bytes.
	 * @since 3.5
	 */
		function uploadOrganizationLogo($length = null, $file = null) {

			$method = ORGANIZATION . "/uploadlogo/@oauthtoken";

			$verbmethod = "POST";

			$params = array("length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets de list of valid IP's
	 */
		function obtainValidIPs() {

			$method = ORGANIZATION . "/ip";

			$verbmethod = "GET";

			$params = array();

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Updates the list of valid IP's
	 * 
	 * @param ips Comma-separated list of users IDs
	 * 
	 * @since 4.0
	 */
		function updateValidIPs() {

			$method = ORGANIZATION . "/ip";

			$verbmethod = "POST";

			$params = array();

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Updates the list of valid IP's
	 * 
	 * @param ips Comma-separated list of users IDs
	 * 
	 * @since 4.0
	 */
		function extends($consumerkey, $organizationName = null, $organizationQuota = null, $totalUsers = null, $organizationType = null, $userName = null, $lastName = null, $password = null, $email = null, $userQuota = null, $language = null) {

			$method = ORGANIZATION . "/create/$consumerkey";

			$verbmethod = "POST";

			$params = array("organizationName" => $organizationName,
							 "organizationQuota" => $organizationQuota,
							 "totalUsers" => $totalUsers,
							 "organizationType" => $organizationType,
							 "userName" => $userName,
							 "lastName" => $lastName,
							 "password" => $password,
							 "email" => $email,
							 "userQuota" => $userQuota,
							 "language" => $language);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>