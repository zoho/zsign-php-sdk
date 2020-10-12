<?php

	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : Send documents for embedded signing
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
			// OAuth::ACCESS_TOKEN => "" // optional. If not set, will auto refresh for access token
		) );

		ZohoSign::setCurrentUser( $user );

		$user->generateAccessTokenUsingRefreshToken();  // manully generate access token. Else, will auto refresh.

		$access_token = $user->getAccessToken(); // get and store access token so to avoid unnecessary regeneration.

		/*********
		STEP 2 : Draft a request using SDK functions
		**********/

		$reqObj = new RequestObject();
		$reqObj->setRequestName		( 'Partnership Agreement' );

		$partner = new Actions();
		$partner->setRecipientName	( "Albus D" ); 
		$partner->setRecipientEmail	( "albus@zylker.com" ); 
		$partner->setActionType		( Actions::SIGNER ); 
		$partner->setisEmbedded		( true ); 

		$reqObj->addAction	( $partner );

		$file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/TextTagsAPI.pdf"; // this document has text-tags
		$files = [ 
			new CURLfile( $file1 )
		];

		$draftJSON	= ZohoSign::draftRequest( $reqObj,  $files);


		/*********
			STEP 3 : Submit for signature
		**********/

		$sfs_resp	= ZohoSign::submitForSignature( $draftJSON );


		/*********
			STEP 4 :Get Signing URL. (valid 3 mins only! use to embed)
		**********/

		$request_id = $sfs_resp->getRequestId();
		$action_id_0 = $sfs_resp->getActions()[0]->getActionId();
		$hosting_parent_url = null; // your website URL where you are embedding in iframe. Null if opening in new Tab

		$signing_url = ZohoSign::generateEmbeddedSigningLink( $request_id, $action_id_0, $hosting_parent_url );

		echo ":: ".$request_id." : ".$sfs_resp->getRequestStatus()." : <a href='".$signing_url."' target='_blank'>Signing URL</a>";

		
	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>