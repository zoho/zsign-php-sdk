<?php

namespace zsign\api\fields;

class DropdownValues
{ 
	private $dropdown_value_id; 
	private $dropdown_order; 
	private $dropdown_value; 
	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->dropdown_value_id= (isset($response["dropdown_value_id"])) ? $response["dropdown_value_id"] : null;
		$this->dropdown_order= (isset($response["dropdown_order"])) ? $response["dropdown_order"] : null;
		$this->dropdown_value= (isset($response["dropdown_value"])) ? $response["dropdown_value"] : null;
	} 
	public function getDropdownValueId(){
		return $this->dropdown_value_id;
	} 
 
	public function getDropdownOrder(){
		return $this->dropdown_order;
	} 
 
	public function getDropdownValue(){
		return $this->dropdown_value;
	} 
 
	public function setDropdownValueId($dropdown_value_id){
		$this->dropdown_value_id=$dropdown_value_id;
	} 
 
	public function setDropdownOrder($dropdown_order){
		$this->dropdown_order=$dropdown_order;
	} 
 
	public function setDropdownValue($dropdown_value){
		$this->dropdown_value=$dropdown_value;
	} 
 
	public function constructJson()
	{
		$response["dropdown_value_id"]=$this->dropdown_value_id;
		$response["dropdown_order"]=$this->dropdown_order;
		$response["dropdown_value"]=$this->dropdown_value;
		return array_filter( $response , function($v) { return !is_null($v); });
	}
}
?>