<?php
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
	 * @return A list of events {@link EventApi}
	 */
	 
	include_once "lib/config.php";
	include_once "lib/Api/WallApiService.php";
	
	$dsf->start(new WallApiService($dsf));
	
	function loginByUserPass($email,$pass){
		global $dsf;
		
		$dsf->controller->zyncroApi->email = $email;
		$dsf->controller->zyncroApi->pass = $pass;
		$dsf->controller->zyncroApi->logintype = 'userpass';	
	}
	
	function loginBySession($urnsession){
		global $dsf;
		
		$dsf->controller->zyncroApi->urnsession = $urnsession;
		$dsf->controller->zyncroApi->logintype = 'session';			
	}
	
	function loginByThreelegged(){
		global $dsf;
		
		$dsf->controller->zyncroApi->logintype = 'threelegged';	
	}

	loginByUserPass('email@domain.xx','password');
	
	print_r ($dsf->controller->getGroupEvents(null, 'syncrum:sharegroup:xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx', '10'));			
?>