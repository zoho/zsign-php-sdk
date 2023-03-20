PHP SDK for ZOHO SIGN
----------------------
This SDK provides wrapper functions for Zoho Sign v1 API's Document Management and Template Management.

You can setup signing workflows using this SDK similar as in Zoho Sign UI.

Links :
[Zoho Sign API Guide](https://www.zoho.com/sign/api/getting-started-guide/overview.html)
&
[Zoho Sign API Documentation](https://www.zoho.com/sign/api/)

Environment Set Up
------------------
PHP SDK is installable through `composer`. Composer is a tool for dependency management in PHP. SDK expects the following from the client app.
```
NOTE :
- Client app must have PHP 5.6 or above with  curl  extension enabled.
- SDK must be installed into client app though composer
```

Installation of SDK through composer
------------------------------------
Install Composer(if not installed)
Run this command to install the composer

>curl -sS https://getcomposer.org/installer | php

To make the composer accessible globally, follow the instructions from the link below

To install composer on mac/ linux machine:

>https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx

To install composer on windows machine:

>https://getcomposer.org/doc/00-intro.md#installation-windows


Install PHP SDK
---------------
Install PHP SDK
Here's how you install the SDK:

1) Navigate to the workspace of your client app
2) Run the command below:

>composer require zoho-sign/php-sdk

Hence, the PHP SDK would be installed and a package named 'vendor' would be created in the workspace of your client app.


Registering a Zoho Client
-------------------------
Since Zoho CRM APIs are authenticated with OAuth2 standards, you should register your client app with Zoho. To register your app:
1) Visit this page [https://accounts.zoho.com/developerconsole](https://accounts.zoho.com/developerconsole).
2) Click on `Add Client ID`.
3) Enter Client Name, Client Domain and Redirect URI then click `Create`.
4) Your Client app would have been created and displayed by now.
5) The newly registered app's Client ID and Client Secret can be found by clicking `Options` → `Edit`.
(Options is the three dot icon at the right corner).


Initializing SDK
----------------

To import the classes used in SDK, use 'require_once' to include the 'autoload.php' created by composer
>require 'vendor/autoload.php'

It is required to set the OAuth2 credentials of an user as the current user as a first step.

```php
    $user = new OAuth( array(
        OAuth::CLIENT_ID => "",
        OAuth::CLIENT_SECRET => "",
        OAuth::DC => "COM", // allowed values : com/COM, in/IN, au/AU, eu/EU
        OAuth::REFRESH_TOKEN => "",
        OAuth::ACCESS_TOKEN => "" // optional. If not set, will auto refresh for access token
    ) );
    ZohoSign::setCurrentUser( $user );
```
All subsequent api calls made will be made on behalf of this user
The First time user permission authentication/approval is to handled and the oauth credential storage has to be manually handled.

You can refresh & store the access_token as
```php
 $user->generateAccessTokenUsingRefreshToken();
```

Note : The OAuth credentials needs to storage(DB or file) needs to be logically handled manually


Class Heirarchy
---------------

- ZohoSign
- Oauth
- ApiClient
- SignException
- SignUtil
  - Actions
  - DocumentFormData
  - Documents
  - Fields
    - AttachmentField
    - CheckBox
    - DateField
    - DropdownField
    - DropdownValues
    - ImageField
    - RadioField
    - TextField
    - TextProperty
  - RequestType
  - TemplateDocumentFields
  - TemplateObject


SDK functions description
-------------------------

All functions for Document and Template management are available under 'ZohoSign.php' class  

