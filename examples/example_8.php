<?php
	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : use Zoho Sign template, set pre-fill fields & recipient info and quickly send out for signature. Embed the docuemnt for signing
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
		STEP 2 : Get template object by ID
		**********/

		$template = ZohoSign::getTemplate( 2000002552015 );
		
		/*********
		STEP 3 : Set values to the same object & send for signature
		**********/

		$template->setRequestName("Partnership Agreement [test8]");
		$template->setNotes("Call us back if you need clarificaions regarding agreement");
		
		$template->setPrefillTextField		( "Full name",	"M McGonagall" );
		$template->setPrefillBooleanField	( "Agree ?", 	true );
		$template->setPrefillDateField		( "Date - 1",	"08 July 2020" );
	
		$template->getActionByRole("Partner")->setRecipientName("M McGonagall");
		$template->getActionByRole("Partner")->setRecipientEmail("mcgonagall@hogwcorp.com");
		$template->getActionByRole("Partner")->setIsEmbedded(true);
	
		$resp_obj = ZohoSign::sendTemplate( $template, true );

		/*********
			STEP 4 :Get Signing URL. (valid 3 mins only! use to embed)
		**********/

		$request_id = $resp_obj->getRequestId();
		$action_id_0 = $resp_obj->getActions()[0]->getActionId();
		$hosting_parent_url = null; // your website URL where you are embedding in iframe. Null if opening in new Tab

		$signing_url = ZohoSign::generateEmbeddedSigningLink( $request_id, $action_id_0, $hosting_parent_url );

		echo ":: ".$request_id." : ".$resp_obj->getRequestStatus()." : <a href='".$signing_url."' target='_blank'>Signing URL</a>";


	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>s