<?php

namespace zsign\api;


use zsign\SignException;
use zsign\api\Actions;
use zsign\api\Documents;
use zsign\api\PrefillField;
use zsign\api\TemplateDocumentFields;


class TemplateObject
{ 

	// template creation variable
	private $field_data;
	private $request_name;
	private $bulk_actions;
	private $is_bulk;
	private $default_fields;
	private $custom_data;

	// template get details
	private $actions = array();
	private $created_time;
	private $description;
	private $document_fields = array();
	private $document_ids = array();
	private $email_reminders;
	private $expiration_days;
	private $is_sequential;
	private $modified_time;
	private $notes;

	private $owner_email;
	private $owner_first_name;
	private $owner_id;
	private $owner_last_name;

	private $reminder_period;
	private $request_type_id;
	private $request_type_name;
	private $template_id;//not part of constructedJSON;
	private $template_name;
	private $validity;
	private $folder_id;
	private $self_sign;
	private $deleted_actions;
	private $page_num;
	private $expiration_alert_period;
	private $bulk_template_id;

	private $field_text_data;
	private $field_boolean_data;
	private $field_date_data;
	// private $field_image_data;
	private $field_radio_data;

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
		$this->created_time 		= (isset($response["created_time"]))	 	? $response["created_time"]			: null;
		$this->modified_time 		= (isset($response["modified_time"]))	 	? $response["modified_time"]		: null;


		$this->template_id 			= (isset($response["template_id"]))	 		? $response["template_id"]			: null;
		$this->template_name 		= (isset($response["template_name"])) 		? $response["template_name"]		: null;
		$this->email_reminders 		= (isset($response["email_reminders"])) 	? $response["email_reminders"]		: null;
		$this->notes 				= (isset($response["notes"])) 				? $response["notes"] 				: null;
		$this->reminder_period 		= (isset($response["reminder_period"]))		? $response["reminder_period"]		: null;
		$this->expiration_days 		= (isset($response["expiration_days"])) 	? $response["expiration_days"]		: null;
		$this->is_sequential 		= (isset($response["is_sequential"])) 		? $response["is_sequential"]		: null;
		$this->description 			= (isset($response["description"])) 		? $response["description"]			: null;
		$this->validity 			= (isset($response["validity"])) 			? $response["validity"]				: null;
		$this->request_type_id  	= (isset($response["request_type_id"])) 	? $response["request_type_id"]		: null;
		$this->request_type_name  	= (isset($response["request_type_name"])) 	? $response["request_type_name"]	: null;
		$this->folder_id		  	= (isset($response["folder_id"])) 			? $response["folder_id"]			: null;
		$this->self_sign		  	= (isset($response["self_sign"]))		 	? $response["self_sign"]			: false;

		$this->bulk_actions		  	= (isset($response["bulk_actions"])) 		? $response["bulk_actions"]			: null;
		$this->is_bulk 			  	= (isset($response["is_bulk"]))			 	? $response["is_bulk"]				: null;
		$this->bulk_template_id  	= (isset($response["bulk_template_id"])) 	? $response["bulk_template_id"]		: null;
		$this->custom_data 		  	= (isset($response["custom_data"]))		 	? $response["custom_data"] : "Sent Using Zoho Sign PHP SDK.";

		$this->owner_email		  	= (isset($response["owner_email"])) 		? $response["owner_email"]			: null;
		$this->owner_first_name   	= (isset($response["owner_first_name"])) 	? $response["owner_first_name"]		: null;
		$this->owner_last_name  	= (isset($response["owner_last_name"])) 	? $response["owner_last_name"]		: null;
		$this->owner_id 		  	= (isset($response["owner_id"]))		 	? $response["owner_id"] 			: null;


	

		// $this->prefill_fields 		= array();
			$this->field_text_data 		= array();
			$this->field_boolean_data	= array();
			$this->field_date_data		= array();
			$this->field_radio_data		= array();
			// $this->field_image_data		= array();

		$this->document_fields 		= array();

