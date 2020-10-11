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


class TemplateDocumentFields{
	
	/*
		USE: for reading template fields

		GET: /api/v1/templates/--ID--/details  > templates > document_fields
	*/

	private $document_id;
	private $fields=array();

	private $date_fields = array(); 	// Array of class date_fields 
	private $dropdown_fields = array(); // Array of class dropdown_fields 
	private $file_fields = array(); 	// Array of class file_fields 
	private $text_fields = array(); 	// Array of class text_fields 
	private $image_fields = array(); 	// Array of class image_fields 
	private $check_boxes = array(); 	// Array of class check_boxes 
	private $radio_groups = array();	// Array of class radio_groups 

	function __construct($response=null){

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}
		
		$this->document_id 	= (isset($response["document_id"]))	 ? $response["document_id"]	: null;

		if( isset($response["fields"]) ){
			foreach ($response["fields"] as $field) {

				switch( $field["field_category"] ){
					case "checkbox":
						$cb = new CheckBox($field);
						array_push($this->check_boxes, $cb);
						array_push($this->fields, $cb);
						break;
					case "radiogroup":
						$rb = new RadioGroup($field);
						array_push($this->radio_groups,$rb);
						array_push($this->fields, $rb);
						break;
					case "image":
						$imgF = new ImageField($field);
						array_push($this->image_fields,$imgF);
						array_push($this->fields, $imgF);
						break;
					case "textfield":
						$tf = new TextField($field);
						array_push($this->text_fields,$tf);
						array_push($this->fields, $tf);
						break;
					case "datefield":
						$df = new DateField($field);
						array_push($this->date_fields,$df);
						array_push($this->fields, $df);
						break;
					case "dropdown":
						$dd = new DropdownField($field);
						array_push($this->dropdown_fields,$dd);
						array_push($this->fields, $dd);
						break;
					case "filefield":
						$ff = new AttachmentField($field);
						array_push($this->file_fields, $ff);
						array_push($this->fields, $ff);
						break;
				}
			}

		}
				
	}

	public function getDocumentId(){
		return $this->document_id ;
	}

	public function getFields(){
		return $this->fields ;
	}

	public function getDateFields(){
		return $this->date_fields ;
	}

	public function getDropdownFields(){
		return $this->dropdown_fields ;
	}

	public function getFileFields(){
		return $this->file_fields ;
	}

	public function getTextFields(){
		return $this->text_fields ;
	}

	public function getImageFields(){
		return $this->image_fields ;
	}

	public function getCheckBoxes(){
		return $this->check_boxes ;
	}

	public function getRadioGroups(){
		return $this->radio_groups ;
	}



	/*
	// SETTERS not required
	public function setDocumentId($document_id){
		$this->document_id = $document_id;
	}
	
	public function setFields($fields){
		$this->fields = $fields;
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
	}*/


	// no need of construct json
	// any use case exists ?

}

