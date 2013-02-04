<?php
	include_once "../config.php";
	class InvitationApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets the invitations count for the user.
	 * 
	 * @param invitationStates Comma-separated list of invitation states to return. Default returns all states.
	 *            {@link InvitationStateApiType}
	 * @param invitationTypes Comma-separated list of invitation types to return. Default returns all types. {@link InvitationApiType}
	 * @param invitationRole Invitation role of the caller in the invitations count to return. Default value returns all roles.
	 *            {@link InvitationRoleApiType}
	 * @return A list of invitations {@link InvitationApi}.
	 * @since 3.5
	 */
		function getInvitationsCount($invitationStates = null, $invitationTypes = null, $invitationRole = "2") {

			$method = INVITATIONS . "/invitationscount";

			$verbmethod = "GET";

			$params = array("invitationStates" => $invitationStates,
							 "invitationTypes" => $invitationTypes,
							 "invitationRole" => $invitationRole);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the invitations for the user.
	 * 
	 * @param pageNumber Page to return. Default set to 1
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param invitationStates Comma-separated list of invitation states to return. Default returns all states.
	 *            {@link InvitationStateApiType}
	 * @param invitationTypes Comma-separated list of invitation types to return. Default returns all types. {@link InvitationApiType}
	 * @param invitationRole Invitation role of the invitations to return. Default value returns all roles. {@link InvitationRoleApiType}
	 * @return A list of invitations {@link InvitationApi}.
	 */
		function getInvitations($pageNumber = null, $itemsPerPage = null, $invitationStates = null, $invitationTypes = null, $invitationRole = "2") {

			$method = INVITATIONS . "/";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "invitationStates" => $invitationStates,
							 "invitationTypes" => $invitationTypes,
							 "invitationRole" => $invitationRole);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the information about an invitation.
	 */
		function getInvitation($idinvitation) {

			$method = INVITATIONS . "/$idinvitation";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Accepts, cancels or declines an invitation.
	 * 
	 * @param state The new invitation state for the invitation. {@link InvitationStateApiType}
	 */
		function editInvitation($idinvitation, $state = null) {

			$method = INVITATIONS . "/$idinvitation";

			$verbmethod = "POST";

			$params = array("state" => $state);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Sends an invitation to a user to become member of a group.
	 * 
	 * @param idUser User ID of the user to invite. Required if users is empty.
	 * @param idGroup Group ID of the invitation.
	 * @param isEditor Parameter to set the new member as editor. Default is false.
	 * @param isInviter Parameter to set the new member as inviter. Default is false.
	 * @param isTaskManager Parameter to set the new member as task manager. Default is false.
	 * @param isOwner Parameter to set the new member as owner. Default is false.
	 * @param isCommenter Parameter to set the new member as commenter. Default is false.
	 * @param comment Optional comment to add to the invitation
	 * @since 3.4
	 */
		function sendGroupInvitation($idUser = null, $idGroup = null, $isEditor = "false", $isInviter = "false", $isTaskManager = "false", $isOwner = "false", $isCommenter = "false", $comment = null) {

			$method = INVITATIONS . "/groupinvitation";

			$verbmethod = "POST";

			$params = array("idUser" => $idUser,
							 "idGroup" => $idGroup,
							 "isEditor" => $isEditor,
							 "isInviter" => $isInviter,
							 "isTaskManager" => $isTaskManager,
							 "isOwner" => $isOwner,
							 "isCommenter" => $isCommenter,
							 "comment" => $comment);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Sends an invitation to a list of users to become members of a group.
	 * 
	 * @param users Comma-separated list of User ID to invite. Required if idUser is empty.
	 * @param idGroup Group ID of the invitation.
	 * @param isEditor Parameter to set the new member as editor. Default is false.
	 * @param isInviter Parameter to set the new member as inviter. Default is false.
	 * @param isTaskManager Parameter to set the new member as task manager. Default is false.
	 * @param isOwner Parameter to set the new member as owner. Default is false.
	 * @param isCommenter Parameter to set the new member as commenter. Default is false.
	 * @param comment Optional comment to add to the invitation
	 * @since 4.0
	 */
		function sendGroupInvitations($users = null, $idGroup = null, $isEditor = "false", $isInviter = "false", $isTaskManager = "false", $isOwner = "false", $isCommenter = "false", $comment = null) {

			$method = INVITATIONS . "/groupinvitations";

			$verbmethod = "POST";

			$params = array("users" => $users,
							 "idGroup" => $idGroup,
							 "isEditor" => $isEditor,
							 "isInviter" => $isInviter,
							 "isTaskManager" => $isTaskManager,
							 "isOwner" => $isOwner,
							 "isCommenter" => $isCommenter,
							 "comment" => $comment);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Sends an invitation to an external user to be a contact.
	 * 
	 * @param idUser User ID or email of the user to invite.
	 * @param comment Optional comment to add to the invitation
	 * @since 3.4
	 */
		function sendExternalContactInvitation($idUser = null, $comment = null) {

			$method = INVITATIONS . "/externalcontactinvitation";

			$verbmethod = "POST";

			$params = array("idUser" => $idUser,
							 "comment" => $comment);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Sends an invitation to a list of external users to be a contact.
	 * 
	 * @param users Comma-separated list of User ID to invite. Required if idUser is empty.
	 * @param comment Optional comment to add to the invitation
	 * @since 4.0
	 */
		function sendExternalContactInvitations($users = null, $comment = null) {

			$method = INVITATIONS . "/externalcontactinvitations";

			$verbmethod = "POST";

			$params = array("users" => $users,
							 "comment" => $comment);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * @deprecated Use
	 *             {@link IInvitationApiService#sendGroupInvitation(String, String, boolean, boolean, boolean, boolean, boolean, String)}
	 */
		function sendInvitation($appIdOrEmail = null, $shareGroupURN = null, $enableEditor = null, $enableInviter = null, $enableTaskManager = null, $comment = null, $isOwner = null) {

			$method = INVITATIONS . "/group";

			$verbmethod = "POST";

			$params = array("appIdOrEmail" => $appIdOrEmail,
							 "shareGroupURN" => $shareGroupURN,
							 "enableEditor" => $enableEditor,
							 "enableInviter" => $enableInviter,
							 "enableTaskManager" => $enableTaskManager,
							 "comment" => $comment,
							 "isOwner" => $isOwner);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

}
?>