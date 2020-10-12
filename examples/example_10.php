<?php
	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : for a completed request, download the documents, completion certificate to local drive
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

		$request_id = 2000002608053;

		mkdir("./Downloads/$request_id");	// suggested idea
				
		ZohoSign::setDownloadPath( "./Downloads/$request_id/" );

		ZohoSign::downloadRequest( $request_id );
		ZohoSign::downloadCompletionCertificate( $request_id );


	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>