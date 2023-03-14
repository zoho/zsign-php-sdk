<?php

namespace zsign\api;

use zsign\api\Fields;

class Actions
{ 
	private $verify_recipient; 
	private $is_bulk; 
	private $action_id; 
	private $action_type; 
	private $private_notes; 
	private $recipient_email; 
	private $signing_order; 
	private $recipient_name; 
	private $fields; // Object of fields class
	private $deleted_fields = array(); // Array of class deleted_fields 
	private $recipient_countrycode; 
	private $recipient_countrycode_iso; 
	private $recipient_phonenumber; 
	private $delivery_mode;

	private $language;
	private $is_embedded; 	private $verification_type;
	private $verification_code;

	const SIGNER 	= "SIGN";
	const VIEWER 	= "VIEW";
	const INPERSON 	= "INPERSONSIGN";
	const APPROVER 	= "APPROVER";

	const EMAIL 	= "EMAIL";
	const OFFLINE	= "OFFLINE";
	const SMS 		= "SMS";
	const EMAIL_SMS = "EMAIL_SMS";

	// used in Templates only
	private $role;

	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->verify_recipient 	= (isset($response["verify_recipient"]))		? $response["verify_recipient"]			: null;
		$this->is_bulk 				= (isset($response["is_bulk"])) 				? $response["is_bulk"] 					: null;
		$this->action_id 			= (isset($response["action_id"])) 				? $response["action_id"] 				: null;
		$this->action_type 			= (isset($response["action_type"])) 			? $response["action_type"] 				: null;
		$this->private_notes 		= (isset($response["private_notes"])) 			? $response["private_notes"] 			: null;
		$this->recipient_email 		= (isset($response["recipient_email"]))			? $response["recipient_email"] 			: null;
		$this->signing_order 		= (isset($response["signing_order"])) 			? $response["signing_order"] 			: null;
		$this->recipient_name 		= (isset($response["recipient_name"])) 			? $response["recipient_name"] 			: null;
		$this->fields 				= (isset($response["fields"])) 					? new Fields($response["fields"]) 		: null;
		$this->recipient_countrycode= (isset($response["recipient_countrycode"])) 	? $response["recipient_countrycode"] 	: null;
		$this->recipient_phonenumber= (isset($response["recipient_phonenumber"])) 	? $response["recipient_phonenumber"] 	: null;

