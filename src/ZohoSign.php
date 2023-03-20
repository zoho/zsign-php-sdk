<?php

namespace zsign;

use CURLFile;
use stdClass;
use zsign\OAuth;
use zsign\ApiClient;
use zsign\SignException;
use zsign\SignUtil;
use zsign\api\Actions;
use zsign\api\Documents;
use zsign\api\Fields;
use zsign\api\PageContext;
use zsign\api\PrefillField;
use zsign\api\RequestObject;
use zsign\api\RequestType;
use zsign\api\TemplateDocumentFields;
use zsign\api\TemplateObject;
use zsign\api\fields\AttachmentField;
use zsign\api\fields\CheckBox;
use zsign\api\fields\DateField;
use zsign\api\fields\DropdownField;
use zsign\api\fields\DropdownValues;
use zsign\api\fields\ImageField;
use zsign\api\fields\RadioField;
use zsign\api\fields\RadioGroup;
use zsign\api\fields\TextField;
use zsign\api\fields\TextProperty;

abstract class ZohoSign{

	static private $users;
	static private $currentUser;
	static private $downloadPath;
	static private $testing=false;

	

	const ALL 		= "_ALL";				// key valid in sdk only
	const SHREDDED	= "shredded";
	const ARCHIVED	= "archived";
	const DELETED 	= "deleted";
	const DRAFT		= "draft";
	const INPROGRESS= "inprogress";
	const RECALLED	= "recalled";
	const COMPLETED	= "completed";
	// const ONHOLD	= "onhold";
	const DECLINED	= "declined";
	const EXPIRED 	= "expired";
	const EXPIRING 	= "expiring";

	const MY_REQUESTS = "_MY_REQUESTS";		// key valid in sdk only
	const MY_PENDING  = "my_pending";



	static function setCurrentUser( Oauth $user ){
		self::$currentUser = $user;
	}

	static function getCurrentUser(){
		return self::$currentUser;	
	}

	static function setTesting( $testing ){
		self::$testing = $testing;
	}
	
	static function getTesting(){
		return self::$testing;	
	}
	//-----------------------_REQUESTS_-----------------------

	public static function getRequest( $requestId ){
		
		$response = ApiClient::callSignAPI( "/api/v1/requests/$requestId", ApiClient::GET, null, null );

		$responseObject = new RequestObject( $response->requests );

		return $responseObject;
	}

	public static function draftRequest( $requestObject, array $files ){

		if( is_a($requestObject, "RequestObject") ){
			throw new SignException("Not an object of 'RequestObject' class", -1);
		}

		$data = new \stdClass();
		$data->requests = $requestObject->constructJson();
		foreach ($files as $index=>$file) {
			if( get_class($file) != "CURLfile" && is_string($file) && substr($file, 0, 1)=="@" ){
				$files[ $index ] = new CURLfile( $file );
			}
		}

		$payload = array( 
			"file"		=> $files[0], 
			"data" 		=> json_encode( $data ),
			"testing"	=> self::getTesting()?'true':'false',
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/requests", 						// api
			ApiClient::POST, 							// post
			null, 										// queryparams
			$payload, 									// post data
			true  										// uploading first file (ALWAYS TRUE)
		);

		$responseJSON = $response->requests;

		array_splice( $files, 0,1 );
		if( count($files) >0 ){
			$response = self::addFilesToRequest( $responseJSON->request_id, $files );
		}

		return new RequestObject( $response->requests );
	}

	public static function updateRequest( $requestObject, array $files=null ){

		if( is_a($requestObject, "RequestObject") ){
			throw new SignException("not an object of 'RequestObject' class", -1);
		}

		$requestId   = $requestObject->getRequestId();
		
		if( !isset($requestId) ){
			throw new SignException("Request Id not set", -1);
		}

		$data = new \stdClass();
		$data->requests = $requestObject->constructJson();

		$payload = array( 
			"data" 		=> json_encode( $data )
		);

		$has_file = count($files) > 0 ? true  : false ;

		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId", 				// api
			ApiClient::PUT, 							// post
			null, 										// queryparams
			$payload, 									// post data
			$has_file 						 			// file present?
		);

		if( count($files) >0 ){
			$response = self::addFilesToRequest( $requestId, $files );
		}

