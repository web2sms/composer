<?php
namespace Web2sms\Sms;

class SendSMS extends Web2sms{
    public function __construct(){
        //
    }

    public function setRequest(){
        $this->verifyRequest();
    }

    public function sendSMS(){
        foreach($this->validMessageList as $smsItem) {
            $response[] = $this->sendRequest($smsItem);
        }
        return $response;
    }

    public function verifyRequest() {
        if(!isset($this->apiKey) || is_null($this->apiKey) || empty($this->apiKey)) {
            throw new \Exception('INVALID_APIKEY');
            exit;
        }

        if(!isset($this->secretKey) || is_null($this->secretKey) || empty($this->secretKey)) {
            throw new \Exception('INVALID_SECRET_KEY');
            exit;
        }

        if(!isset($this->messages) || is_null($this->messages) || empty($this->messages)) {
            throw new \Exception('INVALID_MESSAGE_LIST');
            exit;
        }

        foreach($this->messages as $msgItem) {
            if(!isset($msgItem['recipient']) || is_null($msgItem['recipient']) || empty($msgItem['recipient'])) {
                throw new \Exception('INVALID_RECIVER');
                exit;
            }

            if(!isset($msgItem['body']) || is_null($msgItem['body']) || empty($msgItem['body'])) {
                throw new \Exception('INVALID_SMS_BODY');
                exit;
            }

            $message = new \StdClass();
            $message->sender = (isset($msgItem['sender'])  && !empty($msgItem['sender'])) ? $msgItem['sender'] : '' ;
            $message->recipient = $msgItem['recipient'];
            $message->body = $msgItem['body'];
            $message->scheduleDatetime = (isset($msgItem['scheduleDatetime'])  && !empty($msgItem['scheduleDatetime'])) ? strtotime($msgItem['scheduleDatetime']) : '' ;
            $message->validityDatetime = (isset($msgItem['validityDatetime'])  && !empty($msgItem['validityDatetime'])) ? strtotime($msgItem['validityDatetime']) : '' ;
            $message->callbackUrl = (isset($msgItem['callbackUrl'])  && !empty($msgItem['callbackUrl'])) ? $msgItem['callbackUrl'] : '' ;
            $message->userData = (isset($msgItem['userData'])  && !empty($msgItem['userData'])) ? $msgItem['userData'] : 'Web2sms Composer' ;
            $message->visibleMessage = (isset($msgItem['visibleMessage'])  && !empty($msgItem['visibleMessage'])) ? $msgItem['visibleMessage'] : '' ;
            $message->nonce = time();

            $this->validMessageList[] = $message; 
        }
    }
}
