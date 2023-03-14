<?php

namespace zsign\api\fields;

class CheckBox
{ 
	private $field_id; 
	private $field_name; 
	private $field_label; 
	private $field_type_id; 
	private $field_type_name; 

	private $document_id; 
	private $action_id; 
	private $field_category;
	private $is_mandatory; 

	private $x_coord;
	private $y_coord; 
	private $x_value; 
	private $y_value; 
	private $abs_height; 
	private $abs_width; 
	private $height; 
	private $width; 

	private $default_value; 
	private $page_no; 

	private $is_read_only; 
	private $description_tooltip; 

	const CHECKBOX 	 = "Checkbox";

	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->field_id= (isset($response["field_id"])) ? $response["field_id"] : null;
		$this->x_coord= (isset($response["x_coord"])) ? $response["x_coord"] : null;
		$this->field_type_id= (isset($response["field_type_id"])) ? $response["field_type_id"] : null;
		$this->field_type_name= (isset($response["field_type_name"])) ? $response["field_type_name"] : self::CHECKBOX;
		$this->abs_height= (isset($response["abs_height"])) ? $response["abs_height"] : null;
		$this->field_category= (isset($response["field_category"])) ? $response["field_category"] : null;
		$this->field_label= (isset($response["field_label"])) ? $response["field_label"] : null;
		$this->is_mandatory= (isset($response["is_mandatory"])) ? $response["is_mandatory"] : null;
		$this->default_value= (isset($response["default_value"])) ? $response["default_value"] : null;
		$this->page_no= (isset($response["page_no"])) ? $response["page_no"] : null;
		$this->document_id= (isset($response["document_id"])) ? $response["document_id"] : null;
		$this->field_name= (isset($response["field_name"])) ? $response["field_name"] : null;
		$this->y_value= (isset($response["y_value"])) ? $response["y_value"] : null;
		$this->abs_width= (isset($response["abs_width"])) ? $response["abs_width"] : null;
		$this->action_id= (isset($response["action_id"])) ? $response["action_id"] : null;
		$this->width= (isset($response["width"])) ? $response["width"] : null;
		$this->y_coord= (isset($response["y_coord"])) ? $response["y_coord"] : null;
		$this->is_read_only= (isset($response["is_read_only"])) ? $response["is_read_only"] : null;
		$this->description_tooltip= (isset($response["description_tooltip"])) ? $response["description_tooltip"] : null;
		$this->x_value= (isset($response["x_value"])) ? $response["x_value"] : null;
		$this->height= (isset($response["height"])) ? $response["height"] : null;
	} 
	public function getFieldId(){
		return $this->field_id;
	} 
 
	public function getX_coord(){
		return $this->x_coord;
	} 
 
	public function getFieldTypeId(){
		return $this->field_type_id;
	} 

	public function getFieldTypeName(){
		return $this->field_type_name;
	} 
 
	public function getAbsHeight(){
		return $this->abs_height;
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
 
	public function getDefaultValue(){
		return $this->default_value;
	} 
 
	public function getPageNum(){
		return $this->page_no;
	} 
 
	public function getDocumentId(){
		return $this->document_id;
	} 
 
	public function getFieldName(){
		return $this->field_name;
	} 
 
	public function getY_value(){
		return $this->y_value;
	} 
 
	public function getAbsWidth(){
		return $this->abs_width;
	} 
 
	public function getActionId(){
		return $this->action_id;
	} 
 
	public function getWidth(){
		return $this->width;
	} 
 
	public function getY_coord(){
		return $this->y_coord;
	} 
 
	public function getIsReadOnly(){
		return $this->is_read_only;
	} 
 
	public function getDescriptionTooltip(){
		return $this->description_tooltip;
	} 
 
	public function getX_value(){
		return $this->x_value;
	} 
 
	public function getHeight(){
		return $this->height;
	} 
 
	public function setFieldId($field_id){
		$this->field_id=$field_id;
	} 
 
	public function setX_coord($x_coord){
		$this->x_coord=$x_coord;
	} 
 
	public function setFieldTypeId($field_type_id){
		$this->field_type_id=$field_type_id;
	} 
 
	public function setFieldTypeName($field_type_name){
		$this->field_type_name=$field_type_name;
	} 
 
	public function setAbsHeight($abs_height){
		$this->abs_height=$abs_height;
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
 
	public function setDefaultValue($default_value){
		$this->default_value=$default_value;
	} 
 
	public function setPageNum($page_no){
		$this->page_no=$page_no;
	} 
 
	public function setDocumentId($document_id){
		$this->document_id=$document_id;
	} 
 
	public function setFieldName($field_name){
		$this->field_name=$field_name;
	} 
 
	public function setY_value($y_value){
		$this->y_value=$y_value;
	} 
 
	public function setAbsWidth($abs_width){
		$this->abs_width=$abs_width;
	} 
 
	public function setActionId($action_id){
		$this->action_id=$action_id;
	} 
 
	public function setWidth($width){
		$this->width=$width;
	} 
 
	public function setY_coord($y_coord){
		$this->y_coord=$y_coord;
	} 
 
	public function setIsReadOnly($is_read_only){
		$this->is_read_only=$is_read_only;
	} 
 
	public function setDescriptionTooltip($description_tooltip){
		$this->description_tooltip=$description_tooltip;
	} 
 
	public function setX_value($x_value){
		$this->x_value=$x_value;
	} 
 
	public function setHeight($height){
		$this->height=$height;
	} 
 
 
	public function constructJson()
	{
		$response["field_id"]=$this->field_id;
		$response["x_coord"]=$this->x_coord;
		$response["field_type_id"]=$this->field_type_id;
		$response["field_type_name"]=$this->field_type_name;
		$response["abs_height"]=$this->abs_height;
		$response["field_category"]=$this->field_category;
		$response["field_label"]=$this->field_label;
		$response["is_mandatory"]=$this->is_mandatory;
		$response["default_value"]=$this->default_value;
		$response["page_no"]=$this->page_no;
		$response["document_id"]=$this->document_id;
		$response["field_name"]=$this->field_name;
		$response["y_value"]=$this->y_value;
		$response["abs_width"]=$this->abs_width;
		$response["action_id"]=$this->action_id;
		$response["width"]=$this->width;
		$response["y_coord"]=$this->y_coord;
		$response["is_read_only"]=$this->is_read_only;
		$response["description_tooltip"]=$this->description_tooltip;
		$response["x_value"]=$this->x_value;
		$response["height"]=$this->height;
		return array_filter($response, function($v) { return !is_null($v); });
	}
}
?>