<?php

namespace Zoho\Sign\sdk\src;

use Zoho\Sign\sdk\src\SignException;
use Zoho\Sign\sdk\src\ApiClient;

class OAuth {


	const DC_type = [
		"com"	=> "com",
		"COM"	=> "com",
		"in"	=> "in",
		"IN"	=> "in",
		"eu"	=> "eu",
		"EU"	=> "eu",
		"au"	=> "au",
		"AU" 	=> "au"
	];

	const CLIENT_ID 	= "CLIENT_ID";
	const CLIENT_SECRET = "CLIENT_SECRET";
	const REDIRECT_URI 	= "REDIRECT_URI";
	const SCOPE 		= "SCOPE";
	const ACCESS_TYPE 	= "ACCESS_TYPE";
	const ACCESS_TOKEN 	= "ACCESS_TOKEN";
	const REFRESH_TOKEN = "REFRESH_TOKEN";
	const DC 			= "DC";

	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $scope;
	private $access_type;
	private $DC = 'com';
	
	private $access_token;
	private $refresh_token;

	private $currentUser;

	function __construct( $arr ) {

		foreach ($arr as $key => $value) {
			switch( strtoupper($key) ){
				case self::CLIENT_ID : 
					$this->client_id = $value;
					break;
				case self::CLIENT_SECRET : 
					$this->client_secret = $value;
					break;
				case self::REDIRECT_URI : 
					$this->redirect_uri = $value;
					break;
				case self::SCOPE : 
					$this->scope = $value;
					break;
				case self::ACCESS_TYPE : 
					$this->access_type = $value;
					break;
				case self::ACCESS_TOKEN : 
					$this->access_token = $value;
					break;
				case self::REFRESH_TOKEN : 
					$this->refresh_token = $value;
					break;
				case self::DC :
					if( array_key_exists($value, self::DC_type ) ){
						$this->DC =  $value;
					}else{
			 			throw new SignException("Invalid DC type", -1);
					}
			}
		}
	}

	public function getDC(){
		return $this->DC;
	}

	public function getBaseURL(){
		return "https://sign.zoho.".$this->DC;
	}

	public function getAccessToken(){
		return $this->access_token;
	}

	public function setAccessToken( $access_token ){
		$this->access_token = $access_token;
	}

	public function getRefreshToken(){
		return $this->$refresh_token;
	}

	public function setRefreshToken( $refresh_token ){
		$this->refresh_token = $refresh_token;
	}

	public function generateAccessTokenUsingRefreshToken(){

		$params = array(
			'refresh_token'		=> $this->refresh_token,
			'client_id'			=> $this->client_id,
			'client_secret' 	=> $this->client_secret,
			'grant_type'		=> 'refresh_token'
		);

		$response = ApiClient::callURL( 
			'https://accounts.zoho.com/oauth/v2/token', // URL
			ApiClient::POST, 							// METHOD
			$params,									// PARAMS
			null
		);

		if( isset($response->data->access_token) ){
			$this->access_token = $response->data->access_token;
			return $response->data->access_token ;
		}else{
			return null;
		}
		
	}

}