<?php

	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : create a Zoho Sign request using a document with text-tags and a signer. Send the draft for signature.
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
			OAuth::CLIENT_ID 	=> "1000.Y97OE5BFRXVJKY7OUAWFW6ZO4SUYVA",
			OAuth::CLIENT_SECRET=> "5f60eb6a58ca63ffc37c14466104f29e1fdcd4707c",
			OAuth::DC 			=> "COM",
			OAuth::REFRESH_TOKEN=> "1000.c273d62198c4a66e1f9df5b4b54c4fcd.2556f1120e30f879db1bec4278b5a60c",
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
		$partner->setRecipientName	( "Will S" ); 
		$partner->setRecipientEmail	( "will.s@zylker.com" ); 
		$partner->setActionType		( Actions::SIGNER ); 
		$partner->setisEmbedded		( true ); 

		$reqObj->addAction	( $partner );

		$file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/TextTagsAPI.pdf";
		$files = [ 
			new CURLfile( $file1 )
		];

		$draftJSON	= ZohoSign::draftRequest( $reqObj,  $files);


		/*********
			STEP 3 : Submit for signature
		**********/

		$sfs_resp	= ZohoSign::submitForSignature( $draftJSON );

		echo ":: ".$sfs_resp->getRequestId()." : ".$sfs_resp->getRequestStatus();

		
	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>