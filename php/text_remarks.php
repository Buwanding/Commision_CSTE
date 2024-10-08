<?php
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require(__DIR__ . '/../vendor/autoload.php');
require 'db.php';

session_start(); // Start the session at the beginning

if (!isset($_SESSION['activity_id'], $_SESSION['student_email'], $_SESSION['remarks'], $_SESSION['activity_name'], $_SESSION['subject_name'])) {
    echo "Session variables are missing.";
    exit();
}

$activity_id = $_SESSION['activity_id'];
$student_email = $_SESSION['student_email'];
$remarks = $_SESSION['remarks'];
$activity_name = $_SESSION['activity_name'];
$subject_name = $_SESSION['subject_name'];

$stud_query = "SELECT parents.parents_name, parents.contact_number 
               FROM parents 
               INNER JOIN users ON users.id = parents.student_id 
               WHERE users.email = ?";
$stud_stmt = $conn->prepare($stud_query);
if ($stud_stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stud_stmt->bind_param("s", $student_email); // Note: Use 's' for string type
$stud_stmt->execute();
$stud_result = $stud_stmt->get_result();

while ($parent = $stud_result->fetch_assoc()) {
    $phonenum = $parent['contact_number'];
    $message = " Good day! " . $parent['parents_name'] . 
               " Please be informed that your child has received a score on " . $activity_name . 
               " for their " . $subject_name . " activity. Thank you. " . $remarks;
}

$apiURL = "dkqvq1.api.infobip.com";
$apiKey = "796ffed709e2c1204a7d2052677522ca-2283dd9d-37e3-4ee8-a193-2ffaa2e9b57a";

$configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
$api = new SmsApi(config: $configuration);

$destination = new SmsDestination(to: $phonenum);
$themessage = new SmsTextualMessage(
    destinations: [$destination],
    text: $message,
    from: "Syntax Flow"
);

$request = new SmsAdvancedTextualRequest(messages: [$themessage]);
$response = $api->sendSmsMessage($request);

echo '<script>alert("Successfully sent the message");</script>';
header("Location: ../teachers-page/dashboard.php");
exit();
?>
