<?php
	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : on a webhook triggered by a signing completed document, 
		create a folder & download the documents, completion certificate to local drive
	*/
	require_once __DIR__ . '/vendor/autoload.php';

	use zsign\OAuth;
	use zsign\ZohoSign;
	use zsign\SignException;
	use zsign\api\Fields;
	use zsign\api\Actions;
	use zsign\api\RequestObject;
	use zsign\api\fields\ImageField;

	try{

		/*********
			STEP 1 : Set user credentials
		**********/

		$user = new OAuth( array(
			OAuth::CLIENT_ID 	=> "",
			OAuth::CLIENT_SECRET=> "",
			OAuth::DC 			=> "COM",
			OAuth::REFRESH_TOKEN=> "",
			OAuth::ACCESS_TOKEN => "" // optional. If not set, will auto refresh for access token
		) );

		ZohoSign::setCurrentUser( $user );

		/*********
		STEP 2 : Download document
		**********/

		$postBody = file_get_contents("php://input");
		if( $postBody == "" ){
			throw new Exception('post contents are empty ');
		}
		$data_json = json_decode( $postBody, true );

		if( isset( $data_json["notifications"] ) ){
			if( $data_json["notifications"]["operation_type"]=="RequestCompleted" ){

				$completed_request_id = $data_json["requests"]["request_id"];

				mkdir("./Downloads/$completed_request_id");
				
				ZohoSign::setDownloadPath( "./Downloads/$completed_request_id/" );

				ZohoSign::downloadRequest( $completed_request_id );
				ZohoSign::downloadCompletionCertificate( $completed_request_id );
			
			}else{
				// webhook for someother action 
				throw new Exception("Error Processing Request - 2", 2);
			}
		}else{
			// wrong json,  not of webooks
			throw new Exception("Error Processing Request - 1 : $postBody", 1);
			
		}


	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>