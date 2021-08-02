<?php
namespace Web2sms;

abstract class Web2sms {    
    const SMS_PLATFORM_URL            = "https://www.web2sms.ro";        // Mandatory 
    const SMS_METHOD                  = "POST";                          // Mandatory 
    const SMS_URL_PREPAIID            = "/prepaid/message";              // Mandatory
    const SMS_URL_POSTPAID            = "/send/message";                 // Mandatory

    public $accountType;
    public $apiKey;
    public $secretKey;
    public $messages;


    public function __construct(){
        //
    }
    
    // Send request json to SMS
    public function sendRequest($smsItem) {
       switch($this->accountType) {
        case 'postpaid':
            $selectedEndpointURL = self::SMS_URL_POSTPAID;
            break;
        case 'prepaid' :
            $selectedEndpointURL = self::SMS_URL_PREPAIID;
            break;
        default:
            $selectedEndpointURL = self::SMS_URL_PREPAIID;
       }
        
       $string = $this->apiKey . $smsItem->nonce . self::SMS_METHOD . $selectedEndpointURL . $smsItem->sender .
       $smsItem->recipient . $smsItem->body . $smsItem->visibleMessage . $smsItem->scheduleDatetime .
       $smsItem->validityDatetime . $smsItem->callbackUrl . $this->secretKey;

       $signature = hash('sha512', $string);

       $data = array(
        "apiKey"            => $this->apiKey,
        "sender"            => $smsItem->sender,
        "recipient"         => $smsItem->recipient,
        "message"           => $smsItem->body,
        "scheduleDatetime"  => $smsItem->scheduleDatetime,
        "validityDatetime"  => $smsItem->validityDatetime,
        "callbackUrl"       => $smsItem->callbackUrl,
        "userData"          => $smsItem->userData,
        "visibleMessage"    => $smsItem->visibleMessage,
        "nonce"             => $smsItem->nonce);


        $url = self::SMS_PLATFORM_URL.$selectedEndpointURL; // Endpoint URL

        $ch = curl_init($url);
        
        $payload = json_encode($data); // json DATA

        // Set to include the header in the output.
        curl_setopt($ch, CURLOPT_HEADER, true);

        // Set the authorization signature
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $signature);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-length: '.strlen($payload)));

        // Regular HTTP POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 

        // Execute the POST request
        $result = curl_exec($ch);
        
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                case 201:
                    $arr = array(
                        'status'  => 1,
                        'code'    => $http_code,
                        'msg'     => "SMS sent successfully",
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 400:  # Bad Request
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'Bad Request',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 401:  # Unauthorized
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'Authentication token or the authentication token was expired.',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 403:  # Forbidden
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'No permission to access the requested resource.',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 404:  # Not Found 
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'Not Found',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 405:  # Method Not Allowed
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'Method Not Allowed',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 409:  # Conflict
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'request could not be completed,there is a conflict.',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 415:  # Unsupported Media Type.
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'Unsupported Media Type.',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                case 500:  # Internal Server Error.
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'Request was not completed. There is an internal error on the server side.',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                case 503:  # Service Unavailable.
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => 'The server was unavailable.',
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
                default:
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'msg'     => null,
                        'data'    => $this->getSpecificData($result, $http_code)
                    );
                break;
            }
        } else {
            $arr = array(
                'status' => 0,
                'code'   => $http_code,
                'msg'    => null,
                'data'   => $result
            );
        }
        
        // Close cURL resource
        curl_close($ch);
        
        $finalResult = json_encode($arr);
        die(print_r($finalResult));
        return $finalResult;
    }

    public function getSpecificData($data, $code) {
        if($code >= 200 && $code < 300) {
            if (($pos = strpos($data, '{"id":"')) !== false) { 
                $pureJsonData = substr($data, $pos);
                $pureArrData = json_decode($pureJsonData);
                return $pureArrData;
            }
        }else {
            if (($pos = strpos($data, '{"error":{')) !== false) { 
                $pureJsonData = substr($data, $pos);
                $pureArrData = json_decode($pureJsonData);
                return $pureArrData;
            }
        }
    }
}