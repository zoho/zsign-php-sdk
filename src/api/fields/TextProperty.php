<?php

namespace zsign\api\fields;

class TextProperty
{ 
	private $is_italic; 
	private $is_underline; 
	private $font_color; 
	private $font_size; 
	private $is_read_only; 
	private $is_bold; 
	private $font; 

	const TEXTFIELD  = "Textfield";
	const EMAIL 	 = "Email";
	const NAME 		 = "Name";
	const COMPANY 	 = "Company";
	const JOBTITLE 	 = "Jobtitle";

	function __construct($response=null)
	{

		if( gettype( $response ) == "object" ){
			$response = json_decode( json_encode($response) , true );
		}

		$this->is_italic= (isset($response["is_italic"])) ? $response["is_italic"] : null;
		$this->is_underline= (isset($response["is_underline"])) ? $response["is_underline"] : null;
		$this->font_color= (isset($response["font_color"])) ? $response["font_color"] : null;
		$this->font_size= (isset($response["font_size"])) ? $response["font_size"] : null;
		$this->is_read_only= (isset($response["is_read_only"])) ? $response["is_read_only"] : null;
		$this->is_bold= (isset($response["is_bold"])) ? $response["is_bold"] : null;
		$this->font= (isset($response["font"])) ? $response["font"] : null;
	} 
	public function getIsItalic(){
		return $this->is_italic;
	} 
 
	public function getIsUnderline(){
		return $this->is_underline;
	} 
 
	public function getFontColor(){
		return $this->font_color;
	} 
 
	public function getFontSize(){
		return $this->font_size;
	} 
 
	public function getIsReadOnly(){
		return $this->is_read_only;
	} 
 
	public function getIsBold(){
		return $this->is_bold;
	} 
 
	public function getFont(){
		return $this->font;
	} 
 
	public function setIsItalic($is_italic){
		$this->is_italic=$is_italic;
	} 
 
	public function setIsUnderline($is_underline){
		$this->is_underline=$is_underline;
	} 
 
	public function setFontColor($font_color){
		$this->font_color=$font_color;
	} 
 
	public function setFontSize($font_size){
		$this->font_size=$font_size;
	} 
 
	public function setIsReadOnly($is_read_only){
		$this->is_read_only=$is_read_only;
	} 
 
	public function setIsBold($is_bold){
		$this->is_bold=$is_bold;
	} 
 
	public function setFont($font){
		$this->font=$font;
	} 
 
	public function constructJson()
	{
		$response["is_italic"]=$this->is_italic;
		$response["is_underline"]=$this->is_underline;
		$response["font_color"]=$this->font_color;
		$response["font_size"]=$this->font_size;
		$response["is_read_only"]=$this->is_read_only;
		$response["is_bold"]=$this->is_bold;
		$response["font"]=$this->font;
		return array_filter($response, function($v) { return !is_null($v); });
	}
}
?>