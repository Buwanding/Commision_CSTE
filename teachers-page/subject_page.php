<?php
$subject_id = isset($_REQUEST["subject_id"]) ? $_REQUEST["subject_id"] : '';
$subject_name = isset($_REQUEST["subject"]) ? htmlspecialchars($_REQUEST["subject"]) : '';
$subject_des = isset($_REQUEST["subject_des"]) ? htmlspecialchars($_REQUEST["subject_des"]) : '';
// Ensure subject_id and subject are set and valid
if (empty($subject_id) || empty($subject_name)) {
    // Handle the error or redirect to a different page
    echo "Subject not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($_REQUEST["subject"]); ?></title>
    <link rel="stylesheet" href="./teacher-styles/subject-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
        <div class="header-container">

            <div class="system-name">
                <a href="dashboard.php"><h1>STUDENT ACTIVITY MANAGEMENT SYSTEM</h1></a>
            </div>

            <div class="icons">
                <div class="notification-icon">
                    <a href="#">
                    	<i style="font-size:24px" class="fa">&#xf0f3;</i>
                    </a>
                </div>

                <div class="profile-icon">
                    <a href="#">
						<i style="font-size:24px" class="fa">&#xf007;</i>
                    </a>
                </div>

                <div class="logout">
                    <a href="../index.html">LOGOUT</a>
                </div>
            </div>
        </div>
</header>

<br>
<br>

<main>


    <div class="subject-details"> 
        <center> <h1><?php echo htmlspecialchars($_REQUEST["subject"]); ?></h1> </center> 
            <h2><?php echo htmlspecialchars($_REQUEST["subject_des"]); ?> </h2>

        <br>

        <div class="button-container">
            <button class="button">ADD ACTIVITY</button>
            <button class="button">LIST OF STUDENTS</button>
            <button class="button">LIST OF PARENTS</button>
        </div>

        <br>


        <!-- Form to add activities -->
        <div class="form-container">
            <h2>Add Activity</h2>
            <br>
            <form action="../php/add_activity.php" method="post">
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>">
                <label for="activity_name">Activity Name:</label>
                <input type="text" id="activity_name" name="activity_name" required>
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
                <label for="deadline">Deadline:</label>
                <input type="date" id="deadline" name="deadline" required>
                <br>
                <button type="submit">Add Activity</button>
            </form>
        </div>

        <br>

        <br>

        <!-- Form to assign students -->
        <div class="form-container">
            <h2>Assign Student</h2>
            <br>
            <form action="../php/assign_student.php" method="post">
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>">
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>
                <br>
                <button type="submit">Assign Student</button>
            </form>
        </div>

        <br>

         <!-- Delete Subject Button -->
         <form action="../php/delete_subject.php" method="post">
            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>">
            <h2> Delete Subject </h2>
            <button type="submit" class="delete-button">Delete Subject</button>
        </form>

        <br>
        <br>
        <br>

        <!-- Display activities and students -->
        <div class="list-container">
            <h2>Activities</h2>
            <ul>
                <?php
                // Fetch activities for the subject
                require '../php/db.php'; // Ensure database connection is included
                $activity_query = "SELECT * FROM activities WHERE subject_id = ?";
                $activity_stmt = $conn->prepare($activity_query);
                $activity_stmt->bind_param("i", $subject_id);
                $activity_stmt->execute();
                $activity_result = $activity_stmt->get_result();
                
                while ($activity = $activity_result->fetch_assoc()) {
                    echo "<li><a href='activity_details.php?activity_id=" . htmlspecialchars($activity['id']) . "'>" . htmlspecialchars($activity['activity_name']) . "</a>: " . htmlspecialchars($activity['description']) . "</li>";
                }
                
                $activity_stmt->close();
                ?>
            </ul>
        </div>

        <div class="list-container">
            <h2>Assigned Students</h2>
            <ul>
                <?php
                // Fetch students assigned to the subject
                $student_query = "SELECT * FROM student_subjects WHERE subject_id = ?";
                $student_stmt = $conn->prepare($student_query);
                $student_stmt->bind_param("i", $subject_id);
                $student_stmt->execute();
                $student_result = $student_stmt->get_result();
                
                while ($student = $student_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($student['student_email']) . "</li>";
                }
                
                $student_stmt->close();
                ?>
            </ul>
        </div>
       <div class="list-container">
    <h2>List Parents</h2>
    <ul>
        <?php
        $stud_query = "SELECT * FROM student_subjects WHERE subject_id = ?";
        $stud_stmt = $conn->prepare($stud_query);
        $stud_stmt->bind_param("i", $subject_id);
        $stud_stmt->execute();
        $stud_result = $stud_stmt->get_result();

        while ($stud = $stud_result->fetch_assoc()) {
            $parent_query = "SELECT parents.parents_name, parents.contact_number 
                             FROM parents 
                             INNER JOIN users ON parents.student_id = users.id 
                             WHERE users.email = ?";
            $parent_stmt = $conn->prepare($parent_query);
            $parent_stmt->bind_param("s", $stud['student_email']); // Assuming email is a string
            $parent_stmt->execute();
            $parent_result = $parent_stmt->get_result();
            
            while ($parent = $parent_result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($parent['parents_name']) . "</li>";
                echo "<li>" . htmlspecialchars($parent['contact_number']) . "</li>";
            }
            $parent_stmt->close();
        }

        $stud_stmt->close();
        ?>
    </ul>
</div>


    </div>
</main>

<br>
<br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
    </div>
</footer>

</body>
</html>
