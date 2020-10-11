<?php

namespace zsign\api;

class RequestType{

	private $request_type_id;
	private $request_type_name;
	private $request_type_description;
	
	function __construct( $response = null ) {

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->request_type_id				 = isset( $response["request_type_id"] ) 			? $response["request_type_id"]			: null ; 
		$this->request_type_name			 = isset( $response["request_type_name"] ) 			? $response["request_type_name"] 		: null ; 
		$this->request_type_description		 = isset( $response["request_type_description"] )	? $response["request_type_description"] : null ; 

	}

	public function getRequestTypeId(){
		return $this->request_type_id;
	}

	public function getRequestTypeName(){
		return $this->request_type_name;
	}

	public function getRequestTypeDescription(){
		return $this->request_type_description;
	}

	public function setRequestTypeId( $request_type_id ){
		$this->request_type_id = $request_type_id ;
	}

	public function setRequestTypeName( $request_type_name ){
		$this->request_type_name = $request_type_name ;
	}

	public function setRequestTypeDescription( $request_type_description  ){
		$this->request_type_description = $request_type_description ;
	}

	public function constructJson(){
		$response = array();
		$response["request_type_id"] = isset($this->request_type_id) ? $this->request_type_id : null;
		$response["request_type_name"] = $this->request_type_name;
		$response["request_type_description"] = $this->request_type_description;

		$response1[ "request_types" ] = array_filter( $response, function($v) { return !is_null($v); } );

		return $response1 ;
		
	}

}