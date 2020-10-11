<?php

namespace zsign\api\fields;

use zsign\api\fields\RadioField;

class RadioGroup
{ 
	private $field_id; 
	private $field_type_id; 
	private $field_type_name; 
	private $action_id; 
	private $sub_fields = array(); // Array of class sub_fields 
	private $field_category; 
	private $field_label; 
	private $is_mandatory; 
	private $is_read_only; 
	private $page_no; 
	private $description_tooltip; 
	private $document_id; 
	private $field_name; 

	const RADIOGROUP = "Radiogroup";
	
	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->field_id= (isset($response["field_id"])) ? $response["field_id"] : null;
		$this->field_type_id= (isset($response["field_type_id"])) ? $response["field_type_id"] : null;
		$this->field_type_name= (isset($response["field_type_name"])) ? $response["field_type_name"] : self::RADIOGROUP;
		$this->action_id= (isset($response["action_id"])) ? $response["action_id"] : null;
		$this->sub_fields= array();
		if( $response["sub_fields"] ){
			foreach($response["sub_fields"] as $obj)
			{
				array_push($this->sub_fields,new RadioField($obj));
			}
		}
		$this->field_category= (isset($response["field_category"])) ? $response["field_category"] : null;
		$this->field_label= (isset($response["field_label"])) ? $response["field_label"] : null;
		$this->is_mandatory= (isset($response["is_mandatory"])) ? $response["is_mandatory"] : null;
		$this->is_read_only= (isset($response["is_read_only"])) ? $response["is_read_only"] : null;
		$this->page_no= (isset($response["page_no"])) ? $response["page_no"] : null;
		$this->description_tooltip= (isset($response["description_tooltip"])) ? $response["description_tooltip"] : null;
		$this->document_id= (isset($response["document_id"])) ? $response["document_id"] : null;
		$this->field_name= (isset($response["field_name"])) ? $response["field_name"] : null;
	} 
	public function getFieldId(){
		return $this->field_id;
	} 
 
	public function getFieldTypeId(){
		return $this->field_type_id;
	} 
 
	public function getFieldTypeName(){
		return $this->field_type_name;
	} 
 
	public function getActionId(){
		return $this->action_id;
	} 
 
	public function getSubFields(){
		return $this->sub_fields;
	} 
 
	public function getFieldCategory(){
		return $this->field_category;
	} 
 
	public function getFieldLabel(){
		return $this->field_label;
	} 
 
	public function getIsMandatory(){
		return $this->is_mandatory;
	} 
 
	public function getIsReadOnly(){
		return $this->is_read_only;
	} 
 
	public function getPageNum(){
		return $this->page_no;
	} 
 
	public function getDescriptionTooltip(){
		return $this->description_tooltip;
	} 
 
	public function getDocumentId(){
		return $this->document_id;
	} 
 
	public function getFieldName(){
		return $this->field_name;
	} 
 
	public function setFieldId($field_id){
		$this->field_id=$field_id;
	} 
 
	public function setFieldTypeId($field_type_id){
		$this->field_type_id=$field_type_id;
	} 
 
	public function setFieldTypeName($field_type_name){
		$this->field_type_name=$field_type_name;
	} 
 
	public function setActionId($action_id){
		$this->action_id=$action_id;
	} 
 
	public function addSubField($sub_field){
		array_push($this->sub_fields,$sub_field);
	}

	public function setSubFields($sub_fields){
		$this->sub_fields = $sub_fields;
	} 
 
	public function setFieldCategory($field_category){
		$this->field_category=$field_category;
	} 
 
	public function setFieldLabel($field_label){
		$this->field_label=$field_label;
	} 
 
	public function setIsMandatory($is_mandatory){
		$this->is_mandatory=$is_mandatory;
	} 
 
	public function setIsReadOnly($is_read_only){
		$this->is_read_only=$is_read_only;
	} 
 
	public function setPageNum($page_no){
		$this->page_no=$page_no;
	} 
 
	public function setDescriptionTooltip($description_tooltip){
		$this->description_tooltip=$description_tooltip;
	} 
 
	public function setDocumentId($document_id){
		$this->document_id=$document_id;
	} 
 
	public function setFieldName($field_name){
		$this->field_name=$field_name;
	} 
 
	public function constructJson()
	{
		$response["field_id"]=$this->field_id;
		$response["field_type_id"]=$this->field_type_id;
		$response["field_type_name"]=$this->field_type_name;
		$response["action_id"]=$this->action_id;
		$sub_fieldsArr = array();
		foreach($this->sub_fields as $obj)
		{
			array_push($sub_fieldsArr,$obj->constructJson());
		}
		$response["sub_fields"]= count($sub_fieldsArr)!=0 ? $sub_fieldsArr : NULL ;

		$response["field_category"]=$this->field_category;
		$response["field_label"]=$this->field_label;
		$response["is_mandatory"]=$this->is_mandatory;
		$response["is_read_only"]=$this->is_read_only;
		$response["page_no"]=$this->page_no;
		$response["description_tooltip"]=$this->description_tooltip;
		$response["document_id"]=$this->document_id;
		$response["field_name"]=$this->field_name;
		return array_filter($response, function($v) { return !is_null($v); });
	}
}
?>