		if( isset( $response["document_fields"] ) ){
			foreach($response["document_fields"] as $obj) // obj = templates>docuemnt_fields>fields[i] = field
			{
				array_push($this->document_fields,new TemplateDocumentFields($obj));

				foreach ($obj["fields"] as $field) {

					switch( $field["field_category"] ){
						case "checkbox":
							$this->field_boolean_data[ $field["field_label"] ] = new PrefillField($field);
							break;
						case "textfield":
						case "dropdown":
							$this->field_text_data 	 [ $field["field_label"] ] 	= new PrefillField($field);
							break;
						case "datefield":
							$this->field_date_data   [ $field["field_label"] ]	= new PrefillField($field) ;
							break;
						case "radiogroup":
							$this->field_radio_data   [ $field["field_label"] ]	= new PrefillField($field) ;
							break;
								
							
					}
				}

			}
		}

	} 

	// --------- GETTERS ---------

	public function getTemplateId(){
		return $this->template_id;
	}

	public function getTemplateName(){
		return $this->template_name;
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

	public function getCreatedTime(){
		return $this->created_time;
	}

	public function getModifiedTime(){
		return $this->modified_time;
	}

	public function getDocumentFields(){
		return $this->document_fields;
	}

	public function getRequestName(){
		return $this->request_name;
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
	// --------- SETTERS ---------

	public function setTemplateId( $template_id ){
		$this->template_id = $template_id;
	}
 
	public function setTemplateName($template_name){
		$this->template_name=$template_name;
	} 

	public function setSelfSign( $self_sign ){
		$this->self_sign = $self_sign;
	}
 
	public function setAutomaticReminders($email_reminders){	// alias : Email reminders
		$this->email_reminders=$email_reminders;				// In ref. to UI
	} 

	public function setEmailReminders( $email_reminders ){		// DUPLICATE
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
 
 	public function setCreatedTime( $created_time ){
		$this->created_time = $created_time ;
	}

	public function setModifiedTime($modified_time){
		$this->modified_time = $modified_time ;
	}

	public function setRequestName( $requestName ){
		$this->request_name = $requestName;
	}

	/// ------------ FIELD DATA MANIPULATION FUNCITONS ------------

	// get pre-fill data (All or a specific label)

	public function getPrefillTextField( $field_label=null ){
		if( $field_label==null ){
			return $this->field_text_data;
		}else{
			return $this->field_text_data[$field_label];
		}
	}

	public function getPrefillBooleanField( $field_label=null ){
		if( $field_label==null ){
			return $this->field_boolean_data;
		}else{
			return $this->field_boolean_data[$field_label];
		}
	}

	public function getPrefillDateField( $field_label=null ){
		if( $field_label==null ){
			return $this->field_date_data;
		}else{
			return $this->field_date_data[$field_label];
		}
	}
	
	public function getFieldRadioData($field_label=null){
		if( $field_label==null ){
			return $this->field_radio_data;
		}else{
			return $this->field_radio_data[$field_label];
		}
	}
	/*
	public function getFieldImageData(){
		return $this->field_image_data;
	}
	*/

	// -------- setters ---------
	public function setPrefillTextField( $label, $value ){
		$this->field_text_data [ $label ]=($value) ;
	}

	public function setPrefillBooleanField( $label, $value ){
		$this->field_boolean_data 	[ $label ]=($value) ;
	}

	public function setPrefillDateField( $label, $value ){
		$this->field_date_data 		[ $label ]=($value) ;
	}

	public function setPrefillRadioField( $label, $value ){
		$this->field_radio_data [ $label ]=($value) ;
	}
	
	/// ------------ ACTION DATA MANIPULATION FUNCITONS ------------
	public function getActionByRole( $role ){

		foreach ( $this->actions as $action ) {
			if( $action->getRole() == $role ){
				return $action;
			}
		}

		// If no action by role, likely he's making a mistake.
		throw new SignException("Invalid Role Name", -1);
	}

	// -------- construct  -------

	public function constructJson()
	{
		// template_id not to be included
		$response["template_name"]=$this->template_name;
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
		$response["request_type_id"]=(int)$this->request_type_id;
		$actionsArr = array();
		foreach($this->actions as $obj)
		{
			array_push($actionsArr,$obj->constructJson());
		}
		$response["actions"]= count($actionsArr)!=0 ? $actionsArr : NULL ;
		$response["deleted_actions"]= count($this->deleted_actions)!=0 ? $this->deleted_actions : NULL ;

		$response["folder_id"]		= $this->folder_id;
		// $response["self_sign"]		= $this->self_sign; //not present in security XML
		$response["bulk_actions"]	= $this->bulk_actions;
		$response["is_bulk"]		= $this->is_bulk;
		

		return array_filter( $response, function($v) { return !is_null($v); } );
	}

	public function constructJsonForSubmit(){
		$field_text_data_Obj = array();
		foreach ($this->field_text_data as $label=>$value) {
			$field_text_data_Obj[ $label ] 		= $value;
		}
		
		$field_boolean_data_Obj = array();
		foreach ($this->field_boolean_data as $label=>$value) {
			$field_boolean_data_Obj[  $label  ] 	= $value;
		}
		
		$field_date_data_Obj = array();
		foreach ($this->field_date_data as  $label=>$value) {
			$field_date_data_Obj[  $label  ] 		= $value;
		}

		$field_radio_data_Obj = array();
		foreach ($this->field_radio_data as  $label=>$value) {
			$field_radio_data_Obj[  $label  ] 		= $value;
		}
		
		$field_data= new \stdClass();   //new add
		$field_data->field_text_data 	= (count($field_text_data_Obj) == 0) ? new \stdClass() : $field_text_data_Obj;
		$field_data->field_boolean_data = (count($field_boolean_data_Obj)==0)? new \stdClass() : $field_boolean_data_Obj;
		$field_data->field_date_data 	= (count($field_date_data_Obj)==0) ?   new \stdClass() : $field_date_data_Obj;		
		$field_data->field_radio_data 	= (count($field_radio_data_Obj)==0) ?   new \stdClass() : $field_radio_data_Obj;


		$actionsArr = array();
		foreach($this->actions as $obj)
		{
			$obj->setFields( null );
			array_push($actionsArr,$obj->constructJson());
		}



		$templates["field_data"] 		= $field_data;
		$templates["actions"] 			= count($actionsArr)!=0 ? $actionsArr : NULL ;
		$templates["notes"] 		 	= $this->notes;
		$templates["request_name"] 		= (isset( $this->request_name ) && $this->request_name!="") ? $this->request_name : $this->template_name;
		$templates["custom_data"]	 	= $this->custom_data;
		$templates["is_bulk"] 		 	= $this->is_bulk;
		//default_fields

		// $templateJSON["templates"] 		= $templates;

		return array_filter( $templates, function($v) { return !is_null($v); } );
	}
}


