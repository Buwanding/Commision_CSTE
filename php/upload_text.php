<?php
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require(__DIR__ . '/../vendor/autoload.php');
require 'db.php';

session_start(); // Start the session at the beginning

if (!isset($_SESSION['activity_id'], $_SESSION['student_email'], $_SESSION['timepass'], $_SESSION['activity_name'], $_SESSION['subject_name'])) {
    echo "Session variables are missing.";
    exit();
}

$activity_id = $_SESSION['activity_id'];
$student_email = $_SESSION['student_email'];
$timepass = $_SESSION['timepass'];
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

$phonenum = null; // Initialize to null to detect if it's set later

while ($parent = $stud_result->fetch_assoc()) {
    $phonenum = $parent['contact_number'];
    $message = "Reminder Maam/Sir " . $parent['parents_name'] . 
               " your son/daughter has passed his/her " . $activity_name . 
               " in " . $subject_name . " on date " . $timepass;
}

$stud_stmt->close();

if ($phonenum === null) {
    echo "No phone number found for the student's parent.";
    exit();
}

$apiURL = "dkqvq1.api.infobip.com";
$apiKey = "796ffed709e2c1204a7d2052677522ca-2283dd9d-37e3-4ee8-a193-2ffaa2e9b57a";

$configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
$api = new SmsApi(config: $configuration);

try {
    $destination = new SmsDestination(to: $phonenum);
    $themessage = new SmsTextualMessage(
        destinations: [$destination],
        text: $message,
        from: "Syntax Flow"
    );

    $request = new SmsAdvancedTextualRequest(messages: [$themessage]);
    $response = $api->sendSmsMessage($request);

    echo '<script>alert("Successfully sent the message");</script>';
    header("Location: ../student-page/dashboard.php");
    exit();
} catch (Exception $e) {
    echo 'Message sending failed: ' . $e->getMessage();
}
?>
