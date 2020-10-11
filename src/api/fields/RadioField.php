<?php

namespace zsign\api\fields;

class RadioField
{ 
	private $y_value; 
	private $x_coord; 
	private $abs_width; 
	private $abs_height; 
	private $width; 
	private $sub_field_id; 
	private $y_coord; 
	private $default_value; 
	private $page_no; 
	private $sub_field_name; 
	private $x_value; 
	private $height; 
	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->y_value= (isset($response["y_value"])) ? $response["y_value"] : null;
		$this->x_coord= (isset($response["x_coord"])) ? $response["x_coord"] : null;
		$this->abs_width= (isset($response["abs_width"])) ? $response["abs_width"] : null;
		$this->abs_height= (isset($response["abs_height"])) ? $response["abs_height"] : null;
		$this->width= (isset($response["width"])) ? $response["width"] : null;
		$this->sub_field_id= (isset($response["sub_field_id"])) ? $response["sub_field_id"] : null;
		$this->y_coord= (isset($response["y_coord"])) ? $response["y_coord"] : null;
		$this->default_value= (isset($response["default_value"])) ? $response["default_value"] : null;
		$this->page_no= (isset($response["page_no"])) ? $response["page_no"] : null;
		$this->sub_field_name= (isset($response["sub_field_name"])) ? $response["sub_field_name"] : null;
		$this->x_value= (isset($response["x_value"])) ? $response["x_value"] : null;
		$this->height= (isset($response["height"])) ? $response["height"] : null;
	} 
	public function getY_value(){
		return $this->y_value;
	} 
 
	public function getX_coord(){
		return $this->x_coord;
	} 
 
	public function getAbsWidth(){
		return $this->abs_width;
	} 
 
	public function getAbsHeight(){
		return $this->abs_height;
	} 
 
	public function getWidth(){
		return $this->width;
	} 
 
	public function getSubFieldId(){
		return $this->sub_field_id;
	} 
 
	public function getY_coord(){
		return $this->y_coord;
	} 
 
	public function getDefaultValue(){
		return $this->default_value;
	} 
 
	public function getPageNum(){
		return $this->page_no;
	} 
 
	public function getSubFieldName(){
		return $this->sub_field_name;
	} 
 
	public function getX_value(){
		return $this->x_value;
	} 
 
	public function getHeight(){
		return $this->height;
	} 
 
	public function setY_value($y_value){
		$this->y_value=$y_value;
	} 
 
	public function setX_coord($x_coord){
		$this->x_coord=$x_coord;
	} 
 
	public function setAbsWidth($abs_width){
		$this->abs_width=$abs_width;
	} 
 
	public function setAbsHeight($abs_height){
		$this->abs_height=$abs_height;
	} 
 
	public function setWidth($width){
		$this->width=$width;
	} 
 
	public function setSubFieldId($sub_field_id){
		$this->sub_field_id=$sub_field_id;
	} 
 
	public function setY_coord($y_coord){
		$this->y_coord=$y_coord;
	} 
 
	public function setDefaultValue($default_value){
		$this->default_value=$default_value;
	} 
 
	public function setPageNum($page_no){
		$this->page_no=$page_no;
	} 
 
	public function setSubFieldName($sub_field_name){
		$this->sub_field_name=$sub_field_name;
	} 
 
	public function setX_value($x_value){
		$this->x_value=$x_value;
	} 
 
	public function setHeight($height){
		$this->height=$height;
	} 
	
 
	public function constructJson()
	{
		$response["y_value"]=$this->y_value;
		$response["x_coord"]=$this->x_coord;
		$response["abs_width"]=$this->abs_width;
		$response["abs_height"]=$this->abs_height;
		$response["width"]=$this->width;
		$response["sub_field_id"]=$this->sub_field_id;
		$response["y_coord"]=$this->y_coord;
		$response["default_value"]=$this->default_value;
		$response["page_no"]=$this->page_no;
		$response["sub_field_name"]=$this->sub_field_name;
		$response["x_value"]=$this->x_value;
		$response["height"]=$this->height;
		return array_filter($response, function($v) { return !is_null($v); });
	}
}
?>