#### Document Management functions
```
 getRequest()  
    Params: [INT] request_id  
    Return: instance of RequestObject
    Throws: SignException
    Description: Fetch the details of a Zoho Sign Request by its request id.


 draftRequest()
    Params: [RequestObject] requestObject,
            [array] files
    Return: instance of RequestObject
    Throws: SignException
    Description: Uploads the files and draft's a request with the properties.


 updateRequest()
    Params: [RequestObject] requestObject,
            [array] files
    Return: instance of RequestObject
    Throws: SignException
    Description: Uploads the files and draft's a request with the properties.


 addFilesToRequest()
    Params: [INT] request_id,
            [array] files
    Return: instance of RequestObject
    Throws: SignException
    Description: Uploads the files to a draft request.


submitForSignature()
    Params: [RequestObject] requestObject
    Return: instance of RequestObject
    Throws: SignException
    Description: The requestObject contains a reference for a 'draft' request with fields added
    The function submits the 'draft' for signature.


selfSignRequest()
    Params: [RequestObject] requestObject
    Return: instance of RequestObject with
    Throws: SignException
    The requestObject contains a reference for a 'draft' request with fields added
    The function signs the document as the current user.
    Throws 'SignException' if failed to sign, usually due to wrong field properties set.


getRequestList()
    Params: [KEY NAME] category    (values: ALL, DRAFT, INPROGRESS, RECALLED, COMPLETED, DECLINED, EXPIRED, EXPIRING, MY_REQUESTS, MY_PENDING, SHREDDED),
            [INT]      start_index (optional, default:0),
            [INT]      row_count   (optional, default:100, max:100),
            [KEY NAME] sort_order  (optional, default:DESC, values : ASC, DESC),
            [KEY NAME] sort_column (optional, default:action_time, values: action_time, request_name, folder_name, owner_first_name, recipient_email, created_time, modified_time)
    Return: array of instances of RequestObject
    Throws: SignException
    Description: The function fetches the document list by category name.
    If only category name is specified, it fetches the 100 results sorted by last modified time of the category type.


generateEmbeddedSigningLink()
    Params: [INT] request_id,
            [INT] action_id,
            [URL] host (default:'null'/for opening in new tab)
    Return: URL string
    Throws: SignException
    Generates and return a signing link for the signer specified by action_id.
    Add the 'host' param for the website in which you want to embedd.
    NOTE: The signing URL is valid ONLY for 3 minutes before which the link has to be opened/loaded


getFieldDataFromCompletedDocument
    Params: [INT] requestId
    Return: instance of DocumentFormData
    Throws: SignException
    Description: Returns the pdf fields form data with key:value as data_label:data_value


setDownloadPath()
  Params: [String] path (local directory path)
  Return: -
  Throws: -
  Description: Set the local directory path in which the files will be downloaded using ZohoSign functions
               If not set, will default to "$_SERVER['DOCUMENT_ROOT']" path returned by PHP


getDownloadPath()
  Params: -
  Return: -
  Throws: -
  Description: Return the local download directory path set.
                If not set, will default to "$_SERVER['DOCUMENT_ROOT']" path returned by PHP.


downloadRequest()
  Params: [INT] requrest_id
  Return: true
  Throws: SignException
  Description: Downloads the documents of the request with its current version of signatures placed, either as a PDF if single document or as ZIP of multiple documents
               Documents will be downloaded to the directory path returned by 'getDownloadPath()' function.


downloadDocument()
  Params: [INT] request_id,
          [INT] document_id
  Return: true
  Throws: SignException
  Description: Downloads the specific document of the request with its current version of signatures placed as a PDF
               Documents will be downloaded to the directory path returned by 'getDownloadPath()' function.


downloadCompletionCertificate()
  Params: [INT] requrest_id
  Return: true
  Throws: SignException
  Description: Downloads the completion certificate ONLY for the signing completed request.
               Completion Cetrificate PDF will be downloaded to the directory path returned by 'getDownloadPath()' function.



recallRequest()
  Params: [INT] request_id
  Return: true
  Throws: SignException
  Description: Recalls the request if submitted.


remindRequest()
  Params: [INT] request_id
  Return: true
  Throws: SignException
  Description: Sends a reminder to the recipient of the request.


deleteRequest()
  Params: [INT] request_id
  Return: true
  Throws: SignException
  Description: Deletes the request. Deleted requests will be available in 'Trash'.


createNewFolder()
  Params: [INT] request_id
  Return: folder_id
  Throws: SignException
  Description: Creates new folder by the name, if it doesnt exist already.


getFieldTypes()
  Params: -
  Return: [stdClass] field_types
  Throws: -
  Description: Retrieves all field types.


getRequestTypes()
  Params: -
  Return: array of instances of 'RequestType'
  Throws: -
  Description: Retrieves all request types.


createRequestType()
  Params: [String] name
          [String] desctiption
  Return: instance of RequestType
  Throws:
  Description: Creates a new request type.


getFolderList()
  Params: -
  Return: [JSON] array of stdClass
  Throws:
  Description: Retrieves list of folders
```

