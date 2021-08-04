<div align="center"><a href="https://www.web2sms.ro"><img alt="Parsedown" src="https://www.web2sms.ro/assets/themes/public/images/front/logo.png" /></a></div>

# WEB2SMS SRL
### WEB2SMS Composer

## Introduction

The WEB2SMS PHP library provides easy access to the send SMS via WEB2SMS API from any
applications written in the PHP language.

For more details, please do not hesitate to contact us - contact@web2sms.ro

## Compatibility
PHP 5.7.0 - 8.0.8

## Installation 

You can install the library via [Composer](http://getcomposer.org/). Run the following command:

```
    composer require web2sms/sms
```

### URLs
* **WEB2SMS platform** : <https://www.web2sms.ro/>
* **POSTPAID endpoint** : <https://www.web2sms.ro/send/message/>
* **PREPAID endpoint**  : <https://www.web2sms.ro/prepaid/message/>



### Actions
* #### **Send SMS**
        
    To send one / a set of **SMS** ,

    * **Prepaid action URL :** /prepaid/message/
    * **Postpaid action URL:** /send/message/
    * **Method:** `POST`    
    
        
    #### An example

    ```php
        ...
        
        use Web2sms\Sms\SendSMS;
        $sendSMS = new SendSMS();

        $sender->accountType = 'prepaid';                                 // postpaid | prepaid          // Optional
        $sendSMS->apiKey     = 'API_KEY_FROM_THE_PLATFORM';               // ApiKey from Platform        // Mandatory
        $sendSMS->secretKey  = 'SECRET_KEY_FROM_THE_PLATFORM';            // secretKey from Platform     // Mandatory

        // SMS #1
        $sendSMS->messages[]  = [
                            'sender'            => ''          ,          // who send the SMS             // Optional
                            'recipient'         => '07XXXXXXXX',          // who receive the SMS          // Mandatory
                            'body'              => 'This is the actual content of SMS nr one',            // Mandatory
                            'scheduleDatetime'  => 'YYYY-MM-DD 10:20:10', // Date & Time to send SMS      // Optional
                            'validityDatetime'  => null,                  // Date & Time of expire SMS    // Optional
                            'callbackUrl'       => 'DOMAIN/XXX/',         // Full callback URL            // Optional    
                            'userData'          => null,                  // User data                    // Optional
                            'visibleMessage'    => false                  // false / True                 // Optional
                            ];

        ...

        // SMS #N
        $sendSMS->messages[]  = [
                            'sender'            => ''          ,          // who send the SMS             // Optional
                            'recipient'         => '07XXXXXXXX',          // who receive the SMS          // Mandatory
                            'body'              => 'This is the actual content of SMS nr N'               // Mandatory
                            'scheduleDatetime'  => null,                  // Date & Time to send SMS      // Optional
                            'validityDatetime'  => null,                  // Date & Time of expire SMS    // Optional
                            'callbackUrl'       => 'DOMAIN/XXX/',         // Full callback URL            // Optional    
                            'userData'          => null,                  // User data                    // Optional
                            'visibleMessage'    => false                  // false / True                 // Optional
                            ];


        $sendSMS->setRequest();
        $sendSMS->sendSMS();

        ...
    ```
    #### Parameters
    * **accountType :** The "accountType" define the type of your account in WEB2SMS platform. WEB2SMS has two type of account, **"postpaid"** & **"prepaid"** . The **prepaid** defined as default account Type.
    * **apiKey      :** The "apiKey" is your unique ID to work with WEB2SMS API, this parameter is Mandatory.
    * **secretKey   :** "secretKey" is another unique ID to work with WEB2SMS API, this parameter is also Mandatory.
    * **messages    :** The "messages" is your actual SMS. You can define one message to be send as SMS, or define a **list of messages** to be send to your destination. As is shown in above example, any message of the list is individual, and can have diffrent configuration, text content or even send in diffrent time.
    
       * **sender           :** The "sender" is the actual number, what will sending the SMS to the destination phone number. The sender is an optional parameter, and the phone number on destination will be apear as what is defined in the Platform. if you have several predefined number in the platform, so you can pickup one of them as SMS sender. 

       * **recipient        :** The "recipient" is the actual phone number of the SMS destination. This is a mandatory parameter.
       * **body             :** The "body" is the actual content of the SMS. The "body" is a mandatory parameter.
       * **scheduleDatetime :** To set the **date & time** of SMS sending. By setting this option you will be able to define one / set of SMS  to be sent in any interval of time in the future . The format of this parameter is like : **Y-m-d H:i:s** as Ex. **2021-12-01 08:59:30**  . this parameter is optional.
       * **validityDatetime :**  To set a expire date & time for SMS sending. This is an optional parameter.
       * **callbackUrl      :** The "callBackUrl", is an URL from your website to sending the feedback for each individual SMS  after SMS is send to the destination, to be informed about each individual SMS. This is an optional parameter.
       * **userData         :** "userData" is an string given by you, to be used in the reports generated in the WEB2SMS platform. this option is helping you to categorize the informations. As example if you sending to many SMS for diffrent events, you can choese a **event title** as **userData** to be apear in the reports  
      * **visibleMessage    :** The "visibleMessage" option is to Hide & Show the SMS content in WEB2SMS platform, to protect the sensitive Data . you can set this parameter as **TRUE** | **FALSE**. as defulte the SMS content is apearing in the platform. this parameter is optional.

### Error codes defination

* **536870913** : Internal web2SMS error 
* **268435457** : No available account for the calling IP                             
* **268435463** : Associated account is disabled  
* **268435462** : Associated account is missconfigured                                  
* **268435464** : Internal web2SMS error while creating SMS Sender                                   
* **268435458** : Parameter phone has a wrong format or it belongs to a GSM.Network that is not configured for the associated account!                                               
        
* **268435466** : Phone number is black listed
* **268435520** : Phone number belongs to a GSN Network that is not configured for the associated account 
* **268435460** : You’ve exceeded your monthly limit for SMS sending
* **268435488** : You are trying to schedule a SMS message outside the configured time interval restrictions
* **268435459** : Parameter message is empty! Empty message are not allowed 
* **268435465** : Internal web2SMS error while scheduling a SMS


### Techincal support
mail : support@web2sms.ro


##### Resources
###### ( <a href="https://sites.google.com/a/netopia-system.com/wiki-web2sms/api-web2sms-rest-client" target="_blank">WEB2SMS Documentation</a> )

