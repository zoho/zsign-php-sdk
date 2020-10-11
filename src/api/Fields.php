<?php

namespace zsign\api;

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


class Fields
{ 
	private $date_fields = array(); 	// Array of class date_fields 
	private $dropdown_fields = array(); // Array of class dropdown_fields 
	private $file_fields = array(); 	// Array of class file_fields 
	private $text_fields = array(); 	// Array of class text_fields 
	private $image_fields = array(); 	// Array of class image_fields 
	private $check_boxes = array(); 	// Array of class check_boxes 
	private $radio_groups = array();	// Array of class radio_groups

	private $document_form_data = array(); // Array of class radio_groups 
	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			// echo "<br>new Actions Object: typecast from \stdClass(object) to array<br>";
			$response = json_decode( json_encode($response) , true );
		}

		/*
			2 scenarios:
				Scenario 1 : 2 types of field formats supported
					1) array of array(by field type)
					2) array of fields (no order)
				Scenario 2 : form field data 
		*/

		// Scenario 1, Type 1
		$this->date_fields= array();
		if( isset($response["date_fields"]) ){
			foreach($response["date_fields"] as $obj)
			{
				array_push($this->date_fields,new DateField($obj));
			}
		}
		
		$this->dropdown_fields= array();
		if( isset( $response["dropdown_fields"] ) ){
			foreach($response["dropdown_fields"] as $obj)
			{
				array_push($this->dropdown_fields,new DropdownField($obj));
			}
		}

		$this->file_fields= array();
		if( isset( $response["file_fields"] ) ){
			foreach($response["file_fields"] as $obj)
			{
				array_push($this->file_fields,new AttachmentField($obj));
			}
		}

		$this->text_fields= array();
		if( isset( $response["text_fields"] ) ){
			foreach($response["text_fields"] as $obj)
			{
				array_push($this->text_fields,new TextField($obj));
			}
		}

		$this->image_fields= array();
		if( isset( $response["image_fields"] ) ){
			foreach($response["image_fields"] as $obj)
			{
				array_push($this->image_fields,new ImageField($obj));
			}
		}

		$this->check_boxes= array();
		if( isset( $response["check_boxes"] ) ){
			foreach($response["check_boxes"] as $obj)
			{
				array_push($this->check_boxes,new CheckBox($obj));
			}
		}

		$this->radio_groups= array();
		if( isset( $response["radio_groups"] ) ){
			foreach($response["radio_groups"] as $obj)
			{
				array_push($this->radio_groups,new RadioGroup($obj));
			}
		}

		// Scenario 1, Type 2
		$temp = $response;
		unset( $temp["date_fields"] );
		unset( $temp["dropdown_fields"] );
		unset( $temp["file_fields"] );
		unset( $temp["image_fields"] );
		unset( $temp["check_fields"] );
		unset( $temp["radio_fields"] );

		if( !is_null($temp) ){

			foreach ($temp as $field) {
				if( isset($field["field_category"]) ){
					switch( strtolower( $field["field_category"] ) ){
						case "checkbox":
							array_push($this->check_boxes,new CheckBox($field));
							break;
						case "radiogroup":
							array_push($this->radio_groups,new RadioGroup($field));
							break;
						case "image":
							array_push($this->image_fields,new ImageField($field));
							break;
						case "textfield":
							array_push($this->text_fields,new TextField($field));
							break;
						case "datefield":
							array_push($this->date_fields,new DateField($field));
							break;
						case "dropdown":
							array_push($this->dropdown_fields,new DropdownField($field));
							break;
						case "filefield":
							array_push($this->file_fields,new AttachmentField($field));
							break;
					}
				}else{
					// Scenario 2
					array_push($this->document_form_data,new DocumentFormData($field));
				}
			}
		}
	} 

	public function getDateFields(){
		return $this->date_fields;
	} 
 
	public function getDropdownFields(){
		return $this->dropdown_fields;
	} 
 
	public function getFileFields(){
		return $this->file_fields;
	} 
 
	public function getTextFields(){
		return $this->text_fields;
	} 
 
	public function getImageFields(){
		return $this->image_fields;
	} 
 
	public function getCheckBoxes(){
		return $this->check_boxes;
	} 
 
	public function getRadioGroups(){
		return $this->radio_groups;
	} 
 
 	public function getDocumentFormData(){
		return $this->document_form_data;
	} 	
 	
 	public function getDocumentFormDataByFieldLabel( $field_label ){
		foreach ($this->document_form_data as $key => $obj) {
			if( $obj->getFieldLabel() == $field_label ){
				return $obj;
			}
		};
		return null;
	} 	

	public function addDateField($date_field){
		array_push($this->date_fields, $date_field);
	} 
 
	public function addDropdownField($dropdown_field){
		array_push($this->dropdown_fields, $dropdown_field);
	} 
 
	public function addFileField($file_field){
		array_push($this->file_fields, $file_field);
	} 
 
	public function addTextField($text_field){
		array_push($this->text_fields, $text_field);
	} 
 
	public function addImageField($image_field){
		array_push($this->image_fields, $image_field);
	} 
 
	public function addCheckBox($check_box){
		array_push($this->check_boxes, $check_box);
	} 
 
	public function addRadioGroup($radio_group){
		array_push($this->radio_groups, $radio_group);
	} 
 


	public function setDateFields($date_fields){
		$this->date_fields = $date_fields;
	} 
 
	public function setDropdownFields($dropdown_fields){
		$this->dropdown_fields = $dropdown_fields;
	} 
 
	public function setFileFields($file_fields){
		$this->file_fields = $file_fields;
	} 
 
	public function setTextFields($text_fields){
		$this->text_fields = $text_fields;
	} 
 
	public function setImageFields($image_fields){
		$this->image_fields = $image_fields;
	} 
 
	public function setCheckBoxes($check_boxes){
		$this->check_boxes = $check_boxes;
	} 
 
	public function setRadioGroups($radio_groups){
		$this->radio_groups = $radio_groups;
	} 
 
 
	public function constructJson()
	{
		$date_fieldsArr = array();
		foreach($this->date_fields as $obj)
		{
			array_push($date_fieldsArr,$obj->constructJson());
		}
		$response["date_fields"]= count($date_fieldsArr)!=0 ? $date_fieldsArr : NULL ;		


		$dropdown_fieldsArr = array();
		foreach($this->dropdown_fields as $obj)
		{
			array_push($dropdown_fieldsArr,$obj->constructJson());
		}
		$response["dropdown_fields"]= count($dropdown_fieldsArr)!=0 ? $dropdown_fieldsArr : NULL ;		
		  

		$file_fieldsArr = array();
		foreach($this->file_fields as $obj)
		{
			array_push($file_fieldsArr,$obj->constructJson());
		}
		$response["file_fields"]= count($file_fieldsArr)!=0 ? $file_fieldsArr : NULL ;		


		$text_fieldsArr = array();
		foreach($this->text_fields as $obj)
		{
			array_push($text_fieldsArr,$obj->constructJson());
		}
		$response["text_fields"]= count($text_fieldsArr)!=0 ? $text_fieldsArr : NULL ;		


		$image_fieldsArr = array();
		foreach($this->image_fields as $obj)
		{
			array_push($image_fieldsArr,$obj->constructJson());
		}
		$response["image_fields"]= count($image_fieldsArr)!=0 ? $image_fieldsArr : NULL ;		


		$check_boxesArr = array();
		foreach($this->check_boxes as $obj)
		{
			array_push($check_boxesArr,$obj->constructJson());
		}
		$response["check_boxes"]= count($check_boxesArr)!=0 ? $check_boxesArr : NULL ;		


		$radio_groupsArr = array();
		foreach($this->radio_groups as $obj)
		{
			array_push($radio_groupsArr,$obj->constructJson());
		}
		$response["radio_groups"]= count($radio_groupsArr)!=0 ? $radio_groupsArr : NULL ;		
		

		return array_filter( $response, function($v) { return !is_null($v); } );
	}
}
?>