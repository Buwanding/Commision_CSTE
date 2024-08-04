<?php
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require(__DIR__ . '/../vendor/autoload.php');

$message = $_POST["message"];
$phonenum = $_POST["phonenum"];

$apiURL = "8gprrd.api.infobip.com";
$apiKey = "2db44b4c40f78de1ca10449c921a1e48-2d77bd07-7047-4cbe-9ac0-54520fec118e";


$configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
$api = new SmsApi(config: $configuration);
$destination = new SmsDestination(to: $phonenum);
$themessage = new SmsTextualMessage(
    destinations: [$destination],
    text: $message,
    from: "Syntax Flow",
);

$request = new SmsAdvancedTextualRequest(messages: [$themessage]);
$response = $api->sendSmsMessage($request);

echo '<script>alert ("Successfully sent the message");</script>';


?>

