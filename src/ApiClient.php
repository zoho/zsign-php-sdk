<?php

namespace zsign;

use zsign\OAuth;
use zsign\ZohoSign;
use zsign\UpdateOAuth;

abstract class ApiClient{
        
    const GET    = "GET";
    const POST   = "POST";
    const PUT    = "PUT";
    const DELETE = "DELETE";

    private static $retry_attempts = 0;
    
    public static function callURL( $URL, $method, $queryparams, $postData=[], $MultipartFormData=false, $file_response=false){

        // determine zoho domain and allow authorization. (?)
        return self::makeCall( $URL, $method, $queryparams, $postData, $MultipartFormData, $file_response, false );

    }

    public static function callSignAPI( $api, $method, $queryparams, $postData=[], $MultipartFormData=false, $file_response=false, $authorizedCall = true ){

        $URL = ZohoSign::getCurrentUser()->getBaseURL().$api;

        return self::makeCall( $URL, $method, $queryparams, $postData, $MultipartFormData, $file_response, true );   
    }

    private static function makeCall( $URL, $method, $queryparams, $postData=[], $MultipartFormData, $file_response, $authorizedCall ){
        
        if( isset($queryparams) ){
            if( strpos( $URL, "?") == false ){
                $URL .= "?".http_build_query($queryparams);
            }else{
                $URL .= "&".http_build_query($queryparams);
            }
        }

        $access_token = ZohoSign::getCurrentUser()->getAccessToken();

        if( $authorizedCall && !isset( $access_token ) ){
            $resp = ZohoSign::getCurrentUser()->generateAccessTokenUsingRefreshToken();
            if( !isset($resp) ){
                throw new SignException("Authorization Missing(Access Token/ Refresh Token)", -1);
            }
        }

        $HEADERS = array();
        if( $authorizedCall ){
            array_push( $HEADERS, 'Authorization:Zoho-oauthtoken '.ZohoSign::getCurrentUser()->getAccessToken() );    
        }
        if( isset( $MultipartFormData ) && $MultipartFormData ){
            array_push( $HEADERS, "Content-Type:multipart/form-data");
        }


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          // CURLOPT_SAFE_UPLOAD => false,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_HTTPHEADER => $HEADERS
          // ,CURLOPT_VERBOSE   => 1
        )); 

       // $fp;
       // $headers;
        if( $file_response ){
                $path = ZohoSign::getDownloadPath();
                $Fname = $path."zs_temp_file";

                if( !is_dir($path) ){
                    throw new SignException("Unable to write to path($path).", -1);
                }

                $fp = fopen ( $Fname, 'w+');

                curl_setopt($curl, CURLOPT_FILE, $fp);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_HEADERFUNCTION,
                  function($curl, $header) use (&$headers)
                  {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                      return $len;

                    $headers[strtolower(trim($header[0]))][] = trim($header[1]);

                    return $len;
                  }
                );
        }


        if( $MultipartFormData ){
            curl_setopt( $curl, CURLOPT_SAFE_UPLOAD, true); //CHANGED FALSE TO TRUE
        }
        if( ($method == self::POST || $method == self::PUT || $method == self::DELETE) && !empty($postData) ){
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );
        }

        $response   = curl_exec($curl);        
        $status     = curl_getinfo( $curl );    
        
                
        curl_close($curl); 

        //$mediaName;
        
        $http_code_msg = "HTTP Code : ".$status["http_code"].". ";

        if( $authorizedCall ){
            //   SIGN API/Authorized call

            if( $status["http_code"]!=401 ){
                $retry_attempts = 0;
            }

            switch( $status["http_code"] ){
                
                case 0:
                    throw new SignException( $http_code_msg."Message : Call failed to initiate from client | ", -1 );
                    break;



                case 200:
                    if( $file_response ){
                        
                        $mediaName = substr( explode("=", explode(";", $headers['content-disposition'][0])[1] )[1], 1,-1);
                        if( isset($mediaName) ){
                            rename( $path."zs_temp_file", $path.urldecode($mediaName) );
                        }
                        fclose($fp);
                        return true;

                    }else{ 
                        $responseJson = json_decode( $response );
                        if( $responseJson->code == 0 ){

                            return json_decode( $response );
                        
                        }else{

                            // sometimes, error in status 200 ?
                            $errorMessage = self::constructErrorMessageFromAPIResponse( $responseJson );
                            throw new SignException( $http_code_msg . $errorMessage, $responseJson->code );
                        
                        }
                    }
                    break;



                case 400:
                    //Bad Request - HANDLE ZS ERROR CODES
                    if( isset( json_decode( $response )->code ) ){
                        $responseJson =  json_decode($response);
                        $errorMessage = self::constructErrorMessageFromAPIResponse( $responseJson );
                        throw new SignException( $http_code_msg . $errorMessage, $responseJson->code );
                    }else{
                        throw new SignException( $http_code_msg." Bad Request. $method : $URL ", -1 );
                    }
                    break;




                case 401:
                    //Unauthorised Access
                    $response = json_decode( $response );

                    if(  $authorizedCall  ){ // if authorized call and 401, refresh token ..
                        // Access token Expired
                        $resp = ZohoSign::getCurrentUser()->generateAccessTokenUsingRefreshToken();
                        // UpdateOAuth::updateAccessToken($resp);
                        if( isset($resp) && $retry_attempts==0 ){
                            ++$retry_attempts;
                            return self::makeCall( $URL, $method, $queryparams, $postData, $MultipartFormData, $file_response, $authorizedCall  );
                        }else{
                            throw new SignException( $response->message, $response->code );
                        }
                    }
                    else{
                        throw new SignException( $http_code_msg . $response->message, $response->code);
                    }
                    break;



                case 403:
                    //Forbidden
                case 404:
                    //Not-Found
                case 500:
                    //Internal Server
                default:
                    $resp = json_decode( $response );
                    $err = $http_code_msg." | Message : ". ( isset($resp->message) ? $resp->message : $resp ) ." | ";
                    throw new SignException( $err, isset($resp->code) ? $resp->code : -1 );
                    break;

            }
        }else{
            // non authorized call
            $httpObject = new \stdClass();

            $httpObject->status           = $status["http_code"];
            $httpObject->data             = (json_decode( $response )!=null) ? json_decode( $response ) : $response;
            $httpObject->url              = $URL;
            $httpObject->method           = $method;
            $httpObject->request_headers  = $HEADERS;
            if( isset($mediaName) ){
                $httpObject->response_headers = $mediaName;
            }

            return $httpObject;
        }
    }

    private static function constructErrorMessageFromAPIResponse( $response ){
        
        // it is possible there are more keys than basic ones.

        $errorMessage = "";

        $responseArr = json_decode( json_encode($response), true );        

        $errorMessage = $response->message;
        
        if( count( $responseArr ) <= 3 ){
            // not code, message, status
            // more keys are present, like error_param : error_key
            $errorMessage = $response->message.". " ;

            foreach ( $responseArr as $key => $value) {
                switch ($key) {
                    case 'code':
                    case 'message':
                    case 'status':
                        break;
                    default:
                        $errorMessage .= "$key : $value. ";
                        break;
                }
            }
        }
        return $errorMessage;
    }

}
