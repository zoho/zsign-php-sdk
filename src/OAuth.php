<?php

namespace zsign;

use zsign\SignException;
use zsign\ApiClient;
use zsign\UpdateOAuth;

class OAuth {


	const DC_type = [
		"com"	=> "com",
		"COM"	=> "com",
		"in"	=> "in",
		"IN"	=> "in",
		"eu"	=> "eu",
		"EU"	=> "eu",
		"au"	=> "au",
		"AU" 	=> "au",
		"JP"	=> "jp",
		"jp"	=> "jp"
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
	private $expires_in;

	private $currentUser;

	function __construct( $arr ) {

		if( isset($arr[self::CLIENT_ID]) ){
			$this->client_id = $arr[self::CLIENT_ID];
		}else{
			throw new SignException("Client ID not set", -1);
		}


		if( isset($arr[self::CLIENT_SECRET]) ){
			$this->client_secret = $arr[self::CLIENT_SECRET];
		}else{
			throw new SignException("Client Secret not set", -1);
		}


		if( isset($arr[self::REDIRECT_URI]) ){
			$this->redirect_uri = $arr[self::REDIRECT_URI];
		}

		if( isset($arr[self::SCOPE]) ){
			$this->scope = $arr[self::SCOPE];
		}


		if( isset($arr[self::ACCESS_TYPE]) ){
			$this->access_type = $arr[self::ACCESS_TYPE];
		}


		if( isset($arr[self::ACCESS_TOKEN]) ){
			$this->access_token = $arr[self::ACCESS_TOKEN];
		}


		if( isset($arr[self::REFRESH_TOKEN]) ){
			$this->refresh_token = $arr[self::REFRESH_TOKEN];
		}else{
			throw new SignException("Refresh Token not set", -1);
		}


		if( isset($arr[self::DC]) ){
			if( array_key_exists( $arr[self::DC], self::DC_type ) ){
				$this->DC =  $arr[self::DC];
			}else{
	 			throw new SignException("Invalid DC type", -1);
			}
		}else{
			throw new SignException("DC type(TLD) not set not set", -1);
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
		return $this->refresh_token;
	}

	public function setRefreshToken( $refresh_token ){
		$this->refresh_token = $refresh_token;
	}

	public function generateAccessTokenUsingRefreshToken(){

		$params = array(
			'refresh_token'	=> $this->refresh_token,
			'client_id'		=> $this->client_id,
			'client_secret' 	=> $this->client_secret,
			'grant_type'		=> 'refresh_token'
		);

		$response = ApiClient::callURL(
			'https://accounts.zoho.'.$this->DC.'/oauth/v2/token', // URL
			ApiClient::POST, 							// METHOD
			$params,									// PARAMS
			null
		);

		if( isset($response->data->access_token) ){
			$this->access_token = $response->data->access_token;
			$this->expires_in = $response->data->expires_in;
			//UpdateOAuth::updateAccessToken($this->access_token,$this->expires_in);
			return $response->data->access_token ;
		}else{
			return null;
		}

	}

}
