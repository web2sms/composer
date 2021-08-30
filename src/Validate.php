<?php
namespace Web2sms\Sms;

class Validate extends Web2sms{
    const SENDER_LENGTH = 11;               //max sender length

    /**
     * Sender validate 
     * Sender max length is 11 
     * Sender can be null
     */
    public function senderValidate(string $str) {
        if(strlen($str) > 11){
            $str = substr($str, 0 , self::SENDER_LENGTH);
        }
        return $str;
    }

    /**
     * Validate recipient
     * Recipient is a mobil number
     * Mobil number shouldn't be with + | 00
     * Valid form is 07PPXXXXXX | 407PPXXXXXX | 7PPXXXXXX
     * NOTE : currently is permis only 07PPXXXXXX
     */
    public function recipientValidate(string $phoneNumber) {
        if(!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
            throw new Exception('Not valid phone number.');
        }

        if (!preg_match("/^07$/", substr($phoneNumber,0,2))) {
            throw new Exception('Local mobil number can not start with '.substr($phoneNumber,0,2).'.');
        }
    }

    /**
     * Validate message
     * Message can not be empty
     */
    public function messageValidate (string $message) {
        if(is_null($message)) {
            throw new Exception('Can not send empty message.');
        }
    }

    public function dataValidate ($scheduleDate) {
        if(!is_null($scheduleDate)) {
            switch ($scheduleDate) {
                // case validateDate($scheduleDate, 'Y-m-d\TH:i:sP'):
                case validateDate($scheduleDate, 'Y-m-d\TH:i:s') :
                case validateDate($scheduleDate, 'Y-m-d H:i:s')  :
                    break;
                default:
                    throw new Exception('Data format is not correct.'); 
            }
        }
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
}