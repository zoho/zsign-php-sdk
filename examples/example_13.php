<?php

	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : get field data from completed document

		NOTE : the example assumes the below "document_id" (not "request_id") is
		signing completed and contains the fields with following "field_label" set
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
			STEP 2 : Get field details
		**********/

		$field_data = ZohoSign::getFieldDataFromCompletedDocument( 1234567890 ); // replace with valid "request_id"
		
		$action_1_fields =  $field_data[0]->getFields();

		// assuming the zoho sign request has field_label's for the signer
		$Value_full_name = $action_1_fields->getDocumentFormDataByFieldLabel("Full name")->getFieldValue();
		$Value_Email = $action_1_fields->getDocumentFormDataByFieldLabel("Email")->getFieldValue();
		$Value_ph_num = $action_1_fields->getDocumentFormDataByFieldLabel("Phone No")->getFieldValue();
		$Value_Comment = $action_1_fields->getDocumentFormDataByFieldLabel("Comment")->getFieldValue();
		$Value_Gender = $action_1_fields->getDocumentFormDataByFieldLabel("Gender")->getFieldValue();

		$Name_full_name = $action_1_fields->getDocumentFormDataByFieldLabel("Full name")->getFieldName();
		$Name_Email = $action_1_fields->getDocumentFormDataByFieldLabel("Email")->getFieldName();
		$Name_ph_num = $action_1_fields->getDocumentFormDataByFieldLabel("Phone No")->getFieldName();
		$Name_Comment = $action_1_fields->getDocumentFormDataByFieldLabel("Comment")->getFieldName();
		$Name_Gender = $action_1_fields->getDocumentFormDataByFieldLabel("Gender")->getFieldName();

		echo $Name_full_name." : ".$Value_full_name."<br>";
		echo $Name_Email." : ".$Value_Email."<br>";
		echo $Name_ph_num." : ".$Value_ph_num."<br>";
		echo $Name_Comment." : ".$Value_Comment."<br>";
		echo $Name_Gender." : ".$Value_Gender."<br>";

		
	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>