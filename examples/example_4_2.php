<?php

	/*
		:: Zoho Sign PHP SDK ::
		Use Case : 	create a Zoho Sign request using multiple document and diff types of signer. Add fields & Send the draft for signature.
					using JSON instead of SDK functions.
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

		// $user->generateAccessTokenUsingRefreshToken();  // manully generate access token
		// echo "<br> ".$user->getAccessToken()." <br>"; // get and store access token so to avoid unnecessary regeneration.
		
		/*********
		STEP 2 : Draft a request using SDK functions

			you can construct the request object by
			> using SDK functions (see file : submit_for_signature_3.php )
			> using JSON ( CURRENT FILE )
			> using API to get a reqquest (see file : submit_for_signature_5.php )
		**********/

		$file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/Non Diclosure Agreement.pdf";
		$file2 = $_SERVER['DOCUMENT_ROOT']."/Documents/Partnership Agreement.pdf";
		$files = [ 
			new CURLfile( $file1 ),
			new CURLfile( $file2 )
		];

		$reqJSON = json_decode(
			'{
			    "request_name": "[14] Partnership Agreement & Invoice",
			    "is_sequential": true,
			    "actions": [
			        {
			            "action_type": "SIGN",
			            "recipient_email": "severus.s@hogwcorp.com",
			            "signing_order": 0,
			            "recipient_name": "Severis S",
			            "is_embedded": true
			        },
			        {
			            "action_type": "SIGN",
			            "recipient_email": "eric@zylker.com",
			            "signing_order": 1,
			            "recipient_name": "Eric"
			        },
			        {
			            "action_type": "APPROVER",
			            "recipient_email": "john@zylker.com",
			            "signing_order": 2,
			            "recipient_name": "John H"
			        }
			    ],
			    "self_sign": false,
			    "custom_data": "Sent Using Zoho Sign PHP SDK."
			}', true);

		$reqObj = new RequestObject( $reqJSON );

		$draftObj = ZohoSign::draftRequest( $reqObj, $files );

		/*********
			STEP 3 :Set Fields to the request object & Submit for signature
		**********/

		$document_id_0 = $draftObj->getDocumentIds()[0]->getDocumentId();
		$document_id_1 = $draftObj->getDocumentIds()[1]->getDocumentId();

		$fieldsJSON_0 = json_decode(
			'{
	            "image_fields": [
	                {
	                    "field_type_name": "Signature",
	                    "is_mandatory": true,
	                    "page_no": 0,
	                    "document_id": "'.$document_id_0.'",
	                    "y_value": 81,
	                    "width": 22,
	                    "x_value": 64,
	                    "height": 2.5
	                }
	            ]
	        }', true);

        $fields_0 = new Fields( $fieldsJSON_0 );

        $fieldsJSON_1 = json_decode(
			'{
				"image_fields": [
	                {
	                    "field_type_name": "Signature",
	                    "is_mandatory": true,
	                    "page_no": 0,
	                    "document_id": "'.$document_id_1.'",
	                    "y_value": 81,
	                    "width": 22,
	                    "x_value": 64,
	                    "height": 2.5
	                }
	            ]
	        }', true);

        $fields_1 = new Fields( $fieldsJSON_1 );
        
        $action = $draftObj->getActions();
			$action0 = $action[0];
			$action0->setFields ( $fields_0 );
			$action[0] = $action0;
		
			$action1 = $action[1];
			$action1->setFields ( $fields_1 );
			$action[1] = $action1;
		$draftObj->setActions( $action );

		$sfs_resp	= ZohoSign::submitForSignature( $draftObj );

		echo "<hr>".$sfs_resp->getRequestId()." : ".$sfs_resp->getRequestStatus()."<hr>";

	}catch( SignException $signEx ){
		// log it
		echo "SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>