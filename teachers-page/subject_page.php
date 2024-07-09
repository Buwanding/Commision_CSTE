<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subject['subject_name']) ?></title>
    <link rel="stylesheet" href="./teacher-styles/subject-style.css">
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($subject['subject_name']) ?></h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../index.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="subject-details" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
            <p>Details for subject: <?= htmlspecialchars($subject['subject_name']) ?></p>
            <!-- Form to add activities -->
            <h2>Add Activity</h2>
            <form action="../php/add_activity.php" method="post">
                <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
                <label for="activity_name">Activity Name:</label>
                <input type="text" id="activity_name" name="activity_name" required>
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
                <button type="submit">Add Activity</button>
            </form>
            
            <!-- Form to assign students -->
            <h2>Assign Student</h2>
            <form action="../php/assign_student.php" method="post">
                <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>
                <button type="submit">Assign Student</button>
            </form>

            <!-- Display activities and students -->
            <h2>Activities</h2>
            <ul>
                <?php
                // Fetch activities for the subject
                $activity_query = "SELECT * FROM activities WHERE subject_id = ?";
                $activity_stmt = $conn->prepare($activity_query);
                $activity_stmt->bind_param("i", $subject['id']);
                $activity_stmt->execute();
                $activity_result = $activity_stmt->get_result();
                
                while ($activity = $activity_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($activity['activity_name']) . ": " . htmlspecialchars($activity['description']) . "</li>";
                }
                
                $activity_stmt->close();
                ?>
            </ul>
            
            <h2>Assigned Students</h2>
            <ul>
                <?php
                // Fetch students assigned to the subject
                $student_query = "SELECT * FROM student_subjects WHERE subject_id = ?";
                $student_stmt = $conn->prepare($student_query);
                $student_stmt->bind_param("i", $subject['id']);
                $student_stmt->execute();
                $student_result = $student_stmt->get_result();
                
                while ($student = $student_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($student['student_name']) . "</li>";
                }
                
                $student_stmt->close();
                ?>
            </ul>
        </div>
    </main>
</body>
</html>
