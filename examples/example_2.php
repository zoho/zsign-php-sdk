<?php

	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : create a Zoho Sign request using a document and a signer. Add a field & send for signature.
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
		$partner->setRecipientName	( "Will S" ); 
		$partner->setRecipientEmail	( "will.s@zylker.com" ); 
		$partner->setActionType		( Actions::SIGNER ); 
		$partner->setisEmbedded		( true ); 

		$reqObj->addAction	( $partner );

		$file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/Non Diclosure Agreement.pdf";
		$files = [ 
			new CURLfile( $file1 )
		];

echo "<hr>".json_encode($reqObj->constructJson())."<hr>";
		$draftJSON	= ZohoSign::draftRequest( $reqObj,  $files);


		/*********
			STEP 3 :Set Fields & Submit for signature
		**********/

		$sign1 = new ImageField();
		$sign1->setFieldTypeName	( ImageField::SIGNATURE );
		$sign1->setDocumentId		( $draftJSON->getDocumentIds() [0] ->getDocumentId() );
		$sign1->setPageNum 			( 0 );
		$sign1->setIsMandatory		( true );
		$sign1->setX_value 			( 64 );
		$sign1->setY_value 			( 81 );
		$sign1->setHeight			( 2.5 );
		$sign1->setWidth			( 22 );

		$fields = new Fields();
		$fields->addImageField( $sign1 );

		$action = $draftJSON->getActions();
			$action0 = $action[0];
			$action0->setFields ( $fields );
			$action[0] = $action0;
		$draftJSON->setActions( $action );

		echo "<hr>".json_encode($draftJSON->constructJson())."<hr>";
		$sfs_resp	= ZohoSign::submitForSignature( $draftJSON );

		echo ":: ".$sfs_resp->getRequestId()." : ".$sfs_resp->getRequestStatus();

		
	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>