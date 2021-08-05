<?php

namespace zsign\api;

class RedirectPages
{ 
	private $sign_success; 
	private $sign_failure; 
	private $sign_later; 
	private $sign_declined; 
	private $sign_completed; 
	private $sign_forwarded; 

	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->sign_success = (isset($response["sign_success"]))? $response["sign_success"]: null;
		$this->sign_failure = (isset($response["sign_failure"]))? $response["sign_failure"]: null;
		$this->sign_later = (isset($response["sign_later"]))? $response["sign_later"]: null;
		$this->sign_declined = (isset($response["sign_declined"]))? $response["sign_declined"]: null;
		$this->sign_completed = (isset($response["sign_completed"]))? $response["sign_completed"]: null;
		$this->sign_forwarded = (isset($response["sign_forwarded"]))? $response["sign_forwarded"]: null;
	} 

	// GETTERS

	public function getSignSuccess(){
		return $this->sign_success;
	} 
 
	public function getSignFailure(){
		return $this->sign_failure;
	} 
 
	public function getSignLater(){
		return $this->sign_later;
	} 
 
	public function getSignDeclined(){
		return $this->sign_declined;
	} 
 
	public function getSignCompleted(){
		return $this->sign_completed;
	} 
 
	public function getSignForwarded(){
		return $this->sign_forwarded;
	}

	// SETTERS
 
	public function setSignSuccess($sign_success){
		$this->sign_success=$sign_success;
	} 
 
	public function setSignFailure($sign_failure){
		$this->sign_failure=$sign_failure;
	} 
 
	public function setSignLater($sign_later){
		$this->sign_later=$sign_later;
	} 
 
	public function setSignDeclined($sign_declined){
		$this->sign_declined=$sign_declined;
	} 
 
	public function setSignCompleted($sign_completed){
		$this->sign_completed=$sign_completed;
	} 
 
	public function setSignForwarded($sign_forwarded){
		$this->sign_forwarded=$sign_forwarded;
	}

	public function constructJson()
	{
		$response["sign_success"]=$this->sign_success;
		$response["sign_failure"]=$this->sign_failure;
		$response["sign_later"]=$this->sign_later;
		$response["sign_declined"]=$this->sign_declined;
		$response["sign_completed"]=$this->sign_completed;
		$response["sign_forwarded"]=$this->sign_forwarded;
		return array_filter( $response, function($v) { return !is_null($v); }  );
	}
}
?>
