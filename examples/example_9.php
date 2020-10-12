<?php

	/*
		:: Zoho Sign PHP SDK ::
		Use Case : 	draft a Zoho Sign request  Add fields & self sign it.
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
		$reqObj->setRequestName		( 'Partnership Agreement [test - selfSign]' );
		$reqObj->setSelfSign 		( true );

		$file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/Non Diclosure Agreement.pdf";
		$files = [ 
			new CURLfile( $file1 )
		];

		$draftJSON	= ZohoSign::draftRequest( $reqObj,  $files);


		/*********
			STEP 3 :Set Fields & Submit for signature
		**********/

		$sign1 = new ImageField();
		$sign1->setFieldTypeName	( ImageField::SIGNATURE );
		$sign1->setDocumentId		( $draftJSON->getDocumentIds() [0] ->getDocumentId() );
		$sign1->setPageNum 			( 0 );
		$sign1->setIsMandatory		( "true" );
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


		$sfs_resp	= ZohoSign::selfSignRequest( $draftJSON );

		if( $sfs_resp ){
			echo "signed";
		}else{
			echo "signing failed";
		}

	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
		echo "GENERAL EXCEPTION : ".$ex;
	}

?>