<?php

namespace zsign\api;

class Documents
{ 
	private $document_name; 
	private $document_order; 
	private $document_id; 
	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->document_name= (isset($response["document_name"])) ? $response["document_name"] : null;
		$this->document_order= (isset($response["document_order"])) ? $response["document_order"] : null;
		$this->document_id= (isset($response["document_id"])) ? $response["document_id"] : null;
	} 
	public function getDocumentName(){
		return $this->document_name;
	} 
 
	public function getDocumentOrder(){
		return $this->document_order;
	} 
 
	public function getDocumentId(){
		return $this->document_id;
	} 
 
	public function setDocumentName($document_name){
		$this->document_name=$document_name;
	} 
 
	public function setDocumentOrder($document_order){
		$this->document_order=$document_order;
	} 
 
	public function setDocumentId($document_id){
		$this->document_id=$document_id;
	} 
 
	public function constructJson()
	{
		$response["document_name"]=$this->document_name;
		$response["document_order"]=$this->document_order;
		$response["document_id"]=$this->document_id;
		return array_filter( $response, function($v) { return !is_null($v); } );
		
	}
}
?>