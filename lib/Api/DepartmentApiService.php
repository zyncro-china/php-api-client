<?php
	include_once "../config.php";
	class DepartmentApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets the list of organization's departments.
	 * 
	 * @param pageNumber Page to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param departmentFilter Department filter to apply {@link DepartmentFilterApiType}. Default returns all departments (departmentFilter
	 *            = 0).
	 * @param startsWith Text to search starting with.
	 * @param orderField Order field. Default set to NAME. {@link GroupOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of departments {@link DepartmentApi}.
	 */
		function getDepartments($pageNumber = null, $itemsPerPage = null, $departmentFilter = "0", $startsWith = null, $orderField = null, $orderType = null) {

			$method = DEPARTMENTS . "/";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "departmentFilter" => $departmentFilter,
							 "startsWith" => $startsWith,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new department.
	 * 
	 * @param name Name of the department to create.
	 * @param description Description of the department to create.
	 * @param address Address of the department to create.
	 * @param telephone Telephone of the department to create.
	 * @param email Email of the department to create.
	 * @param web Web of the department to create.
	 * @param fax Fax of the department to create.
	 * @param showMembers Parameter to let members of the new department see each other. Default is true.
	 * @param idUserOwner User ID of the owner/manager of the new department; if it is not set, the owner/manager will be the user invoking
	 *            the service.
	 * @param attributes Additional attributes to store in the department. They must be in JSON format.
	 * @param isFollowerCommenter Whether the followers of the department can comment or not.
	 * @return ID of the newly created department.
	 */
		function createDepartment($name = null, $description = null, $address = null, $telephone = null, $email = null, $web = null, $fax = null, $showMembers = "true", $idUserOwner = null, $attributes = null, $isFollowerCommenter = "true") {

			$method = DEPARTMENTS . "/";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description,
							 "address" => $address,
							 "telephone" => $telephone,
							 "email" => $email,
							 "web" => $web,
							 "fax" => $fax,
							 "showMembers" => $showMembers,
							 "idUserOwner" => $idUserOwner,
							 "attributes" => $attributes,
							 "isFollowerCommenter" => $isFollowerCommenter);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets information for a list of ID of departments.
	 * 
	 * @param departments Comma-separated list of department IDs to return.
	 * @return A list of departments {@link DepartmentApi}.
	 * @since 3.4
	 */
		function getDepartmentsById($departments = null) {

			$method = DEPARTMENTS . "/profiles";

			$verbmethod = "GET";

			$params = array("departments" => $departments);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the information for a department.
	 * 
	 * @return A department {@link DepartmentApi}
	 */
		function getDepartment($iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits an existing department.
	 * 
	 * @param name Name of the department to edit.
	 * @param description Description of the department to edit.
	 * @param address Address of the department to edit.
	 * @param telephone Telephone of the department to edit.
	 * @param email Email of the department to edit.
	 * @param web Web of the department to edit.
	 * @param fax Fax of the department to edit.
	 * @param idNewManager Changed user ID of the owner/manager of the department.
	 * @param attributes Additional attributes to store in the department. They must be in JSON format.
	 * @param defaultvote Whether all departments threads will be allowed to vote by default or not. {@since 4.0}
	 */
		function editDepartment($iddepartment, $name = null, $description = null, $address = null, $telephone = null, $email = null, $web = null, $fax = null, $idNewManager = null, $attributes = null, $defaultvote = null) {

			$method = DEPARTMENTS . "/$iddepartment";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description,
							 "address" => $address,
							 "telephone" => $telephone,
							 "email" => $email,
							 "web" => $web,
							 "fax" => $fax,
							 "idNewManager" => $idNewManager,
							 "attributes" => $attributes,
							 "defaultvote" => $defaultvote);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes the department.
	 */
		function deleteDepartment($iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits a document in a department.
	 * 
	 * @param name Name of the document to modify.
	 * @param description Description of the document to modify.
	 * @param attributes Additional attributes to store in the document. They must be in JSON format.
	 * @since 3.4
	 */
		function editDocument($iddepartment, $iddocument, $name = null, $description = null, $attributes = null) {

			$method = DEPARTMENTS . "/$iddepartment/document/$iddocument";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description,
							 "attributes" => $attributes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes a document from a department.
	 * 
	 * @since 3.4
	 */
		function deleteDocument($idgroup, $iddocument) {

			$method = DEPARTMENTS . "/$idgroup/document/$iddocument";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Enables or disables the subscription to event notifications in a department.
	 * 
	 * @param notificationType Notification type {@link NotificationApiType}.
	 * @param subscriptionType Type of event to subscribe {@link SubscriptionEventApiType}.
	 * @param enabled Determines whether subscription to the notification of this event type is enabled or disabled.
	 * @since 3.4
	 */
		function editNotification($iddepartment, $notificationType = null, $subscriptionType = null, $enabled = null) {

			$method = DEPARTMENTS . "/$iddepartment/notifications";

			$verbmethod = "POST";

			$params = array("notificationType" => $notificationType,
							 "subscriptionType" => $subscriptionType,
							 "enabled" => $enabled);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of departments that the indicated user is following.
	 * 
	 * @param pageNumber Page to return. Default set to 1
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param startsWith Text to search starting with.
	 * @param orderField Order field. Default set to NAME. {@link GroupOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of departments {@link DepartmentApi}.
	 */
		function getFollowingDepartmentsForUser($iduser, $pageNumber = null, $itemsPerPage = null, $startsWith = null, $orderField = null, $orderType = null) {

			$method = DEPARTMENTS . "/users/$iduser/following";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "startsWith" => $startsWith,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of departments for the user.
	 * 
	 * @param pageNumber Page to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param departmentFilter Department filter to apply {@link DepartmentFilterApiType}. Default returns all departments (departmentFilter
	 *            = 0).
	 * @param startsWith Text to search starting with.
	 * @param orderField Order field. Default set to NAME. {@link GroupOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of departments {@link DepartmentApi}.
	 * @since 3.4
	 */
		function getDepartmentsForUser($iduser, $pageNumber = null, $itemsPerPage = null, $departmentFilter = "0", $startsWith = null, $orderField = null, $orderType = null) {

			$method = DEPARTMENTS . "/users/$iduser";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "departmentFilter" => $departmentFilter,
							 "startsWith" => $startsWith,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * The calling user starts following the indicated department.
	 */
		function followDepartment($iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment/follow";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * The calling user stops following the indicated department.
	 */
		function unfollowDepartment($iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment/unfollow";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the members of a department.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of members {@link MemberApi}.
	 */
		function getMembers($iddepartment, $pageNumber = null, $itemsPerPage = null) {

			$method = DEPARTMENTS . "/$iddepartment/members";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of users following the indicated.
	 * 
	 * @param pageNumber Page to return. Default set to 1
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of departments {@link DepartmentApi}.
	 */
		function getFollowers($iddepartment, $pageNumber = null, $itemsPerPage = null) {

			$method = DEPARTMENTS . "/$iddepartment/following";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the information for a member of a department.
	 * 
	 * @return A member {@link MemberApi}
	 */
		function getMember($iddepartment, $iduser) {

			$method = DEPARTMENTS . "/$iddepartment/members/$iduser";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits a member in a department.
	 * 
	 * @param isOwner Parameter to set the member as owner.
	 * @since 3.4
	 */
		function editMember($iddepartment, $idMemberUser, $isOwner = null) {

			$method = DEPARTMENTS . "/$iddepartment/members/$idMemberUser";

			$verbmethod = "POST";

			$params = array("isOwner" => $isOwner);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Adds a new member to a department.
	 * 
	 * @param idUser User ID of the new member to add.
	 * @param isOwner Parameter to set the new member as owner. Default is false.
	 */
		function addMember($iddepartment, $idUser = null, $isOwner = "false") {

			$method = DEPARTMENTS . "/$iddepartment/members";

			$verbmethod = "POST";

			$params = array("idUser" => $idUser,
							 "isOwner" => $isOwner);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes a member from a department.
	 */
		function deleteMember($iddepartment, $iduser) {

			$method = DEPARTMENTS . "/$iddepartment/members/$iduser";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the root documents from a department.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param orderField Order field. Default set to NAME. {@link DocumentOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of documents {@link DocumentApi}.
	 */
		function getDocuments($iddepartment, $pageNumber = null, $itemsPerPage = null, $orderField = null, $orderType = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the list of versions of a document.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of versions of a document {@link DocumentVersionApi}
	 * @since 3.5
	 */
		function getDocumentVersions($iddepartment, $iddocument, $pageNumber = null, $itemsPerPage = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/versions";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the root documents from a folder of a department.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param orderField Order field. Default set to NAME. {@link DocumentOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of documents {@link DocumentApi}.
	 */
		function getDocumentsById($iddepartment, $iddocument, $pageNumber = null, $itemsPerPage = null, $orderField = null, $orderType = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets information for a list of ID of documents.
	 * 
	 * @param orderField Order field. Default set to NAME. {@link DocumentOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of documents {@link DocumentApi}.
	 * @since 3.5
	 */
		function getDocumentsByIds($iddepartment, $documents = null, $orderField = null, $orderType = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/profiles";

			$verbmethod = "GET";

			$params = array("documents" => $documents,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the structure of an existing document from a department
	 * 
	 * @return A document {@link DocumentApi}
	 */
		function getDocument($iddepartment, $iddocument) {

			$method = DEPARTMENTS . "/$iddepartment/document/$iddocument";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new folder in the root of a department.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 */
		function createFolder($iddepartment, $name = null, $description = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/folder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new subfolder in a folder of a department an return information about created folder.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 * @return A folder {@link DocumentApi}
	 * @since 3.5
	 */
		function createFolderAndGetInfo($iddepartment, $name = null, $description = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/createfolder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new subfolder in a folder of a department.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 */
		function createFolderInDocument($iddepartment, $iddocument, $name = null, $description = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/folder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new subfolder in a folder of a department an return information about created folder.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 * @return A folder {@link DocumentApi}
	 * @since 3.5
	 */
		function createFolderInDocumentAndGetInfo($iddepartment, $iddocument, $name = null, $description = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/createfolder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a Zlink URL for the department and returns that URL.
	 * 
	 * @param expiration POSIX time in milliseconds when the Zlink expires. Default it does not expire {@since 3.4}
	 * @param password Password to set to access the Zlink. {@since 3.4}
	 * @return A Zlink {@link UrlServiceResultApi}
	 */
		function createZlink($iddepartment, $expiration = null, $password = null) {

			$method = DEPARTMENTS . "/$iddepartment/zlink";

			$verbmethod = "POST";

			$params = array("expiration" => $expiration,
							 "password" => $password);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a Zlink URL for a document in a department and returns that URL.
	 * 
	 * @param expiration POSIX time in milliseconds when the Zlink expires. Default it does not expire {@since 3.4}
	 * @param password Password to set to access the Zlink. {@since 3.4}
	 * @return A Zlink {@link UrlServiceResultApi}
	 */
		function createDocumentZlink($iddepartment, $iddocument, $expiration = null, $password = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/zlink";

			$verbmethod = "POST";

			$params = array("expiration" => $expiration,
							 "password" => $password);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new external link document in the root of a department.
	 * 
	 * @param name Name of the document.
	 * @param url URL (external link) for the document.
	 * @param description Description of the document.
	 * @return ID of the newly created link.
	 */
		function createExternalLink($iddepartment, $name = null, $url = null, $description = null) {

			$method = DEPARTMENTS . "/$iddepartment/externallink";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "url" => $url,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new external link document in folder of a department.
	 * 
	 * @param name Name of the document.
	 * @param url URL (external link) for the document.
	 * @param description Description of the document.
	 * @return ID of the newly created link.
	 */
		function createExternalLinkByDocumentId($iddepartment, $iddocument, $name = null, $url = null, $description = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/externallink";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "url" => $url,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new internal link document in the root of a department. This document is a "link" to another file in another group or
	 * department.
	 * 
	 * @param idLink Target document ID, i.e. the document to which it will be linked.
	 * @return ID of the newly created link.
	 */
		function createInternaLink($iddepartment, $idLink = null) {

			$method = DEPARTMENTS . "/$iddepartment/internallink";

			$verbmethod = "POST";

			$params = array("idLink" => $idLink);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new internal link document in a folder of a department. This document is a "link" to another file in another group or
	 * department.
	 * 
	 * @param idLink Target document ID, i.e. the document to which it will be linked.
	 * @return ID of the newly created link.
	 */
		function createInternaLinkByDocumentId($iddepartment, $iddocument, $idLink = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/internallink";

			$verbmethod = "POST";

			$params = array("idLink" => $idLink);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Searches groups for a specific text.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10
	 * @param text Text to search in the name and description of the departments.
	 * @param users Comma-separated list of user IDs to filter the search to departments including these users as creator. Default search in
	 *            all.
	 * @param fromDate POSIX time in milliseconds. Used to filter the search to departments that have been created from this date. Default
	 *            search from the start.
	 * @param toDate POSIX time in milliseconds. Used to filter the search to departments that have been created up to this date. Default
	 *            search up to now.
	 * @param onlyResultSize Set as true if you only want to know the number of results that are in this search. Default is false.
	 * @return A list of departments {@link DepartmentApi} matching the search.
	 */
		function searchDepartments($pageNumber = null, $itemsPerPage = null, $text = null, $users = null, $fromDate = null, $toDate = null, $onlyResultSize = "false") {

			$method = DEPARTMENTS . "/search";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "text" => $text,
							 "users" => $users,
							 "fromDate" => $fromDate,
							 "toDate" => $toDate,
							 "onlyResultSize" => $onlyResultSize);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Searches documents for a specific text.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10
	 * @param text Text to search in the name and description of the departments.
	 * @param departments Comma-separated list of group IDs to filter the search to documents included only in these departments. Default
	 *            search in all departments.
	 * @param users Comma-separated list of user IDs to filter the search to departments including these users as creator. Default search in
	 *            all.
	 * @param fromDate POSIX time in milliseconds. Used to filter the search to departments that have been created from this date. Default
	 *            search from the start.
	 * @param toDate POSIX time in milliseconds. Used to filter the search to departments that have been created up to this date. Default
	 *            search up to now.
	 * @param onlyResultSize Set as true if you only want to know the number of results that are in this search. Default is false.
	 * @return A list of documents {@link DocumentApi} matching the search.
	 */
		function searchDocuments($pageNumber = null, $itemsPerPage = null, $text = null, $departments = null, $users = null, $fromDate = null, $toDate = null, $onlyResultSize = "false") {

			$method = DEPARTMENTS . "/documents/search";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "text" => $text,
							 "departments" => $departments,
							 "users" => $users,
							 "fromDate" => $fromDate,
							 "toDate" => $toDate,
							 "onlyResultSize" => $onlyResultSize);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Downloads a file from a department. This works just like a direct download link. However, this link should not be displayed to anyone
	 * except the authenticated user, as the authtoken here can be used in other API calls. Note: This method does not require the use of
	 * the OAuth Authorization header.
	 */
		function downloadDocument($iddepartment, $iddocument) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/download/@oauthtoken";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApiDirect($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Downloads a file version from a department. This works just like a direct download link. However, this link should not be displayed
	 * to anyone except the authenticated user, as the oauthtoken here can be used in other API calls. Note: This method does not require
	 * the use of the OAuth Authorization header.
	 * 
	 * @since 3.5
	 */
		function downloadDocumentVersion($iddepartment, $iddocument, $idversion) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/version/$idversion/download/@oauthtoken";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApiDirect($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Uploads a new file to a department (Multipart HTTP POST request). Note: This method does not require the use of the OAuth
	 * Authorization header.
	 * 
	 * @param file The file to upload.
	 * @param fileName The name of the new file to upload.
	 * @param length The size of the file to upload in bytes.
	 * @param iddocument ID of an existing document, if a new version of that document wants to be uploaded.
	 * @param description Text to add as a description for the file. Default is empty.
	 * @param parentDocumentUrn ID of the folder of the group where the new file will be uploaded. If it is not set, the new file will be
	 *            uploaded to the root of the group.
	 * @return Response of the upload {@link UploadDocumentResultApi}
	 * @since 3.5
	 */
		function upload($iddepartment, $parentDocumentUrn = null, $iddocument = null, $description = null, $fileName = null, $length = null, $file = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/upload/@oauthtoken";

			$verbmethod = "POST";

			$params = array("parentDocumentUrn" => $parentDocumentUrn,
							 "iddocument" => $iddocument,
							 "description" => $description,
							 "fileName" => $fileName,
							 "length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets a preview of an image document from a department.
	 * 
	 * @param width The width in pixels of the preview
	 * @param height The height in pixels of the preview
	 * @param mode The modes available to generate the preview are 0: Keeps proportions, 1: Stretch and 2: Crop. Defaults set to 0.
	 * @since 3.4
	 */
		function preview($iddepartment, $iddocument, $width = null, $height = null, $mode = "0") {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/preview/@oauthtoken";

			$verbmethod = "GET";

			$params = array("width" => $width,
							 "height" => $height,
							 "mode" => $mode);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApiDirect($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Associates a tag with a department
	 */
		function associateTag($idtag, $iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment/associatetag/$idtag";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Dissociates a tag with a department
	 */
		function dissociateTag($idtag, $iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment/disassociatetag/$idtag";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the tags associated with a department.
	 * 
	 * @return A list of tags {@link TagApi}
	 */
		function getTags($iddepartment) {

			$method = DEPARTMENTS . "/$iddepartment/tags";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Uploads a new icon file for a department.
	 * 
	 * @param file The file to upload (valid files are png, gif, jpeg)
	 * @param length The size of the file to upload in bytes.
	 * @since 3.5
	 */
		function uploadDepartmentIcon($iddepartment, $length = null, $file = null) {

			$method = DEPARTMENTS . "/$iddepartment/uploadicon/@oauthtoken";

			$verbmethod = "POST";

			$params = array("length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * @deprecated Use {@link IDepartmentApiService#upload(String, String, String, String, String, String, Long, InputStream)}
	 */
		function uploadNewDocument($iddepartment, $parentDocumentUrn = null, $description = null, $comment = null, $fileName = null, $length = null, $file = null) {

			$method = DEPARTMENTS . "/$iddepartment/upload/@oauthtoken";

			$verbmethod = "POST";

			$params = array("parentDocumentUrn" => $parentDocumentUrn,
							 "description" => $description,
							 "comment" => $comment,
							 "fileName" => $fileName,
							 "length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * @deprecated Use {@link IDepartmentApiService#upload(String, String, String, String, String, String, Long, InputStream)}
	 */
		function uploadDocumentNewVersion($iddepartment, $iddocument, $idversion = null, $description = null, $comment = null, $transferUrn = null, $fileName = null, $length = null, $file = null) {

			$method = DEPARTMENTS . "/$iddepartment/documents/$iddocument/upload/@oauthtoken";

			$verbmethod = "POST";

			$params = array("idversion" => $idversion,
							 "description" => $description,
							 "comment" => $comment,
							 "transferUrn" => $transferUrn,
							 "fileName" => $fileName,
							 "length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * @deprecated Use {@link IWallApiService#attachInEvent(String, String, String, String, Long, InputStream)}
	 */
		function uploadNewDocumentAttached($iddepartment, $parentDocumentUrn = null, $eventUrn = null, $description = null, $fileName = null, $length = null, $file = null) {

			$method = DEPARTMENTS . "/$iddepartment/uploadattach/@oauthtoken";

			$verbmethod = "POST";

			$params = array("parentDocumentUrn" => $parentDocumentUrn,
							 "eventUrn" => $eventUrn,
							 "description" => $description,
							 "fileName" => $fileName,
							 "length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>