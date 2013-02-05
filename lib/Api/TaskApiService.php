<?php
	include_once "../config.php";
	class TaskApiService extends DSF_Controller { 
		public $zyncroApi;

		function public_default() {
			$this->zyncroApi = $this->dsf->newLibrary("oAuth",'');
		}

	/**
	 * Gets all the tasks the user "can see", from groups/departments he belongs.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10.
	 * @param taskType Type of the task. {@link TaskListFilterApiType} {@since 4.0}
	 * @param groups Comma-separated list of group IDs to filter. {@since 4.0}
	 * @param orderField Order field. Default set to NAME. {@link TaskInfoFieldFilterApiType} {@since 4.0}
	 * @param orderType Order type. Default set to ASC. {@link OrderFilterApiType} {@since 4.0}
	 * @return A list of tasks {@link TaskApi}
	 * @since 3.4
	 */
		function getTasks($pageNumber = null, $itemsPerPage = null, $groups = null, $taskType = null, $orderField = null, $orderType = null) {

			$method = TASKS . "/";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "groups" => $groups,
							 "taskType" => $taskType,
							 "orderField" => $orderField,
							 "orderType" => $orderType);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Gets a task.
	 * 
	 * @return A task {@link TaskApi}
	 * @since 3.4
	 */
		function getTask($idtask) {

			$method = TASKS . "/$idtask";

			$verbmethod = "GET";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Creates a new task
	 * 
	 * @param title The title of the task to create
	 * @param description The description of the task to create
	 * @param responsible The ID of the user responsible of the task to create
	 * @param responsibleText The responsible text of the task to create
	 * @param dueDate The due date of the task to create (POSIX time in milliseconds)
	 * @param type The title type the task to create {@link TaskApiType}
	 * @param status The status of the task to create {@link TaskStatusApiType}
	 * @param following Whether or not follow this task in meetings
	 * @param urnGroup The group/department of the task to create
	 * @param eventUrn The event associated of the task to create
	 * @since 3.4
	 */
		function createTask($title = null, $description = null, $responsible = null, $responsibleText = null, $dueDate = null, $type = null, $status = "0", $following = null, $urnGroup = null, $eventUrn = null) {

			$method = TASKS . "/";

			$verbmethod = "POST";

			$params = array("title" => $title,
							 "description" => $description,
							 "responsible" => $responsible,
							 "responsibleText" => $responsibleText,
							 "dueDate" => $dueDate,
							 "type" => $type,
							 "status" => $status,
							 "following" => $following,
							 "urnGroup" => $urnGroup,
							 "eventUrn" => $eventUrn);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Edits and existing task
	 * 
	 * @param title The title of the task to edit
	 * @param description The description of the task to edit
	 * @param responsible The ID of the user responsible of the task to edit
	 * @param responsibleText The responsible text of the task to edit
	 * @param dueDate The due date of the task to edit (POSIX time in milliseconds)
	 * @param type The title type the task to edit {@link TaskApiType}
	 * @param status The status of the task to edit {@link TaskStatusApiType}
	 * @param following Whether or not follow this task in meetings
	 * @since 3.4
	 */
		function editTask($idtask, $title = null, $description = null, $responsible = null, $responsibleText = null, $dueDate = null, $type = null, $status = null, $following = null) {

			$method = TASKS . "/$idtask";

			$verbmethod = "POST";

			$params = array("title" => $title,
							 "description" => $description,
							 "responsible" => $responsible,
							 "responsibleText" => $responsibleText,
							 "dueDate" => $dueDate,
							 "type" => $type,
							 "status" => $status,
							 "following" => $following);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Deletes a task
	 * 
	 * @since 3.4
	 */
		function deleteTask($idtask) {

			$method = TASKS . "/$idtask";

			$verbmethod = "DELETE";

			$params = array();

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

	/**
	 * Search tasks.
	 * 
	 * @param pageNumber Page number to return. Default set to 1.
	 * @param itemsPerPage Number of items to return per page (between 1 and 50). Default set to 10
	 * @param text Text to search in the content of the messages.
	 * @param searchType Type of search.
	 * @param groups Comma-separated list of group IDs to filter the search to events including these groups. Default search in all.
	 * @param users Comma-separated list of user IDs to filter the search to events including these users. Default search in all.
	 * @param fromDate POSIX time in milliseconds. Used to filter the search to events from this date. Default search from the start.
	 * @param toDate POSIX time in milliseconds. Used to filter the search to events up to this date. Default search up to now.
	 * @param onlyResultSize Set as true if you only want to know the number of results that are in this search. Default is false.
	 * @return A list of events {@link EventApi} matching the search.
	 * @since 4.0
	 */
		function searchTasks($pageNumber = null, $itemsPerPage = null, $text = null, $searchType = null, $groups = null, $users = null, $fromDate = null, $toDate = null, $onlyResultSize = "false") {

			$method = TASKS . "/search";

			$verbmethod = "GET";

			$params = array("pageNumber" => $pageNumber,
							 "itemsPerPage" => $itemsPerPage,
							 "text" => $text,
							 "searchType" => $searchType,
							 "groups" => $groups,
							 "users" => $users,
							 "fromDate" => $fromDate,
							 "toDate" => $toDate,
							 "onlyResultSize" => $onlyResultSize);

			$params = array_filter($params, function($item) { return !is_null($item); });

			$response = json_decode($this->zyncroApi->callApi( $method, $params, $verbmethod), true);

			return $response;
		}

}
?>