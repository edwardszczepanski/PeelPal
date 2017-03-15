<?php
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
include('./twilioConfig.txt');
echo $auth;

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
//$sid ='ACd0c4af2cce951c932c29c894e971249b';
//$token = $auth;
$sid = "ACd0c4af2cce951c932c29c894e971249b";
$token = "your_auth_token";
$client = new Client($sid, $token);

$sms = $client->messages->create(
    "+15036168728",
    array("from" => "+15005550006", 'body' =>"test test")
);
echo $sms->sid;

/*
echo $number->sid;
echo "arst";

// Use the client to do fun stuff like send text messages!
$client->messages->create(
    // the number you'd like to send the message to
    '+15036168728',
    array(
        // A Twilio phone number you purchased at twilio.com/console
        'from' => $number->phone_number,
        // the body of the text message you'd like to send
        'body' => "This is a super cool test!"
    )
);
*/
