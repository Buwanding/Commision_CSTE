<?php
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require(__DIR__ . '/../vendor/autoload.php');
require '../php/db.php'; // Ensure database connection is included

session_start();

if (!isset($_SESSION['subject_id'], $_SESSION['activity_name'], $_SESSION['deadline'])) {
    echo "Session variables are missing.";
    exit();
}

$subject_id = $_SESSION['subject_id'];
$activity_name = $_SESSION['activity_name'];
$deadline = $_SESSION['deadline'];

// Fetch students and their parents
$stud_query = "SELECT * FROM student_subjects WHERE subject_id = ?";
$stud_stmt = $conn->prepare($stud_query);
$stud_stmt->bind_param("i", $subject_id);
$stud_stmt->execute();
$stud_result = $stud_stmt->get_result();

$students = [];
while ($stud = $stud_result->fetch_assoc()) {
    $parent_query = "SELECT parents.parents_name, parents.contact_number 
                     FROM parents 
                     INNER JOIN users ON parents.student_id = users.id 
                     WHERE users.email = ?";
    $parent_stmt = $conn->prepare($parent_query);
    $parent_stmt->bind_param("s", $stud['student_email']); // Assuming email is a string
    $parent_stmt->execute();
    $parent_result = $parent_stmt->get_result();
    
    $parents = [];
    while ($parent = $parent_result->fetch_assoc()) {
        $parents[] = $parent;
    }
    $students[] = [
        'student' => $stud,
        'parents' => $parents
    ];
    $parent_stmt->close();
}

$stud_stmt->close();

$apiURL = "8gprrd.api.infobip.com";
$apiKey = "2db44b4c40f78de1ca10449c921a1e48-2d77bd07-7047-4cbe-9ac0-54520fec118e";

$configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
$api = new SmsApi(config: $configuration);

foreach ($students as $student_data) {
    foreach ($student_data['parents'] as $parent) {
        $phonenum = $parent['contact_number'];
        $message = "Reminder Maam/Sir " . $parent['parents_name'] . " has an activity in " . $activity_name . " with a deadline " . $deadline;
        
        $destination = new SmsDestination(to: $phonenum);
        $themessage = new SmsTextualMessage(
            destinations: [$destination],
            text: $message,
            from: "Syntax Flow"
        );

        $request = new SmsAdvancedTextualRequest(messages: [$themessage]);
        $response = $api->sendSmsMessage($request);
    }
}

echo '<script>alert("Successfully sent the message");</script>';
header("Location: ../teachers-page/dashboard.php");
exit();
?>
