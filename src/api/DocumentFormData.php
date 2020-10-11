<?php

namespace zsign\api;


class DocumentFormData{
	
	private $field_label;
	private $field_name;
	private $field_value;

	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->field_label	= (isset($response["field_label"])) ? $response["field_label"] 	: null;
		$this->field_name	= (isset($response["field_name"])) 	? $response["field_name"] 	: null;
		$this->field_value	= (isset($response["field_value"]))	? $response["field_value"] 	: null;

	}

	public function getFieldLabel(){
		return $this->field_label; 
	}

	public function getFieldName(){
		return $this->field_name; 
	}

	public function getFieldValue(){
		return $this->field_value; 
	}

	/*
	// setters not allowed
	public function setFieldLabel( $field_label ){
		$this->field_label = $field_label; 
	}

	public function setFieldName( $field_name ){
		$this->field_name = $field_name; 
	}

	public function setFieldValue( $field_value ){
		$this->field_value = $field_value; 
	}*/



}