		$this->language 			= (isset($response["language"])) 				? $response["language"] 				: null;
		$this->verification_type	= (isset($response["verification_type"])) 		? $response["verification_type"] 		: null;
		$this->verification_code	= (isset($response["verification_code"])) 		? $response["verification_code"]		: null;
		$this->delivery_mode		= (isset($response["delivery_mode"]))			? $response["delivery_mode"]			: null; //delivery
		// used in Templates only
		$this->role					= (isset($response["role"])) 					? $response["role"] 					: null;
		$this->is_embedded 			= (isset($response["is_embedded"])) 			? $response["is_embedded"] 				: null;

	} 

	// GETTERS

	public function getVerifyRecipient(){
		return $this->verify_recipient;
	} 
 
	public function getIsBulk(){
		return $this->is_bulk;
	} 
 
	public function getActionId(){
		return $this->action_id;
	} 
 
	public function getActionType(){
		return $this->action_type;
	} 
 
	public function getPrivateNotes(){
		return $this->private_notes;
	} 
 
	public function getRecipientEmail(){
		return $this->recipient_email;
	}  

	public function getActionEmail(){
		return $this->recipient_email;
	} 
 
	public function getSigningOrder(){
		return $this->signing_order;
	} 
 
	public function getRecipientName(){
		return $this->recipient_name;
	} 
 	public function getActionName(){
		return $this->recipient_name;
	} 
 
	public function getFields(){
		return $this->fields;
	} 
 
	public function getDeletedFields(){
		return $this->deleted_fields;
	} 
 
	public function getRecipientCountrycode(){
		return $this->recipient_countrycode;
	} 
	
	public function getRecipientCountrycodeISO(){
		return $this->recipient_countrycode_iso;
	} 

	public function getRecipientPhonenumber(){
		return $this->recipient_phonenumber;
	} 

	public function getLanguage(){
		return $this->language;
	}

	public function getVerificationType(){
		return $this->verification_type;
	}

	public function getVerificationCode(){
		return $this->verification_code;
	}

	public function getRole(){
		return $this->role;
	}

	public function getIsEmbedded(){
		return $this->is_embedded;
	}

	public function getDeliveryMode()
	{
		return $this->delivery_mode;
	}
	

	// SETTERS
 
	public function setVerifyRecipient($verify_recipient){
		$this->verify_recipient=$verify_recipient;
	} 
 
	public function setIsBulk($is_bulk){
		$this->is_bulk = $is_bulk;
	} 
 
	public function setActionId($action_id){
		$this->action_id=$action_id;
	} 
 
	public function setActionType($action_type){
		$this->action_type=$action_type;
	} 
 
	public function setPrivateNotes($private_notes){
		$this->private_notes=$private_notes;
	} 
 
	public function setRecipientEmail($recipient_email){
		$this->recipient_email=$recipient_email;
	}  

	public function setActionEmail($recipient_email){
		$this->recipient_email=$recipient_email;
	} 
 
	public function setSigningOrder($signing_order){
		$this->signing_order=$signing_order;
	} 
 
	public function setRecipientName($recipient_name){
		$this->recipient_name=$recipient_name;
	}  
	public function setActionName($recipient_name){
		$this->recipient_name=$recipient_name;
	} 
 
	public function setFields($fields){
		$this->fields=$fields;
	} 
 
	public function setDeletedFields($deleted_fields){
		array_push($this->deleted_fields,$deleted_fields);
	} 
 
	public function setRecipientCountrycode($recipient_countrycode){
		$this->recipient_countrycode=$recipient_countrycode;
	} 
 
	public function setRecipientCountrycodeISO($recipient_countrycode_iso){
		$this->recipient_countrycode_iso=$recipient_countrycode_iso;
	} 

	public function setRecipientPhonenumber($recipient_phonenumber){
		$this->recipient_phonenumber=$recipient_phonenumber;
	} 

	public function setLanguage( $language ){
		$this->language = $language;
	}

	public function setVerificationType( $verification_type ){
		$this->verification_type = $verification_type;
	}
 
	public function setVerificationCode( $verification_code ){
		$this->verification_code = $verification_code;
	}
 
 	public function setRole( $role ){
		$this->role = $role;
	}

	public function setIsEmbedded( $is_embedded ){
		$this->is_embedded = $is_embedded;
	}

	public function setDeliveryMode($delivery_mode)
	{
		$this->delivery_mode=$delivery_mode;
	}
	
	public function constructJson()
	{
		$response["verify_recipient"]=$this->verify_recipient;
		$response["is_bulk"]=$this->is_bulk;
		$response["action_id"]=$this->action_id;
		$response["action_type"]=$this->action_type;
		$response["private_notes"]=$this->private_notes;
		$response["recipient_email"]=$this->recipient_email;
		$response["signing_order"]=$this->signing_order;
		$response["recipient_name"]=$this->recipient_name;
		$response["fields"]= ($this->fields!=NULL) ? $this->fields->constructJson() : NULL ;
		$response["deleted_fields"]= count($this->deleted_fields)!=0 ? $this->deleted_fields : NULL ;
		$response["recipient_countrycode"]=$this->recipient_countrycode;
		$response["recipient_countrycode_iso"]=$this->recipient_countrycode_iso;
		$response["recipient_phonenumber"]=$this->recipient_phonenumber;

		$response["verification_type"]	= $this->verification_type;
		$response["verification_code"]	= $this->verification_code;
		$response["is_embedded"]		= $this->is_embedded;
		$response["language"] 			= $this->language;
		$response["delivery_mode"]		= $this->delivery_mode;
		// only for templates
		$response["role"] 				= $this->role;

		return array_filter( $response, function($v) { return !is_null($v); }  );
	}
}
?>