		return new RequestObject( $response );
	}

	public static function addFilesToRequest( $request_id, array $files ){
		/*
			> files are uploaded one at a time using CURL
			> use GUZZLE for multi file upload?
		*/

			foreach ($files as $file) {

				$payload = array(
					"file"		=> $file
				);

				$response = ApiClient::callSignAPI(
					"/api/v1/requests/".$request_id,	// api
					ApiClient::PUT, 					// post
					null, 								// queryparams
					$payload, 							// post data
					true 								// multipartformdata=true
				);

			}
			
		
		return $response;
	}

	public static function submitForSignature( $requestObject ){

		if( is_a($requestObject, "RequestObject") ){
			throw new SignException("not an object of 'RequestObject' class", -1);
		}
		
		$requestId   = $requestObject->getRequestId();
		
		if( !isset($requestId) ){
			throw new SignException("Request Id not set", -1);
		}

		$data = new \stdClass();
		$data->requests = $requestObject->constructJson();

		$payload = array( 
			"data" 		=> json_encode( $data ),
			"testing"	=>	self::$testing?'true':'false',
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/submit", 		// api
			ApiClient::POST, 							// post
			null, 										// queryparams
			$payload 									// post data
		);

		return new RequestObject( $response->requests );
		
	}

	public static function selfSignRequest( $requestObject ){

		if( is_a($requestObject, "RequestObject") ){
			throw new SignException("not an object of 'RequestObject' class", -1);
		}
		
		$requestId   = $requestObject->getRequestId();
		
		if( !isset($requestId) ){
			throw new SignException("Request Id not set", -1);
		}

		$data = new \stdClass();
		$data->requests = $requestObject->constructJson();

		$payload = array( 
			"data" 		=> json_encode( $data )
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/sign", 		// api
			ApiClient::POST, 							// post
			null, 										// queryparams
			$payload 									// post data
		);

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}
		if( $response["request_status"] == "completed" ){
			return true;
		}else{
			return false;
		}
		
	}

	public static function getRequestList( $category, $start_index=0, $row_count=100, $sort_order="DESC", $sort_column="action_time" ){
		$page_context= new \stdClass();
		$page_context->start_index 	= $start_index;
		$page_context->row_count 	= $row_count;
		$page_context->sort_column 	= $sort_column;
		$page_context->sort_order 	= $sort_order;

		$data=new \stdClass();
		$data->page_context			= $page_context;

		$payload = array(
			"data"				=> json_encode($data)
		);

		$myRequest = null;

		switch( $category ){
			
			case "shredded":
			case "archived":
			case "deleted":
			case "draft":
			case "inprogress":
			case "recalled":
			case "completed":
			case "onhold":
			case "declined":
			case "expired":
			case "expiring":
				$payload["request_status"] = $category;
			case "_ALL":
				$response = ApiClient::callSignAPI(
					"/api/v1/requests", 						// api
					ApiClient::GET, 							// post
					$payload, 									// queryparams
					null 										// post data
				);
				$myRequest = false;

				break;

			case "my_pending":
				$payload["request_status"] = $category;
			case "_MY_REQUESTS":
				$response = ApiClient::callSignAPI(
					"/api/v1/myrequests", 						// api
					ApiClient::GET, 							// post
					$payload, 									// queryparams
					null 										// post data
				);
				$myRequest = true;
				break;

			default:
				throw new SignException("Invalid document category", -1);
		}

		$requestsList = array();

		if( $myRequest ){
			foreach ($response->my_requests as $reqJSON) {
				array_push( $requestsList, new RequestObject($reqJSON) );
			}
		}else{
			foreach ($response->requests as $reqJSON) {
				array_push( $requestsList, new RequestObject($reqJSON) );
			}
		}


		return $requestsList;
	
	}

	public static function generateEmbeddedSigningLink( $request_id, $action_id, $host=null ){
		$payload=["host"=>$host];
		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$request_id/actions/$action_id/embedtoken".(is_null($host)?"":"?host=$host"),	// api
			ApiClient::POST, 							// post
			null, 									// queryparams
			$payload 										// post data
		);

		return $response->sign_url;
	}

	public static function getFieldDataFromCompletedDocument( $requestId ){
		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/fielddata",// api
			ApiClient::GET, 						// post
			null, 									// queryparams
			null  									// post data
		);

		// returning only actions : https://www.zoho.com/sign/api/#get-document-form-data
		$actionsArr = array();
		foreach ($response->document_form_data->actions as $key => $action) {
			array_push($actionsArr, new Actions($action));
		}
		return $actionsArr;
		
	}

	public static function setDownloadPath( $path ){
		self::$downloadPath = $path;
	}

	public static function getDownloadPath(){
		if( !isset(self::$downloadPath) ){
			self::$downloadPath = $_SERVER['DOCUMENT_ROOT'];
		}
		if( substr(self::$downloadPath, -1)!="/" ){
			self::$downloadPath .= "/";
		}
		return self::$downloadPath;
	}

	public static function downloadRequest( $requestId, $with_coc=false,$is_merged=false){

		$queryParams= array( 
			"with_coc"	=> $with_coc?'true':'false', 
			"is_merged" => $is_merged?'true':'false',
		);
		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/pdf", 		// api
			ApiClient::GET, 						// post
			$queryParams, 							// queryparams
			null, 									// post data
			false,									// multipartform data
			true 									// response : file type
		);

		if( $response ){
			return true; 
		}else{
			throw new SignException("Failed to download file", -1 );
		}
	}

	public static function downloadDocument( $requestId, $documentId ){
		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/documents/$documentId/pdf", 		// api
			ApiClient::GET, 												// post
			null, 															// queryparams
			null 	, 														// post data
			false,															// multipartform data
			true 															// response : file type
		);

		if( $response ){
			return true; 
		}else{
			throw new SignException("Failed to download file", -1);
		}
	}

	public static function downloadCompletionCertificate( $requestId ){
		$response = ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/completioncertificate", 		// api
			ApiClient::GET, 						// post
			null, 									// queryparams
			null ,  								// post data
			false,									// multipartform data
			true 									// response : file type
		);

		if( $response ){
			return true; 
		}else{
			throw new SignException("Failed to download file", -1);
		}
	}
	//---------------

	public static function recallRequest( $requestId ){
		
		ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/recall", 	// api
			ApiClient::POST, 						// post
			null, 									// queryparams
			null 	 								// post datae
		);

		return true; // returning true is suffice ?
	}

	public static function remindRequest( $requestId ){
		
		ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/remind", 	// api
			ApiClient::POST, 						// post
			null, 									// queryparams
			null  									// post data
		);

		return true; // returning true is suffice ?

	}

	public static function deleteRequest( $requestId ){
		
		ApiClient::callSignAPI(
			"/api/v1/requests/$requestId/delete", 	// api
			ApiClient::PUT, 						// post
			null, 									// queryparams
			null 	 								// post datae
		);

		return true; // returning true is suffice ?

	}
	
	public static function deleteDocument( $documentId ){
		
		ApiClient::callSignAPI(
			"/api/v1/documents/$documentId/delete", // api
			ApiClient::PUT, 						// post
			null, 									// queryparams
			null 	 								// post datae
		);

		return true; // returning true is suffice ?

	}


	// ERROR : data occurs less than minimum occurance of 1
	public static function createNewFolder( $folderName ){
		$data =new \stdClass();
		$folders=new \stdClass();
		$folders->folder_name = $folderName;
		$data->folders=$folders;
		$payload = array(
			"data" => json_encode( $data )
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/folders", 						// api
			ApiClient::POST, 						// post
			null, 									// queryparams
			$payload 								// post data
		);

		return $response->folders->folder_id;  
	}

	public static function getFieldTypes(){

		$response = ApiClient::callSignAPI(
			"/api/v1/fieldtypes", 					// api
			ApiClient::GET, 						// post
			null, 									// queryparams
			null 	 								// post data
		);

		return $response->field_types; // [!!] RETURN AS FIELD OBJEC
	}

	public static function getRequestTypes(){

		$response = ApiClient::callSignAPI(
			"/api/v1/requesttypes", 				// api
			ApiClient::GET, 						// post
			null, 									// queryparams
			null 	 								// post data
		);

		$arr = array();
		foreach ($response->request_types as $key => $request_type) {
			array_push($arr,  new RequestType($request_type) );
		}
		return $arr;

	}

	public static function createRequestType( $var /*requestTypeName or RequestTypeObject*/, $requestTypeDescription="" ){
		
		if( get_class($var)=="RequestType" ){
			$payload = array(
				"data" => json_encode($var->constructJson())
			);
		}else{
			$requestTypeName = $var;
			$payload = array(
				"data" => json_encode( array( 
					"request_types"=>array(
						"request_type_name"	=> $requestTypeName,
						"request_type_description" => $requestTypeDescription
					)
				 ) )
			);
		}
		$response = ApiClient::callSignAPI(
			"/api/v1/requesttypes", 				// api
			ApiClient::POST, 						// post
			null, 									// queryparams
			$payload 	 							// post data
		);

		return new RequestType($response->request_types[0]); // [!!] RETURN AS FIELD OBJECT

	}

	/* // need to revisit the function
	public function updateRequestType( $var , $requestTypeName="", $requestTypeDescription="" ){//requestTypeId
			
		$requestTypeId;

		if( get_class($var)=="RequestType" ){
			$requestTypeId = $var->getRequestTypeId();
			$payload = array(
				"data" => json_encode($var->constructJson())
			);
		}else{
			$requestTypeId = $var;
			$payload = array(
				"data" => json_encode( array( 
					"request_types"=>array(
						"request_type_name"	=> $requestTypeName,
						"request_type_description" => $requestTypeDescription
					)
				 ) )
			);
		}

		$response = ApiClient::callSignAPI(
			"/api/v1/requesttypes/".$requestTypeId, // api
			ApiClient::POST, 						// post
			null, 									// queryparams
			$payload 	 							// post data
		);

		return new RequestType($response->request_types); // [!!] RETURN AS FIELD OBJECT

	}*/

	public static function getFolderList(){

		$response = ApiClient::callSignAPI(
			"/api/v1/folders",					 	// api
			ApiClient::GET, 						// post
			null, 									// queryparams
			null 	 								// post data
		);
		return new RequestType($response->folders); // [!!] RETURN AS FOLDER OBJECT

	}

	public static function extendDocumentValidity($currentUser,$request_id,$extendedDate) //In format (dd MMMM yyyy )
	{

		$payload = array(
			"expire_by" => $extendedDate
		);

		$response = ApiClient::callSignAPI(
			"api/v1/requests/$request_id/extend",					 	// api
			ApiClient::POST, 						// post
			null, 									// queryparams
			$payload 	 								// post data
		);
		if($response["status"]=="success")
		{
			return false;
		}
		return true;
	}

	public static function emailDocument($currentUser,$request_id,array $emails)//only 3 email allowed
	{

		if((!is_array($emails)) || count($emails)>3)
		{
			return false;
		}
		$payload = array(
			"email_id" => $emails
		);
		$response = ApiClient::callSignAPI(
			"api/v1/requests/$request_id/email",    // api
			ApiClient::POST, 						// post
			null, 									// queryparams
			$payload 	 						    // post data
		);
		if($response["status"]=="success")
		{
			return false;
		}
		return true;
	}

	public static function getReminderSettings($request_id)
	{
		$response = ApiClient::callSignAPI(
			"api/v1/requests/$request_id/remindersettings",    // api
			ApiClient::GET, 						// post
			null, 									// queryparams
			null 	 						    // post data
		);
		if($response["status"]=="success")
		{
			return $response;
		}
		return false;
	}

	public static function setReminderSettings($request_id,$reminder_period,$email_reminders=true)
	{
		$data=new \stdClass();
		$settings=new \stdClass();
		$reminders_settings=new \stdClass();
		 
		$reminders_settings->reminder_period=$reminder_period;
		$reminders_settings->email_reminders=$email_reminders;
		$settings->$reminders_settings=$reminders_settings;
		$data->$settings=$$settings;
		$payload = array(
			"data" => json_encode( $data )
		);
		$response = ApiClient::callSignAPI(
			"api/v1/requests/$request_id/remindersettings",    // api
			ApiClient::POST, 						// post
			null, 									// queryparams
			$payload 	 						    // post data
		);
		if($response["status"]=="success")
		{
			return $response;
		}
		return false;
	}



	//-----------------------_TEMPLATES_-----------------------


	public static function createTemplate( $templateObject, array $files ){

		$data = new \stdClass();
		$data->templates = $templateObject->constructJson();

		$payload = array( 
			"file"		=> $files[0],
			"data" 		=> json_encode( $data )
			
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/templates", 						// api
			ApiClient::POST, 							// post
			null, 										// queryparams
			$payload, 									// post data
			true 							 			// file present
		);

		$response_templ = $response->templates;

		$templateId = $response_templ->template_id;
		array_splice( $files, 0, 1 );

		if( count($files) >0 ){
			$response_templ = self::addFilesToTemplate( $templateId, $files );
		}


		return new TemplateObject( $response_templ );
	}

	public static function updateTemplate( $templateObject, $files=null ){

		$templateId   = $templateObject->getTemplateId();
		
		if( !isset($templateId) ){
			throw new SignException("Template Id not set", -1);
		}

		$data = new \stdClass();
		$data->templates = $templateObject->constructJson();

		$payload = array( 
			"data" 		=> json_encode( $data )
		);
		$response = ApiClient::callSignAPI(
			"/api/v1/templates/$templateId", 			// api
			ApiClient::PUT, 							// post
			null, 										// queryparams
			$payload, 									// post data
			false 						 				// file present?
		);

		// array_splice( $files, 0,1 );
		self::addFilesToRequest( $templateId, $files );

		return $response;
	}

	public static function addFilesToTemplate( $template_id, array $files ){

		if( count($files) >0 ){	

			foreach ($files as $file) {

				$payload = array(
					"file"		=> $file
				);

				$response = ApiClient::callSignAPI(
					"/api/v1/templates/".$template_id,	// api
					ApiClient::PUT, 					// post
					null, 								// queryparams
					$payload, 							// post data
					true 								// multipartformdata=true
				);

			}
			
		}
		return $response;
	}

	public static function getTemplate( $templateId ){
		$response = ApiClient::callSignAPI(
			"/api/v1/templates/".$templateId,		// api
			ApiClient::GET, 						// post
			null,	 								// queryparams
			NULL 									// post data
		);

		return new TemplateObject( $response->templates );
	}

	public static function sendTemplate( $templateObj, $quick_send=true ){
		
		$templateId   = $templateObj->getTemplateId();

		if( !isset($templateId) ){
			throw new SignException("Template Id not set", -1);
		}

		$data["templates"] = $templateObj->constructJsonForSubmit();

		$payload = array( 
			"data" 			=> json_encode( $data ),
			"is_quicksend"	=> $quick_send ? 'true' : 'false' ,
			"testing"	=>	self::$testing?'true':'false',
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/templates/$templateId/createdocument", 	// api
			ApiClient::POST, 									// post
			null, 												// queryparams
			$payload 											// post data
		);

		return new RequestObject ( $response->requests  );
	}

	/*
	// Expermental Function for future use
	public static function sendTemplateUsingJson( $templateId, $jsonArr, $quick_send=true ){

		if( !isset($templateId) ){
			throw new SignException("Template Id not set", -1);
		}

		$data["templates"] = $jsonArr;

		$payload = array( 
			"data" 			=> $data,
			"is_quicksend"	=> $quick_send ? 'true' : 'false' 
		);

		$response = ApiClient::callSignAPI(
			"/api/v1/templates/$templateId/createdocument", 	// api
			ApiClient::POST, 									// post
			null, 												// queryparams
			$payload 											// post data
		);

		return new RequestObject ( $response->requests  );
		

	}*/

	public static function getTemplatesList( $start_index=0, $row_count=100, $sort_order="DESC", $sort_column="action_time" ){

		$page_context=new \stdClass();
		$page_context->start_index 	= $start_index;
		$page_context->row_count 	= $row_count;
		$page_context->sort_column 	= $sort_column;
		$page_context->sort_order 	= $sort_order;

		$data=new \stdClass();
		$data->page_context			= $page_context;

		$payload = array(
			"data"	=> json_encode($data)
		);
		
		$response = ApiClient::callSignAPI(
			"/api/v1/templates", 						// api
			ApiClient::GET, 							// post
			$payload, 									// queryparams
			null 										// post data
		);

		// return 
		$templatesList = array();
		foreach ($response->templates as $templateJSON) {
			array_push( $templatesList, new TemplateObject($templateJSON) );
		}

		return $templatesList;
	}

	public static function deleteTemplate( $templateId ){

		$response = ApiClient::callSignAPI(
			"/api/v1/templates/$templateId/delete", // api
			ApiClient::PUT, 						// post
			null, 									// queryparams
			null 	 								// post data
		);

		return true;
	}

}
