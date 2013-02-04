<?php
	include_once "../config.php";
	class WallApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets all the activity stream events of the user. It can be used to read the contents of all user events on their microblogging wall.
	 * It contains all the microblogging events that the user is able to read.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be used to
	 *            navigate the feed history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10.
	 * @param eventTypes Comma-separated list of integers of the types of events to receive. If it is not set, all event types will be
	 *            returned. {@link EventApiType}
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePrivates Whether include the private messages in the returned events. Default is true. {@since 3.4}
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param users Comma-separated list of users IDs to filter the events returned by the authors included in this list. {@since 3.5}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 */
		function getEvents($date = null, $itemsCount = null, $eventTypes = null, $includeHtml = "false", $includePrivates = "true", $includePropertiesPreview = "false", $viewType = "0", $users = null, $pageNumber = null) {

			$method = WALL . "/";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "eventTypes" => $eventTypes,
							 "includeHtml" => $includeHtml,
							 "includePrivates" => $includePrivates,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "users" => $users,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets all the company feed events. It reads the contents of the company feed from the user's organization. It contains all the
	 * microblogging events from that feed.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be used to
	 *            navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10
	 * @param eventTypes Comma-separated list of integers of the events types to receive. If it is not set, all event types will be
	 *            returned. {@link EventApiType}
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param users Comma-separated list of users IDs to filter the events returned by the authors included in this list. {@since 3.5}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 */
		function getCompanyFeedEvents($date = null, $itemsCount = null, $eventTypes = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0", $users = null, $pageNumber = null) {

			$method = WALL . "/companyfeed";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "eventTypes" => $eventTypes,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "users" => $users,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Publishes a new message on the user's organization company feed. It can only perform this action if the user has the privileges to
	 * write on that feed.
	 * 
	 * @param comment Text of the comment to publish as a new message thread.
	 * @param htmlComment HTML content of the event.
	 * @param indexableContent Text to index for search purposes.
	 * @param attributes Additional attributes to store in the event. They must be in JSON format.
	 * @param idevent ID of the message thread to comment on. If it is not set, a new message thread will be created.
	 * @param usersToNotify Comma-separated list of user IDs to notify on their Inbox this event. {@since 3.4}
	 * @param forceNotifyAllMembers Whether or not notify on their Inbox about this event to all members on this feed. Default if false
	 *            {@since 3.4}
	 * @param delayNotification Whether delay or not the trigger of the notifications this event may generate. This property may be set to
	 *            "true", if you want to delay notifications because some files are going to be attached to this event for example. Default
	 *            is false {@since 3.5}
	 * @param votable Whether the comment is available for voting or not. Default is false {@since 4.0}
	 * @param extraField1 First extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField2 Second extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField3 Third extra sort field for Event view type to return the events {@since 4.0.1}
	 * @return ID of the newly published event.
	 */
		function publishInCompanyFeed($comment = null, $htmlComment = null, $indexableContent = null, $attributes = null, $idevent = null, $usersToNotify = null, $forceNotifyAllMembers = "false", $delayNotification = "false", $votable = "false", $extraField1 = null, $extraField2 = null, $extraField3 = null) {

			$method = WALL . "/companyfeed";

			$verbmethod = "POST";

			$params = array("comment" => $comment,
							 "htmlComment" => $htmlComment,
							 "indexableContent" => $indexableContent,
							 "attributes" => $attributes,
							 "idevent" => $idevent,
							 "usersToNotify" => $usersToNotify,
							 "forceNotifyAllMembers" => $forceNotifyAllMembers,
							 "delayNotification" => $delayNotification,
							 "votable" => $votable,
							 "extraField1" => $extraField1,
							 "extraField2" => $extraField2,
							 "extraField3" => $extraField3);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Deletes a message from the user's organization company feed. It can only perform this action if the user has the privileges to delete
	 * on that feed.
	 */
		function deleteFromCompanyFeed($idevent) {

			$method = WALL . "/companyfeed/$idevent";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Gets all personal feed events. It read the content of the user's personal feed wall. It contains all the microblogging events and
	 * messages from the user's personal feed.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be used to
	 *            navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10.
	 * @param eventTypes Comma-separated list of integers of the events types to receive. If it is not set, all event types will be
	 *            returned. {@link EventApiType}
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param users Comma-separated list of users IDs to filter the events returned by the authors included in this list. {@since 3.5}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 */
		function getPersonalFeedEvents($date = null, $itemsCount = null, $eventTypes = null, $includeHtml = null, $includePropertiesPreview = "false", $viewType = "0", $users = null, $pageNumber = null) {

			$method = WALL . "/personalfeed";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "eventTypes" => $eventTypes,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "users" => $users,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Publishes a new message on the user's personal feed.
	 * 
	 * @param comment Text of the comment to publish as a new message thread.
	 * @param htmlComment HTML content of the event.
	 * @param indexableContent Text to index for search purposes.
	 * @param attributes Additional attributes to store in the event. They must be in JSON format.
	 * @param idevent ID of the message thread to comment on. If it is not set, a new message thread will be created.
	 * @param usersToNotify Comma-separated list of user IDs to notify on their Inbox this event. {@since 3.4}
	 * @param forceNotifyAllMembers Whether or not notify on their Inbox about this event to all members on this feed. Default if false
	 *            {@since 3.4}
	 * @param delayNotification Whether delay or not the trigger of the notifications this event may generate. This property may be set to
	 *            "true", if you want to delay notifications because some files are going to be attached to this event for example. Default
	 *            is false {@since 3.5}
	 * @param votable Whether the comment is votable or not. Default is false {@since 4.0}
	 * @param extraField1 First extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField2 Second extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField3 Third extra sort field for Event view type to return the events {@since 4.0.1}
	 * @return ID of the newly published event.
	 */
		function publishInPersonalFeed($comment = null, $htmlComment = null, $indexableContent = null, $attributes = null, $idevent = null, $usersToNotify = null, $forceNotifyAllMembers = "false", $delayNotification = "false", $votable = "false", $extraField1 = null, $extraField2 = null, $extraField3 = null) {

			$method = WALL . "/personalfeed";

			$verbmethod = "POST";

			$params = array("comment" => $comment,
							 "htmlComment" => $htmlComment,
							 "indexableContent" => $indexableContent,
							 "attributes" => $attributes,
							 "idevent" => $idevent,
							 "usersToNotify" => $usersToNotify,
							 "forceNotifyAllMembers" => $forceNotifyAllMembers,
							 "delayNotification" => $delayNotification,
							 "votable" => $votable,
							 "extraField1" => $extraField1,
							 "extraField2" => $extraField2,
							 "extraField3" => $extraField3);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Deletes a message from the user's personal feed.
	 */
		function deleteFromPersonalFeed($idevent) {

			$method = WALL . "/personalfeed/$idevent";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Gets user's private messages. It read private messages from the user.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter messages/events by returning events older than this date. This parameter can
	 *            be used to navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of messages/events to return (between 1 and 50). Default set to 10.
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param users Comma-separated list of users IDs to filter the events returned by the authors included in this list. {@since 3.5}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 */
		function getPrivateComments($date = null, $itemsCount = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0", $users = null, $pageNumber = null) {

			$method = WALL . "/privatecomments";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "users" => $users,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Sends or answers a private message to a user.
	 * 
	 * @param comment Text of the comment to publish as a new message thread.
	 * @param htmlComment HTML content of the event.
	 * @param indexableContent Text to index for search purposes.
	 * @param attributes Additional attributes to store in the event. They must be in JSON format.
	 * @param idevent ID of the message to comment on. If it is set, "users" parameter is ignored.
	 * @param users Comma-separated list of user IDs to whom a private message should be sent. If it is set, "idevent" parameter is ignored.
	 * @param extraField1 First extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField2 Second extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField3 Third extra sort field for Event view type to return the events {@since 4.0.1}
	 * @return ID of the newly published message.
	 */
		function publishPrivateComment($comment = null, $htmlComment = null, $indexableContent = null, $attributes = null, $idevent = null, $users = null, $extraField1 = null, $extraField2 = null, $extraField3 = null) {

			$method = WALL . "/privatecomments";

			$verbmethod = "POST";

			$params = array("comment" => $comment,
							 "htmlComment" => $htmlComment,
							 "indexableContent" => $indexableContent,
							 "attributes" => $attributes,
							 "idevent" => $idevent,
							 "users" => $users,
							 "extraField1" => $extraField1,
							 "extraField2" => $extraField2,
							 "extraField3" => $extraField3);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Deletes a private message.
	 */
		function deletePrivateComment($idevent) {

			$method = WALL . "/privatecomments/$idevent";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Gets events from a Group or Department. It reads messages from a Group/Department where the user has access.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be use to
	 *            navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10
	 * @param eventTypes Comma-separated list of integers of the events types to receive. If it is not set, all event types will be
	 *            returned. {@link EventApiType}
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param users Comma-separated list of users IDs to filter the events returned by the authors included in this list. {@since 3.5}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 */
		function getGroupEvents($date = null, $idgroup, $itemsCount = null, $eventTypes = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0", $users = null, $pageNumber = null) {

			$method = WALL . "/$idgroup";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "eventTypes" => $eventTypes,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "users" => $users,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the activity stream events associated with a document in a group or department.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be used to
	 *            navigate the feed history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10.
	 * @param eventTypes Comma-separated list of integers of the types of events to receive. If it is not set, all event types will be
	 *            returned. {@link EventApiType}
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 */
		function getDocumentEvents($idgroup, $date = null, $itemsCount = null, $iddocument, $eventTypes = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0", $pageNumber = null) {

			$method = WALL . "/$idgroup/documents/$iddocument";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "eventTypes" => $eventTypes,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets activity events.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be use to
	 *            navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10.
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false.
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 * @since 3.5
	 */
		function getActivityEvents($date = null, $itemsCount = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0", $pageNumber = null) {

			$method = WALL . "/activity";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets profile events for a specific user.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be use to
	 *            navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10.
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false.
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @param pageNumber Only needed when viewType is {@link EventViewApiType#LIKES}, {@link EventViewApiType#TOTALVOTES},
	 *            {@link EventViewApiType#POSITIVEVOTES} or {@link EventViewApiType#NEGATIVEVOTES}. Otherwise, pagination will be by date
	 *            parameter {@since 4.0}
	 * @return A list of events {@link EventApi}
	 * @since 3.5
	 */
		function getProfileEvents($date = null, $iduser, $itemsCount = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0", $pageNumber = null) {

			$method = WALL . "/profile/$iduser";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType,
							 "pageNumber" => $pageNumber);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets like events.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be use to
	 *            navigate the wall history. If it is not set, it returns the latest.
	 * @param itemsCount Number of events to return (between 1 and 50). Default set to 10.
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false.
	 * @param viewType Event view type to return the events. Default is "Latest by main thread date", viewType = 0 {@link EventViewApiType}
	 * @return A list of events {@link EventApi}
	 * @since 3.5
	 */
		function getLikeEvents($date = null, $itemsCount = null, $includeHtml = "false", $includePropertiesPreview = "false", $viewType = "0") {

			$method = WALL . "/likes";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "viewType" => $viewType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Publishes a message on a Group or Department where the user has access.
	 * 
	 * @param comment Text of the comment to publish as a new message thread.
	 * @param htmlComment HTML content of the event.
	 * @param indexableContent Text to index for search purposes.
	 * @param attributes Additional attributes to store in the event. They must be in JSON format.
	 * @param idevent ID of the message thread to comment on. If it is not set a new message thread will be created.
	 * @param iddocument ID of the document to associated this event with.
	 * @param usersToNotify Comma-separated list of user IDs to notify on their Inbox this event. {@since 3.4}
	 * @param forceNotifyAllMembers Whether or not notify on their Inbox about this event to all members on this feed. Default if false
	 *            {@since 3.4}
	 * @param delayNotification Whether delay or not the trigger of the notifications this event may generate. This property may be set to
	 *            "true", if you want to delay notifications because some files are going to be attached to this event for example. Default
	 *            is false {@since 3.5}
	 * @param votable Whether the comment is available for voting or not. Default is false {@since 4.0}
	 * @param extraField1 First extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField2 Second extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField3 Third extra sort field for Event view type to return the events {@since 4.0.1}
	 * @return ID of the newly published message.
	 */
		function publishInGroup($comment = null, $idgroup, $htmlComment = null, $indexableContent = null, $attributes = null, $idevent = null, $iddocument = null, $usersToNotify = null, $forceNotifyAllMembers = "false", $delayNotification = "false", $votable = "false", $extraField1 = null, $extraField2 = null, $extraField3 = null) {

			$method = WALL . "/$idgroup";

			$verbmethod = "POST";

			$params = array("comment" => $comment,
							 "htmlComment" => $htmlComment,
							 "indexableContent" => $indexableContent,
							 "attributes" => $attributes,
							 "idevent" => $idevent,
							 "iddocument" => $iddocument,
							 "usersToNotify" => $usersToNotify,
							 "forceNotifyAllMembers" => $forceNotifyAllMembers,
							 "delayNotification" => $delayNotification,
							 "votable" => $votable,
							 "extraField1" => $extraField1,
							 "extraField2" => $extraField2,
							 "extraField3" => $extraField3);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Deletes an event on a Group/Department.
	 */
		function deleteFromGroup($idgroup, $idevent) {

			$method = WALL . "/$idgroup/$idevent";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Uploads and attaches a new file to a an event.
	 * 
	 * @param file The file to upload and attach to the event.
	 * @param fileName The name of the new file to upload.
	 * @param length The size of the file to upload in bytes.
	 * @param parentDocumentUrn ID of the folder of the group where the new file will be uploaded. If it is not set, the new file will be
	 *            uploaded to the root of the group.
	 * @since 3.5
	 */
		function attachInEvent($idevent, $parentDocumentUrn = null, $fileName = null, $length = null, $file = null) {

			$method = WALL . "/feeds/$idevent/attach/@oauthtoken";

			$verbmethod = "POST";

			$params = array("parentDocumentUrn" => $parentDocumentUrn,
							 "fileName" => $fileName,
							 "length" => $length,
							 "file" => $file);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApiDirect( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the child messages of an event.
	 * 
	 * @param idlastevent Returns the messages older than the event represented by this ID. If it is not set, it returns the latest, sorted
	 *            from newer to older. This parameter can be used to navigate all the message children.
	 * @param itemsCount Number of messages to return (between 1 and 50). Default set to 10
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @param users Comma-separated list of users IDs to filter the events returned by the authors included in this list. {@since 3.5}
	 * @return A list of events {@link EventApi}
	 */
		function getChildEvents($idevent, $idlastevent = null, $itemsCount = null, $includeHtml = "false", $includePropertiesPreview = "false", $users = null) {

			$method = WALL . "/feeds/$idevent";

			$verbmethod = "GET";

			$params = array("idlastevent" => $idlastevent,
							 "itemsCount" => $itemsCount,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview,
							 "users" => $users);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the events associated with the given list of ID of events.
	 * 
	 * @param events Comma-separated list of ID of the events to receive.
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @return A list of events {@link EventApi}
	 */
		function getEventsById($events = null, $includeHtml = "false", $includePropertiesPreview = "false") {

			$method = WALL . "/feeds";

			$verbmethod = "GET";

			$params = array("events" => $events,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets the structure of an existing event.
	 * 
	 * @param includeHtml Whether the HTML content should be included in the returned event. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @return An event {@link EventApi}
	 */
		function getEvent($idevent, $includeHtml = "false", $includePropertiesPreview = "false") {

			$method = WALL . "/feed/$idevent";

			$verbmethod = "GET";

			$params = array("includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Publishes a new message on this event.
	 * 
	 * @param comment Text of the comment to publish as a new message.
	 * @param htmlComment HTML content of the event.
	 * @param indexableContent Text to index for search purposes.
	 * @param attributes Additional attributes to store in the event. They must be in JSON format.
	 * @param usersToNotify Comma-separated list of user IDs to notify on their Inbox this event. {@since 3.4}
	 * @param forceNotifyAllMembers Whether or not notify on their Inbox about this event to all members on this feed. Default if false
	 *            {@since 3.4}
	 * @param delayNotification Whether delay or not the trigger of the notifications this event may generate. This property may be set to
	 *            "true", if you want to delay notifications because some files are going to be attached to this event for example. Default
	 *            is false {@since 3.5}
	 * @param extraField1 First extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField2 Second extra sort field for Event view type to return the events {@since 4.0.1}
	 * @param extraField3 Third extra sort field for Event view type to return the events {@since 4.0.1}
	 * @return ID of the newly published event.
	 */
		function commentInEvent($comment = null, $idevent, $htmlComment = null, $indexableContent = null, $attributes = null, $usersToNotify = null, $forceNotifyAllMembers = "false", $delayNotification = "false", $extraField1 = null, $extraField2 = null, $extraField3 = null) {

			$method = WALL . "/feeds/$idevent";

			$verbmethod = "POST";

			$params = array("comment" => $comment,
							 "htmlComment" => $htmlComment,
							 "indexableContent" => $indexableContent,
							 "attributes" => $attributes,
							 "usersToNotify" => $usersToNotify,
							 "forceNotifyAllMembers" => $forceNotifyAllMembers,
							 "delayNotification" => $delayNotification,
							 "extraField1" => $extraField1,
							 "extraField2" => $extraField2,
							 "extraField3" => $extraField3);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Edits an existing event.
	 * 
	 * @param comment Text of the comment.
	 * @param htmlComment HTML content of the event.
	 * @param indexableContent Text to index for search purposes.
	 * @param attributes Additional attributes to store in the event. They must be in JSON format.
	 */
		function editEvent($comment = null, $idevent, $htmlComment = null, $indexableContent = null, $attributes = null) {

			$method = WALL . "/feed/$idevent";

			$verbmethod = "POST";

			$params = array("comment" => $comment,
							 "htmlComment" => $htmlComment,
							 "indexableContent" => $indexableContent,
							 "attributes" => $attributes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Gets the properties associated with an event. It includes any attached documents that an event may have.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of event properties {@link PropertyEventApi}
	 */
		function getEventProperties($idevent, $pageNumber = null, $itemsPerPage = null) {

			$method = WALL . "/feeds/$idevent/properties";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets a list of users that liked this event.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @return A list of users {@link WallUserApi}
	 */
		function getLikes($pageNumber = null, $idevent, $itemsPerPage = null) {

			$method = WALL . "/feeds/$idevent/likes";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * User action to "like" this event.
	 */
		function like($idevent) {

			$method = WALL . "/feeds/$idevent/like";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * User action to "unlike" this event.
	 */
		function unlike($idevent) {

			$method = WALL . "/feeds/$idevent/unlike";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Gets the amount of unseen (unread) inbox events for the user. This includes "new comments" on thread the user is participating in and
	 * "likes" received on comments the user has made.
	 * 
	 * @param inboxTypes Comma-separated list of inbox types to return. Default value returns only response to comment type.
	 *            {@link InboxEventApiType}
	 * @param onlyUnread If it is set as "true", it returns only unseen (unread) inbox events count. Default is "false". {@since 3.4}
	 * @return A number indicating the amount of unseen (unread) inbox events.
	 */
		function getInboxCount($inboxTypes = "0", $onlyUnread = "false") {

			$method = WALL . "/inboxcount";

			$verbmethod = "GET";

			$params = array("inboxTypes" => $inboxTypes,
							 "onlyUnread" => $onlyUnread);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = $this->zyncroApi->callApi($method, $params, $verbmethod);

			return $response;
		}

	/**
	 * Gets a list of inbox events of the user. This includes "new comments" on thread the user is participating in and "likes" received on
	 * comments the user has made.
	 * 
	 * @param date POSIX time in milliseconds. Used to filter events by returning events older than this date. This parameter can be used to
	 *            navigate the inbox history. If it is not set, it returns the latest.
	 * @param itemsCount Number of inbox events to return (between 1 and 50). Default set to 10.
	 * @param onlyUnread If it is set as "true", it returns only unseen (unread) inbox events. Default is "false".
	 * @param inboxTypes Comma-separated list of inbox types to return. Default value returns only response to comment type (inboxType = 0).
	 *            {@link InboxEventApiType}
	 * @return A list of inbox events {@link InboxEventApi}
	 */
		function getInbox($date = null, $itemsCount = null, $onlyUnread = "false", $inboxTypes = "0") {

			$method = WALL . "/inbox";

			$verbmethod = "GET";

			$params = array("date" => $date,
							 "itemsCount" => $itemsCount,
							 "onlyUnread" => $onlyUnread,
							 "inboxTypes" => $inboxTypes);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets an inbox event.
	 * 
	 * @return A invox event {@link InboxEventApi}
	 * @since 3.5
	 */
		function getInboxEvent($idinbox) {

			$method = WALL . "/inbox/$idinbox";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Marks all events in inbox as read.
	 */
		function markAllInboxEventAsRead() {

			$method = WALL . "/inbox/markallasread";

			$verbmethod = "POST";

			$params = array();

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Marks an event from the inbox as read.
	 */
		function markInboxEventAsRead($idinbox) {

			$method = WALL . "/inbox/$idinbox/markasread";

			$verbmethod = "POST";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Enables or disables the subscription to event notifications to Company feed, Personal feed, Likes and Follows.
	 * 
	 * @param notificationType Notification type {@link GeneralEmailNotificationApiType}.
	 * @param enabled Determines whether subscription to the notification of this event type is enabled or disabled.
	 * @since 3.5
	 */
		function editNotification($notificationType = null, $enabled = null) {

			$method = WALL . "/notifications";

			$verbmethod = "POST";

			$params = array("notificationType" => $notificationType,
							 "enabled" => $enabled);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Searches messages for a specific text.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10
	 * @param text Text to search in the content of the messages.
	 * @param searchType Filters the search to some sections. Default search in all sections. {@link SearchFilterApiType}
	 * @param groups Comma-separated list of group IDs to filter the search to events including these groups. Default search in all.
	 * @param users Comma-separated list of user IDs to filter the search to events including these users. Default search in all.
	 * @param fromDate POSIX time in milliseconds. Used to filter the search to events from this date. Default search from the start.
	 * @param toDate POSIX time in milliseconds. Used to filter the search to events up to this date. Default search up to now.
	 * @param onlyResultSize Set as true if you only want to know the number of results that are in this search. Default is false.
	 * @param includeHtml Whether the HTML content should be included in the returned events. Default is false.
	 * @param includePropertiesPreview Whether include some properties (attached files, folders...) associated with each event. Default is
	 *            false. {@since 3.5}
	 * @return A list of events {@link EventApi} matching the search.
	 */
		function searchEvents($pageNumber = null, $itemsPerPage = null, $text = null, $searchType = "0", $groups = null, $users = null, $fromDate = null, $toDate = null, $onlyResultSize = "false", $includeHtml = "false", $includePropertiesPreview = "false") {

			$method = WALL . "/search";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "text" => $text,
							 "searchType" => $searchType,
							 "groups" => $groups,
							 "users" => $users,
							 "fromDate" => $fromDate,
							 "toDate" => $toDate,
							 "onlyResultSize" => $onlyResultSize,
							 "includeHtml" => $includeHtml,
							 "includePropertiesPreview" => $includePropertiesPreview);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * User action to "vote" this event.
	 * 
	 * @param voteType Determines if vote is positive, negative or deleted. {@link VoteTypeApi}
	 * @since 4.0
	 */
		function vote($voteType = null, $idevent) {

			$method = WALL . "/feeds/$idevent/vote";

			$verbmethod = "POST";

			$params = array("voteType" => $voteType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>