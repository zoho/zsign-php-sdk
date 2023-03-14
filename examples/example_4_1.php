<?php
	/*
		:: Zoho Sign API examples using PHP SDK ::
		
		Use Case : create a Zoho Sign request using multiple document and diff types of signer. Add fields & Send the draft for signature.
	*/
	require_once __DIR__ . '/vendor/autoload.php';

	use zsign\OAuth;
	use zsign\ZohoSign;
	use zsign\SignException;
	use zsign\api\Fields;
	use zsign\api\Actions;
	use zsign\api\RequestObject;
	use zsign\api\fields\AttachmentField;
	use zsign\api\fields\CheckBox;
	use zsign\api\fields\DateField;
	use zsign\api\fields\DropdownField;
	use zsign\api\fields\DropdownValues;
	use zsign\api\fields\ImageField;
	use zsign\api\fields\RadioField;
	use zsign\api\fields\RadioGroup;
	use zsign\api\fields\TextField;
	use zsign\api\fields\TextProperty;

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

			you can construct the request object by
			> using SDK functions (this file)
			> using JSON (see file : example_4_2.php )

		**********/

		$reqObj = new RequestObject();
		$reqObj->setRequestName		( 'Partnership Agreement & Invoice' );
		$reqObj->setSequentialSigning( true );

		$partner = new Actions();
		$partner->setRecipientName	( "Severus S" ); 
		$partner->setRecipientEmail	( "severus.s@hogwcorp.com" ); 
		$partner->setActionType		( Actions::SIGNER ); 
		$partner->setSigningOrder	( 0 );
		$partner->setIsEmbedded 	( true );

		$ZylkerRepresentative = new Actions();
		$ZylkerRepresentative->setRecipientName	( "Eric" ); 
		$ZylkerRepresentative->setRecipientEmail( "eric@zylker.com" ); 
		$ZylkerRepresentative->setActionType	( Actions::SIGNER ); 
		$ZylkerRepresentative->setSigningOrder	( 1 );

		$ZylkerHR = new Actions();
		$ZylkerHR->setRecipientName	( "John H" ); 
		$ZylkerHR->setRecipientEmail( "john@zylker.com" ); 
		$ZylkerHR->setActionType	( Actions::APPROVER ); 
		$ZylkerHR->setSigningOrder	( 2 );

		$ZylkerAdmin = new Actions();
		$ZylkerAdmin->setRecipientName	( "Torres" ); 
		$ZylkerAdmin->setRecipientEmail ( "Torres@zylker.com" ); 
		$ZylkerAdmin->setActionType		( Actions::VIEWER ); 
		$ZylkerAdmin->setSigningOrder	( 3 );

		$reqObj->addAction	( $partner );
		$reqObj->addAction	( $ZylkerRepresentative );
		$reqObj->addAction	( $ZylkerHR );
		$reqObj->addAction	( $ZylkerAdmin );

		$file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/Non Diclosure Agreement.pdf";
		$file2 = $_SERVER['DOCUMENT_ROOT']."/Documents/Partnership Agreement.pdf";
		$files = [ 
			new CURLfile( $file1 ),
			new CURLfile( $file2 )
		];

		$draftJSON	= ZohoSign::draftRequest( $reqObj,  $files);
		
		/*********
			STEP 3 :Set Fields to the request object & Submit for signature
		**********/
		$fields_0 = new Fields();
		
		// IMAGE FIELD
		$partner_sign = new ImageField();
		$partner_sign->setFieldTypeName	( ImageField::SIGNATURE );
		$partner_sign->setDocumentId	( $draftJSON->getDocumentIds() [0] ->getDocumentId() );
		$partner_sign->setPageNum 		( 0 );
		$partner_sign->setIsMandatory	( true );
		$partner_sign->setX_value 		( 64 );
		$partner_sign->setY_value 		( 81 );
		$partner_sign->setHeight		( 2.5 );
		$partner_sign->setWidth			( 22 );

		// TEXT FIELD
		$partner_group = new TextField();
		$partner_group->setFieldTypeName 	( TextField::TEXTFIELD );
		$partner_group->setDocumentId		( $draftJSON->getDocumentIds() [0] ->getDocumentId() );
		$partner_group->setFieldName 		( "Enter group name " );
		$partner_group->setDefaultValue		( "-" );
		$partner_group->setPageNum 			( 0 );
		$partner_group->setIsMandatory		( false );
		$partner_group->setX_value 			( 18 );
		$partner_group->setY_value 			( 23 );
		$partner_group->setHeight			( 1.8 );
		$partner_group->setWidth			( 14 );
		$prop = new TextProperty();
		$prop->setIsReadOnly(true);
		$partner_group->setTextProperty 	( $prop );

		// COMPANY (TEXT FIELD)
		$partner_company = new TextField();
		$partner_company->setFieldTypeName 	( TextField::COMPANY );
		$partner_company->setDocumentId		( $draftJSON->getDocumentIds() [1] ->getDocumentId() );
		$partner_company->setFieldName 		( "Company" );
		$partner_company->setPageNum 		( 0 );
		$partner_company->setIsMandatory	( true );
		$partner_company->setX_value 		( 65 );
		$partner_company->setY_value 		( 75 );
		$partner_company->setHeight			( 1.5 );
		$partner_company->setWidth			( 14 );

		// SIGN DATE (DATE FIELD)
		$sign_date = new DateField();
		$sign_date->setFieldTypeName 	( DateField::SIGNDATE );
		$sign_date->setDocumentId		( $draftJSON->getDocumentIds() [0] ->getDocumentId() );
		$sign_date->setFieldName 		( "Sign Date" );
		$sign_date->setDateFormat 		( "MMM dd yyyy" );
		$sign_date->setPageNum 			( 0 );
		$sign_date->setIsMandatory		( true );
		$sign_date->setX_value 			( 65 );
		$sign_date->setY_value 			( 82 );
		$sign_date->setHeight			( 1.5 );
		$sign_date->setWidth			( 16 );

		// CUSTOM DATE (DATE FIELD)
		$sign_date = new DateField();
		$sign_date->setFieldTypeName 	( DateField::CUSTOMDATE );
		$sign_date->setDocumentId		( $draftJSON->getDocumentIds() [1] ->getDocumentId() );
		$sign_date->setFieldName 		( "Date Of Birth" );
		$sign_date->setDateFormat 		( "dd MMMM yyyy" );
		$sign_date->setPageNum 			( 0 );
		$sign_date->setIsMandatory		( false );
		$sign_date->setX_value 			( 78 );
		$sign_date->setY_value 			( 75 );
		$sign_date->setHeight			( 1.8 );
		$sign_date->setWidth			( 14 );

		// DROPDOWN FIELD
		$product_name = new DropdownField();
		$product_name->setFieldTypeName 	( DropdownField::DROPDOWN ); // optional
		$product_name->setDocumentId		( $draftJSON->getDocumentIds() [1] ->getDocumentId() );
		$product_name->setFieldName 		( "Product name" );
		$product_name->setPageNum 			( 0 );
		$product_name->setIsMandatory		( false );
		$product_name->setX_value 			( 24 );
		$product_name->setY_value 			( 50 );
		$product_name->setHeight			( 3 );
		$product_name->setWidth				( 30 );
		$dd_value_1 = new DropdownValues();
		$dd_value_1->setDropdownValue ("TV");
		$dd_value_1->setDropdownOrder (0);
		$dd_value_2 = new DropdownValues();
		$dd_value_2->setDropdownValue ("Mobile");
		$dd_value_2->setDropdownOrder (1);
		$dd_value_3 = new DropdownValues();
		$dd_value_3->setDropdownValue ("Laptop");
		$dd_value_3->setDropdownOrder (2);
		$product_name->addDropdownValues	( $dd_value_1 );
		$product_name->addDropdownValues	( $dd_value_2 );
		$product_name->addDropdownValues	( $dd_value_3 );
		$prop1 = new TextProperty();
		$prop1->setFont ("DejaVu Sans");
		$prop1->setFontSize (20);
		$prop1->setIsBold(true);
		$product_name->setTextProperty 	( $prop1 );

		// RADIO FIELDS
		$radio_field = new RadioGroup();
		$radio_field->setFieldTypeName	( RadioGroup::RADIOGROUP );
		$radio_field->setDocumentId		( $draftJSON->getDocumentIds() [1] ->getDocumentId() );
		$radio_field->setFieldName 		( "Nationality" );
		$radio_field->setPageNum 		( 0 );
		$radio_field->setIsMandatory	( false );
		$radio_subfield_1 = new RadioField();
		$radio_subfield_1->setSubFieldName ("Indian");
		$radio_subfield_1->setHeight (1.39);
		$radio_subfield_1->setWidth (2);
		$radio_subfield_1->setX_value (75);
		$radio_subfield_1->setY_value (73);
		$radio_subfield_1->setPageNum (0);
		$radio_subfield_2 = new RadioField();
		$radio_subfield_2->setSubFieldName ("Others");
		$radio_subfield_2->setHeight (1.39);
		$radio_subfield_2->setWidth (2);
		$radio_subfield_2->setX_value (75);
		$radio_subfield_2->setY_value (75);
		$radio_subfield_2->setPageNum (0);
		$radio_field->addSubField( $radio_subfield_1 );
		$radio_field->addSubField( $radio_subfield_2 );

		// CHECK BOX
		$terms_condt = new CheckBox();
		$terms_condt->setFieldTypeName 	( CheckBox::CHECKBOX );
		$terms_condt->setFieldName 		( "Agree ?" );
		$terms_condt->setDocumentId		( $draftJSON->getDocumentIds() [1] ->getDocumentId() );
		$terms_condt->setPageNum 		( 0 );
		$terms_condt->setIsMandatory	( true );
		$terms_condt->setDefaultValue 	( true );
		$terms_condt->setHeight 		(1.4);
		$terms_condt->setWidth 			(2);
		$terms_condt->setX_value	 	(7);
		$terms_condt->setY_value 		(68);

		$kyc_proof = new AttachmentField();
		$kyc_proof->setFieldTypeName 	( AttachmentField::ATTACHMENT );
		$kyc_proof->setFieldName 		( "KYC proof" );
		$kyc_proof->setDocumentId		( $draftJSON->getDocumentIds() [1] ->getDocumentId() );
		$kyc_proof->setPageNum 			( 0 );
		$kyc_proof->setIsMandatory		( false );
		$kyc_proof->setHeight 			(1.4);
		$kyc_proof->setWidth 			(2);
		$kyc_proof->setX_value	 		(7);
		$kyc_proof->setY_value 			(93);


		$fields_0->addImageField( $partner_sign );
		$fields_0->addTextField ( $partner_group );
		$fields_0->addTextField ( $partner_company );
		$fields_0->addDateField ( $sign_date );
		$fields_0->addDropdownField ( $product_name );
		// $fields_0->addRadioGroup( $radio_field );
		$fields_0->addCheckBox ( $terms_condt );
		$fields_0->addFileField ( $kyc_proof );

		// recipient 2 fields
		$fields_1 = new Fields();

		$Zylker_rep_sign = new ImageField();
		$Zylker_rep_sign->setFieldTypeName	( ImageField::SIGNATURE );
		$Zylker_rep_sign->setDocumentId		( $draftJSON->getDocumentIds() [0] ->getDocumentId() );
		$Zylker_rep_sign->setPageNum 		( 0 );
		$Zylker_rep_sign->setIsMandatory	( true );
		$Zylker_rep_sign->setX_value 		( 64 );
		$Zylker_rep_sign->setY_value 		( 81 );
		$Zylker_rep_sign->setHeight			( 2.5 );
		$Zylker_rep_sign->setWidth			( 22 );

		$fields_1->addImageField( $Zylker_rep_sign );

		$action = $draftJSON->getActions();
			$action0 = $action[0];
			$action0->setFields ( $fields_0 );
			$action[0] = $action0;
		
			$action1 = $action[1];
			$action1->setFields ( $fields_1 );
			$action[1] = $action1;
		$draftJSON->setActions( $action );

		$sfs_resp	= ZohoSign::submitForSignature( $draftJSON );

		echo ":: ".$sfs_resp->getRequestId()." : ".$sfs_resp->getRequestStatus();

	}catch( SignException $signEx ){
		// log it
		echo "<hr>SIGN EXCEPTION : ".$signEx;
	}catch( Exception $ex ){
		// handle it
	}

?>