#### Template Management functions
```
createTemplate()
  Params: [TemplateObject] templateObject
          [array] files
  Return: TemplateObject
  Throws: SignException
  Description: Creates a Zoho Sign template. Returns TemplateObect of the created template.


updateTemplate( $templateObject, $files=null )
  Params: [TemplateObject] templateObject
          [array] files
  Return: TemplateObject
  Throws: SignException
  Description: Update an exsiting template with properties in the new templateObject, add files to the template.
              NOTE: The templateObject requires the request_id to be set.


addFilesToTemplate()
  Params: [INT] template_id
          [array] files
  Return: TemplateObject
  Throws: SignException
  Description: Adds files to the template.


getTemplate()
  Params: [INT] template_id
  return: [TemplateObject] templateObject
  Throws: SignException
  Description: Return the template object with its properties, which can be used to fill the prefill-fields and can be used for submission.


sendTemplate()
  Params: [TemplateObject] templateObject
          [Boolean] quick_send
  return: [RequestObject] requestObject
  Throws: SignException
  Description: The templateObject input param contains the TemplateObject returned by the "getTemplate()" function with the prefill-fields filled.
               Setting the quick send params as true sends the document for signature. Setting false, only creates a draft.
               Return value contains a RequestObject of the created request(either a DRAFT or INPROGRESS request).


getTemplatesList()
    Params: [INT]      start_index (optional, default:0),
            [INT]      row_count   (optional, default:100, max:100),
            [KEY NAME] sort_order  (optional, default:DESC, values : ASC, DESC),
            [KEY NAME] sort_column (optional, default:action_time, values: action_time, request_name, folder_name, owner_first_name, recipient_email, created_time, modified_time)
    Return: array of instances of TemplateObject
    Throws: SignException
    Description: The function fetches the templates list of the specified range, sorted by 'sort_column' name.
    If no params are passed, it fetches the 100 results sorted by last modified time of the category type.


deleteTemplate()
  Params: [INT] template_id
  Return: true
  Throws: SignException
  Description: Permanently deletes the template.
```


Exceptions
----------
All functions of class 'ZohoSign' on event of bad/invalid requests to Zoho Sign throws class SignException.
The Error message will be formatted like :
> SIGN EXCEPTION : [error code] : error message


Examples
--------
Below examples are assuming ZohoSign::currentUser is set.

#### Create a Zoho Sign request using a document with text-tags and a signer. Send the draft for signature.
```
    $reqObj = new RequestObject();
    $reqObj->setRequestName		( 'Partnership Agreement' );

    $partner = new Actions();
    $partner->setRecipientName	( "Will Smith" );
    $partner->setRecipientEmail	( "Willsmith@zylker.com" );
    $partner->setActionType		( Actions::SIGNER );
    $partner->setisEmbedded		( true );

    $reqObj->addAction	( $partner );

    $file1 = $_SERVER['DOCUMENT_ROOT']."/Documents/TextTagsAPI.pdf";
    $files = [
        new CURLfile( $file1 )
    ];

    $draftJSON	= ZohoSign::draftRequest( $reqObj,  $files);
```

#### Use template to create a request and send for signature
```
    $template = ZohoSign::getTemplate( 2000002608137 );

    $template->setPrefillBooleanField	( "Premium Partner", true );
    $template->setPrefillTextField ( "Company", "Incredibles Inc" );
    $template->setPrefillDateField ( "Date", "08 July 2020" );

    $template->getActionByRole("Partner")->setRecipientName("John");
    $template->getActionByRole("Partner")->setRecipientEmail("john@incredibles.com");

    $resp_obj = ZohoSign::sendTemplate( $template, false );
```
