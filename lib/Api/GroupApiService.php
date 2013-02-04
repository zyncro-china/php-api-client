<?php
	include_once "../config.php";
	class GroupApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets the list of the user's groups, including the groups the user is member of and the groups the user can access (open groups),
	 * whether they are a member of them or not {@link GroupFilterApiType}.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param groupTypes Comma-separated list of group types. Default returns all types. {@link GroupApiType}
	 * @param groupFilter Group filter to apply. Default returns only groups where the user is member (groupFilter = 1).
	 *            {@link GroupFilterApiType}
	 * @param startsWith Text to search starting with.
	 * @param orderField Order field. Default set to NAME. {@link GroupOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of groups. {@link GroupApi}
	 */
		function getGroups($pageNumber = null, $itemsPerPage = null, $groupTypes = null, $groupFilter = "1", $startsWith = null, $orderField = null, $orderType = null) {

			$method = GROUPS . "/";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "groupTypes" => $groupTypes,
							 "groupFilter" => $groupFilter,
							 "startsWith" => $startsWith,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the information for a group
	 * 
	 * @return A group {@link GroupApi}
	 */
		function getGroup($idgroup) {

			$method = GROUPS . "/$idgroup";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits a group.
	 * 
	 * @param name Name of the group to modify.
	 * @param description Description of the group to modify.
	 * @param attributes Additional attributes to store in the group. They must be in JSON format.
	 * @param defaultvote Whether all group threads will be allowed to vote by default or not. {@since 4.0}
	 * @since 3.4
	 */
		function editGroup($idgroup, $name = null, $description = null, $attributes = null, $defaultvote = null) {

			$method = GROUPS . "/$idgroup";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description,
							 "attributes" => $attributes,
							 "defaultvote" => $defaultvote);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes an existing group. Only a group owner can delete the group.
	 */
		function deleteGroup($idgroup) {

			$method = GROUPS . "/$idgroup";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets information for a list of ID of groups.
	 * 
	 * @param groups Comma-separated list of group IDs to return.
	 * @return A list of groups {@link GroupApi}.
	 * @since 3.4
	 */
		function getGroupsById($groups = null) {

			$method = GROUPS . "/profiles";

			$verbmethod = "GET";

			$params = array("groups" => $groups);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the root documents from a group.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param orderField Order field. Default set to NAME. {@link DocumentOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of documents. {@link DocumentApi}
	 */
		function getDocuments($pageNumber = null, $idgroup, $itemsPerPage = null, $orderField = null, $orderType = null) {

			$method = GROUPS . "/$idgroup/documents";

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
		function getDocumentVersions($idgroup, $pageNumber = null, $iddocument, $itemsPerPage = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/versions";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the root documents from a folder of a group.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param orderField Order field. Default set to NAME. {@link DocumentOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of documents {@link DocumentApi}.
	 */
		function getDocumentsByDocumentId($idgroup, $pageNumber = null, $iddocument, $itemsPerPage = null, $orderField = null, $orderType = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument";

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
	 * @param documents Comma-separated list of document IDs to filter the search. Default search in all.
	 * @param orderField Order field. Default set to NAME. {@link GroupOrderFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of documents {@link DocumentApi}.
	 * @since 3.5
	 */
		function getDocumentsById($idgroup, $documents = null, $orderField = null, $orderType = null) {

			$method = GROUPS . "/$idgroup/documents/profiles";

			$verbmethod = "GET";

			$params = array("documents" => $documents,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the structure of an existing document from a group.
	 * 
	 * @return A document {@link DocumentApi}
	 */
		function getDocument($idgroup, $iddocument) {

			$method = GROUPS . "/$idgroup/document/$iddocument";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes a document from a group.
	 * 
	 * @since 3.4
	 */
		function deleteDocument($idgroup, $iddocument) {

			$method = GROUPS . "/$idgroup/document/$iddocument";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits a document in a group.
	 * 
	 * @param name Name of the document to modify.
	 * @param description Description of the document to modify.
	 * @param attributes Additional attributes to store in the document. They must be in JSON format.
	 * @since 3.4
	 */
		function editDocument($idgroup, $iddocument, $name = null, $description = null, $attributes = null) {

			$method = GROUPS . "/$idgroup/document/$iddocument";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description,
							 "attributes" => $attributes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new group.
	 * 
	 * @param name Name of the group to create.
	 * @param description Description of the group to create.
	 * @param isOpen Set as true if the new group is to be a Public (open) group, and false if it is to be Private. Default is false.
	 * @param isNewMemberEditor Parameter to give new members editor permissions when they join a Public group. Default is false.
	 * @param isNewMemberTaskManager Parameter to give new members task manager permissions when they join a Public group. Default is false.
	 * @param isNewMemberInviter Parameter to give new members inviter permissions when they join a Public group. Default is false.
	 * @param isNewMemberOwner Parameter to give new members owner permissions when they join a Public group. Default is false.
	 * @param isNewMemberCommenter Parameter to give new members comment permissions when they join a Public group. Default is false.
	 * @param showMembers Parameter to let members of the new group see each other. Default is true.
	 * @param attributes Additional attributes to store in the group. They must be in JSON format.
	 * @return ID of the newly created group.
	 */
		function createGroup($name = null, $description = null, $isOpen = "false", $isNewMemberEditor = "false", $isNewMemberTaskManager = "false", $isNewMemberInviter = "false", $isNewMemberOwner = "false", $isNewMemberCommenter = "false", $showMembers = "true", $attributes = null) {

			$method = GROUPS . "/";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description,
							 "isOpen" => $isOpen,
							 "isNewMemberEditor" => $isNewMemberEditor,
							 "isNewMemberTaskManager" => $isNewMemberTaskManager,
							 "isNewMemberInviter" => $isNewMemberInviter,
							 "isNewMemberOwner" => $isNewMemberOwner,
							 "isNewMemberCommenter" => $isNewMemberCommenter,
							 "showMembers" => $showMembers,
							 "attributes" => $attributes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new folder in the root of a group.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 * @return ID of the newly created folder.
	 */
		function createFolder($name = null, $idgroup, $description = null) {

			$method = GROUPS . "/$idgroup/documents/folder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new folder in the root of a group and returns information about created folder.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 * @return A folder {@link DocumentApi}
	 * @since 3.5
	 */
		function createFolderAndGetInfo($idgroup, $name = null, $description = null) {

			$method = GROUPS . "/$idgroup/documents/createfolder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new subfolder in a folder of a group.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 */
		function createFolderInDocument($idgroup, $name = null, $iddocument, $description = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/folder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new subfolder in a folder of a group an return information about created folder.
	 * 
	 * @param name Name of the folder to create.
	 * @param description Description of the folder to create.
	 * @return A folder {@link DocumentApi}
	 * @since 3.5
	 */
		function createFolderInDocumentAndGetInfo($idgroup, $name = null, $iddocument, $description = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/createfolder";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Lets a user join the open group and become a member of it.
	 */
		function joinGroup($idgroup) {

			$method = GROUPS . "/$idgroup/join";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Lets a user leave an open group.
	 * 
	 * @since 3.5
	 */
		function leaveGroup($idgroup) {

			$method = GROUPS . "/$idgroup/leave";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a Zlink URL for the group and returns that URL.
	 * 
	 * @param expiration POSIX time in milliseconds when the Zlink expires. Default it does not expire {@since 3.4}
	 * @param password Password to set to access the Zlink. {@since 3.4}
	 * @return A Zlink {@link UrlServiceResultApi}
	 */
		function createZlink($expiration = null, $idgroup, $password = null) {

			$method = GROUPS . "/$idgroup/zlink";

			$verbmethod = "POST";

			$params = array("expiration" => $expiration,
							 "password" => $password);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Create a Zlink URL for a document in a group and returns that URL.
	 * 
	 * @param expiration POSIX time in milliseconds when the Zlink expires. Default it does not expire {@since 3.4}
	 * @param password Password to set to access the Zlink. {@since 3.4}
	 * @return A Zlink {@link UrlServiceResultApi}
	 */
		function createDocumentZlink($idgroup, $expiration = null, $iddocument, $password = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/zlink";

			$verbmethod = "POST";

			$params = array("expiration" => $expiration,
							 "password" => $password);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new external link document in the root of a group.
	 * 
	 * @param name Name of the document.
	 * @param url URL (external link) for the document.
	 * @param description Description of the document.
	 * @return ID of the newly created link.
	 */
		function createExternalLink($name = null, $idgroup, $url = null, $description = null) {

			$method = GROUPS . "/$idgroup/externallink";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "url" => $url,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new external link document in folder of a group.
	 * 
	 * @param name Name of the document.
	 * @param url URL (external link) for the document.
	 * @param description Description of the document.
	 * @return ID of the newly created link.
	 */
		function createExternalLinkByDocumentId($idgroup, $name = null, $iddocument, $url = null, $description = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/externallink";

			$verbmethod = "POST";

			$params = array("name" => $name,
							 "url" => $url,
							 "description" => $description);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new internal link document in the root of a group. This document is a "link" to another file in another group.
	 * 
	 * @param idLink Target document ID, i.e. the document to which it will be linked.
	 * @return ID of the newly created link.
	 */
		function createInternaLink($idLink = null, $idgroup) {

			$method = GROUPS . "/$idgroup/internallink";

			$verbmethod = "POST";

			$params = array("idLink" => $idLink);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new internal link document in a folder of a group. This document is a "link" to another file in another group.
	 * 
	 * @param idLink Target document ID, i.e. the document to which it will be linked.
	 * @return ID of the newly created link.
	 */
		function createInternaLinkByDocumentId($idgroup, $idLink = null, $iddocument) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/internallink";

			$verbmethod = "POST";

			$params = array("idLink" => $idLink);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the members of a group.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of members {@link MemberApi}.
	 */
		function getMembers($pageNumber = null, $idgroup, $itemsPerPage = null) {

			$method = GROUPS . "/$idgroup/members";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the information of a member of the group.
	 * 
	 * @return A member {@link MemberApi}.
	 */
		function getMember($idgroup, $iduser) {

			$method = GROUPS . "/$idgroup/members/$iduser";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits a member of the group.
	 * 
	 * @param isEditor Parameter to set the member as editor.
	 * @param isTaskManager Parameter to set the member as task manager.
	 * @param isInviter Parameter to set the member as inviter.
	 * @param isCommenter Parameter to set the member as commenter.
	 * @param isOwner Parameter to set the member as owner.
	 * @since 3.4
	 */
		function editMember($idgroup, $isEditor = null, $idMemberUser, $isTaskManager = null, $isInviter = null, $isCommenter = null, $isOwner = null) {

			$method = GROUPS . "/$idgroup/members/$idMemberUser";

			$verbmethod = "POST";

			$params = array("isEditor" => $isEditor,
							 "isTaskManager" => $isTaskManager,
							 "isInviter" => $isInviter,
							 "isCommenter" => $isCommenter,
							 "isOwner" => $isOwner);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes a member from a group.
	 */
		function deleteMember($idgroup, $iduser) {

			$method = GROUPS . "/$idgroup/members/$iduser";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Adds a new member to the group.
	 * 
	 * @param idUser User ID of the new member to add.
	 * @param isEditor Parameter to set the new member as editor. Default is false.
	 * @param isTaskManager Parameter to set the new member as task manager. Default is false.
	 * @param isInviter Parameter to set the new member as inviter. Default is false.
	 * @param isCommenter Parameter to set the new member as inviter. Default is true. {@since 3.4}
	 * @param isOwner Parameter to set the new member as owner. Default is false.
	 */
		function addMember($idUser = null, $idgroup, $isEditor = "false", $isTaskManager = "false", $isInviter = "false", $isCommenter = "true", $isOwner = "false") {

			$method = GROUPS . "/$idgroup/members";

			$verbmethod = "POST";

			$params = array("idUser" => $idUser,
							 "isEditor" => $isEditor,
							 "isTaskManager" => $isTaskManager,
							 "isInviter" => $isInviter,
							 "isCommenter" => $isCommenter,
							 "isOwner" => $isOwner);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Enables or disables the subscription to event notifications in a group.
	 * 
	 * @param notificationType Notification type {@link NotificationApiType}
	 * @param subscriptionType Type of event to subscribe {@link SubscriptionEventApiType}
	 * @param enabled Determines whether subscription to the notification of this event type is enabled or disabled.
	 * @since 3.4
	 */
		function editNotification($idgroup, $notificationType = null, $subscriptionType = null, $enabled = null) {

			$method = GROUPS . "/$idgroup/notifications";

			$verbmethod = "POST";

			$params = array("notificationType" => $notificationType,
							 "subscriptionType" => $subscriptionType,
							 "enabled" => $enabled);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Searches groups for a specific text.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10
	 * @param text Text to search in the name and description of the groups.
	 * @param users Comma-separated list of user IDs to filter the search to groups including these users as creator. Default search in all.
	 * @param fromDate POSIX time in milliseconds. Used to filter the search to groups that have been created from this date. Default search
	 *            from the start.
	 * @param toDate POSIX time in milliseconds. Used to filter the search to groups that have been created up to this date. Default search
	 *            up to now.
	 * @param onlyResultSize Set as true if you only want to know the number of results that are in this search. Default is false.
	 * @return A list of groups {@link GroupApi} matching the search.
	 */
		function searchGroups($pageNumber = null, $itemsPerPage = null, $text = null, $users = null, $fromDate = null, $toDate = null, $onlyResultSize = "false") {

			$method = GROUPS . "/search";

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
	 * @param text Text to search in the name and description of the groups.
	 * @param groups Comma-separated list of group IDs to filter the search to documents included only in these groups. Default search in
	 *            all groups.
	 * @param users Comma-separated list of user IDs to filter the search to groups including these users as creator. Default search in all.
	 * @param fromDate POSIX time in milliseconds. Used to filter the search to groups that have been created from this date. Default search
	 *            from the start.
	 * @param toDate POSIX time in milliseconds. Used to filter the search to groups that have been created up to this date. Default search
	 *            up to now.
	 * @param onlyResultSize Set as true if you only want to know the number of results that are in this search. Default is false.
	 * @return A list of documents {@link DocumentApi} matching the search.
	 */
		function searchDocuments($pageNumber = null, $itemsPerPage = null, $text = null, $groups = null, $users = null, $fromDate = null, $toDate = null, $onlyResultSize = "false") {

			$method = GROUPS . "/documents/search";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "text" => $text,
							 "groups" => $groups,
							 "users" => $users,
							 "fromDate" => $fromDate,
							 "toDate" => $toDate,
							 "onlyResultSize" => $onlyResultSize);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Downloads a file from a group. This works just like a direct download link. However, this link should not be displayed to anyone
	 * except the authenticated user, as the oauthtoken here can be used in other API calls. Note: This method does not require the use of
	 * the OAuth Authorization header.
	 */
		function downloadDocument($idgroup, $iddocument) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/download/@oauthtoken";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApiDirect($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Downloads a file version from a group. This works just like a direct download link. However, this link should not be displayed to
	 * anyone except the authenticated user, as the oauthtoken here can be used in other API calls. Note: This method does not require the
	 * use of the OAuth Authorization header.
	 * 
	 * @since 3.5
	 */
		function downloadDocumentVersion($idgroup, $iddocument, $idversion) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/version/$idversion/download/@oauthtoken";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApiDirect($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Uploads a new file to a group (Multipart HTTP POST request). Note: This method does not require the use of the OAuth Authorization
	 * header.
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
		function upload($idgroup, $parentDocumentUrn = null, $iddocument = null, $description = null, $fileName = null, $length = null, $file = null) {

			$method = GROUPS . "/$idgroup/documents/upload/@oauthtoken";

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
	 * Gets a preview of an image document from a group.
	 * 
	 * @param width The width in pixels of the preview
	 * @param height The height in pixels of the preview
	 * @param mode The modes available to generate the preview are 0: Keeps proportions, 1: Stretch and 2: Crop. Defaults set to 0.
	 * @since 3.4
	 */
		function preview($idgroup, $iddocument, $width = null, $height = null, $mode = "0") {

			$method = GROUPS . "/$idgroup/documents/$iddocument/preview/@oauthtoken";

			$verbmethod = "GET";

			$params = array("width" => $width,
							 "height" => $height,
							 "mode" => $mode);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApiDirect($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Uploads a new icon file for a group.
	 * 
	 * @param file The file to upload (valid files are png, gif, jpeg)
	 * @param length The size of the file to upload in bytes.
	 * @since 3.5
	 */
		function uploadGroupIcon($idgroup, $length = null, $file = null) {

			$method = GROUPS . "/$idgroup/uploadicon/@oauthtoken";

			$verbmethod = "POST";

			$params = array("length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * @deprecated Use {@link IGroupApiService#upload(String, String, String, String, String, String, Long, InputStream)}
	 */
		function uploadNewDocument($idgroup, $description = null, $comment = null, $parentDocumentUrn = null, $description = null, $comment = null, $fileName = null, $length = null, $file = null) {

			$method = GROUPS . "/$idgroup/upload/@oauthtoken";

			$verbmethod = "POST";

			$params = array("description" => $description,
							 "comment" => $comment,
							 "parentDocumentUrn" => $parentDocumentUrn,
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
	 * @deprecated Use {@link IGroupApiService#upload(String, String, String, String, String, String, Long, InputStream)}
	 */
		function uploadDocumentNewVersion($idgroup, $idversion = null, $iddocument, $description = null, $comment = null, $fileName = null, $length = null, $file = null) {

			$method = GROUPS . "/$idgroup/documents/$iddocument/upload/@oauthtoken";

			$verbmethod = "POST";

			$params = array("idversion" => $idversion,
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
	 * @deprecated Use {@link IGroupApiService#upload(String, String, String, String, String, String, Long, InputStream)}
	 */
		function uploadNewDocumentAttached($idgroup, $parentDocumentUrn = null, $eventUrn = null, $description = null, $fileName = null, $length = null, $file = null) {

			$method = GROUPS . "/$idgroup/uploadattach/@oauthtoken";

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