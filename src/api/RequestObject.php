<?php

namespace zsign\api;

use zsign\api\Actions;
use zsign\api\Documents;

class RequestObject
{ 

	private $request_id;	//not part of constructedJSON;
	private $request_status;
	private $owner_email;
	private $owner_first_name;
	private $owner_id;
	private $owner_last_name;
	private $created_time;
	private $modified_time;

	private $request_name; 
	private $email_reminders; 
	private $document_ids = array(); // Array of class document_ids 
	private $notes; 
	private $reminder_period; 
	private $expiration_days; 
	private $is_sequential; 
	private $description; 
	private $validity; 
	private $request_type_id; 
	private $actions = array(); // Array of class actions 
	private $deleted_actions = array(); // Array of class deleted_actions 
	private $page_num;
	//to include
	private $folder_id;
	private $self_sign;
	private $expiration_alert_period;
	private $bulk_actions;
	private $is_bulk;
	private $bulk_request_id;
	// private $field_detect_action;
	// private $disable_forward;
	private $custom_data;
	private $redirect_pages;
	// private $send_completed_document;

	function __construct($response=null)
	{
		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->document_ids 		= array();
		if( isset( $response["document_ids"] ) ){
			foreach($response["document_ids"] as $obj){
				array_push($this->document_ids,new Documents($obj));
			}
		}
		$this->actions 				= array();
		if( isset( $response["actions"] ) ){
			foreach($response["actions"] as $obj)
			{
				array_push($this->actions,new Actions($obj));
			}
		}


		$this->request_id 			= (isset($response["request_id"]))	 		? $response["request_id"]			: null;
		$this->request_status 		= (isset($response["request_status"]))	 	? $response["request_status"]		: null;

		$this->request_name 		= (isset($response["request_name"])) 		? $response["request_name"]			: null;
		$this->email_reminders 		= (isset($response["email_reminders"])) 	? $response["email_reminders"]		: null;
		$this->notes 				= (isset($response["notes"])) 				? $response["notes"] 				: null;
		$this->reminder_period 		= (isset($response["reminder_period"]))		? $response["reminder_period"]		: null;
		$this->expiration_days 		= (isset($response["expiration_days"])) 	? $response["expiration_days"]		: null;
		$this->is_sequential 		= (isset($response["is_sequential"])) 		? $response["is_sequential"]		: null;
		$this->description 			= (isset($response["description"])) 		? $response["description"]			: null;
		$this->validity 			= (isset($response["validity"])) 			? $response["validity"]				: null;
		$this->request_type_id  	= (isset($response["request_type_id"])) 	? $response["request_type_id"]		: null;
		
		$this->folder_id		  	= (isset($response["folder_id"])) 			? $response["folder_id"]			: null;
		$this->self_sign		  	= (isset($response["self_sign"]))		 	? $response["self_sign"]			: false;

		// to be impleted
		$this->bulk_actions		  	= (isset($response["bulk_actions"])) 		? $response["bulk_actions"]			: null;
		$this->is_bulk 			  	= (isset($response["is_bulk"]))			 	? $response["is_bulk"]				: null;
		$this->bulk_request_id  	= (isset($response["bulk_request_id"])) 	? $response["bulk_request_id"]		: null;

		$this->custom_data 		  	= (isset($response["custom_data"]))		 	? $response["custom_data"] : "Sent Using Zoho Sign PHP SDK.";

		$this->owner_email		  	= (isset($response["owner_email"])) 		? $response["owner_email"]			: null;
		$this->owner_first_name   	= (isset($response["owner_first_name"])) 	? $response["owner_first_name"]		: null;
		$this->owner_last_name  	= (isset($response["owner_last_name"])) 	? $response["owner_last_name"]		: null;
		$this->owner_id 		  	= (isset($response["owner_id"]))		 	? $response["owner_id"] 			: null;
		$this->created_time 		= (isset($response["created_time"]))	 	? $response["created_time"]			: null;
		$this->modified_time 		= (isset($response["modified_time"]))	 	? $response["modified_time"]		: null;

		
	} 

	// Getters

	public function getRequestId(){
		return $this->request_id;
	}

	public function getRequestStatus(){
		return $this->request_status;
	}

	public function getRequestName(){
		return $this->request_name;
	} 
 
	public function getSelfSign(){
		return $this->self_sign;
	}

	public function getAutomaiticReminders(){		// alias : Email reminders
		return $this->email_reminders;
	} 
 
	public function getDocumentIds(){
		return $this->document_ids;
	} 
 
	public function getNotes(){
		return $this->notes;
	} 
 
	public function getReminderPeriod(){
		return $this->reminder_period;
	} 
 
	public function getRedirectPages(){
		return $this->redirect_pages; 
	}

	public function getExpirationDays(){
		return $this->expiration_days;
	} 
 
	public function getSequentialSigning(){
		return $this->is_sequential;
	} 
 
	public function getDescription(){
		return $this->description;
	} 
 
	public function getValidity(){
		return $this->validity;
	} 
 
	public function getRequestTypeId(){
		return $this->request_type_id;
	} 
 
	public function getActions(){
		return $this->actions;
	} 
 
	public function getDeleted_actions(){
		return $this->deleted_actions;
	} 

	public function getPageNum(){ 
		return $this->page_num; 
	}

	public function getExpirationAlertPeriod(){
		return $this->expiration_alert_period; 
	}

	public function getBulkActions(){
		return $this->bulk_actions; 
	}

	public function getIsBulk(){ 
		return $this->is_bulk; 
	}

	public function getCustomData(){
		return $this->custom_data; 
	}


	public function getOwnerEmail(){
		return $this->owner_email;
	}

	public function getOwnerFirstName(){
		return $this->owner_first_name;
	}

	public function getOwnerId(){
		return $this->owner_id;
	}

	public function getOwnerLastName(){
		return $this->owner_last_name;
	}
	
	public function getCreatedTime(){
		return $this->created_time;
	}

	public function getModifiedTime(){
		return $this->modified_time;
	}

	
	// Setters	

	public function setRequestId( $request_id ){
		$this->request_id = $request_id;
	}
 
	public function setRequestName($request_name){
		$this->request_name=$request_name;
	} 

	public function setSelfSign( $self_sign ){
		$this->self_sign = $self_sign;
	}
 
	public function setAutomaticReminders($email_reminders){	// alias : Email reminders
		$this->email_reminders=$email_reminders;
	} 
 
	public function setDocumentIds($document_ids){
		array_push($this->document_ids,$document_ids);
	} 
 
	public function setNotes($notes){
		$this->notes=$notes;
	} 
 
	public function setReminderPeriod($reminder_period){
		$this->reminder_period=$reminder_period;
	} 
 
	public function setExpirationDays($expiration_days){
		$this->expiration_days=$expiration_days;
	} 
 
	public function setSequentialSigning($is_sequential){
		$this->is_sequential=$is_sequential;
	} 
 
	public function setDescription($description){
		$this->description=$description;
	} 
 
	public function setValidity($validity){
		$this->validity=$validity;
	} 
 
	public function setRequestTypeId($request_type_id){
		$this->request_type_id=$request_type_id;
	} 
 
	public function addAction( $action ){
		array_push($this->actions,$action);		
	}

	public function setActions($actions){
		$this->actions = $actions;
	} 
 
	public function setDeletedActions($deleted_actions){
		array_push( $this->deleted_actions, $deleted_actions );
	} 

	public function setPageNum( $page_num ){ 
		$this->page_num = $page_num ; 
	}

	public function setExpirationAlertPeriod($expiration_alert_period){
		$this->expiration_alert_period = $expiration_alert_period; 
	}

	public function setBulkActions( $bulk_actions ){
		$this->bulk_actions = $bulk_actions; 
	}

	public function setIsBulk( $is_bulk ){ 
		$this->is_bulk = $is_bulk; 
	}

	public function setCustomData( $custom_data ){
		$this->custom_data = $custom_data; 
	}

	public function setRedirectPages( $redirect_pages ){
		$this->redirect_pages = $redirect_pages; 
	}
 
	public function constructJson()
	{
		// request_id not to be included

		$response["request_name"]=$this->request_name;
		$response["email_reminders"]=$this->email_reminders;
		$document_idsArr = array();
		foreach($this->document_ids as $obj)
		{
			array_push($document_idsArr,$obj->constructJson());
		}
		$response["document_ids"]= count($document_idsArr)!=0 ? $document_idsArr : NULL ;
		$response["notes"]=$this->notes;
		$response["reminder_period"]=$this->reminder_period;
		$response["expiration_days"]=$this->expiration_days;
		$response["is_sequential"]=$this->is_sequential;
		$response["description"]=$this->description;
		$response["validity"]=$this->validity;
		$response["request_type_id"]=$this->request_type_id;
		$actionsArr = array();
		foreach($this->actions as $obj)
		{
			array_push($actionsArr,$obj->constructJson());
		}
		$response["actions"]= count($actionsArr)!=0 ? $actionsArr : NULL ;
		// $response["actions"]=$actionsArr;
		$response["deleted_actions"]=$this->deleted_actions;
		$response["deleted_actions"]= count($this->deleted_actions)!=0 ? $this->deleted_actions : NULL ;

		$response["folder_id"]		= $this->folder_id;
		$response["self_sign"]		= $this->self_sign;
		$response["bulk_actions"]	= $this->bulk_actions;
		$response["is_bulk"]		= $this->is_bulk;
		// $response["bulk_request_id"]= $this->bulk_request_id;
		$response["custom_data"]	= $this->custom_data;
		$response["redirect_pages"]     = is_null($this->redirect_pages) ? NULL : $this->redirect_pages->constructJson();
		return array_filter( $response, function($v) { return !is_null($v); } );
	}
}
