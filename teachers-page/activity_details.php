<?php
// Ensure database connection is included
require '../php/db.php';

// Fetch activity details
$activity_id = $_GET['activity_id'] ?? null;

if ($activity_id) {
    $activity_query = "SELECT ad.student_email, ad.remarks, ad.timepass, ad.student_file_path, a.description, a.deadline 
                       FROM activity_details ad 
                       JOIN activities a ON ad.activity_id = a.id 
                       WHERE ad.activity_id = ?";
    $activity_stmt = $conn->prepare($activity_query);
    $activity_stmt->bind_param("i", $activity_id);
    $activity_stmt->execute();
    $activity_result = $activity_stmt->get_result();
} else {
    echo "Activity ID is missing.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Details</title>
    <link rel="stylesheet" href="./teacher-styles/subject-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label>
            <nav>
                <ul>
                    <li><a href="dashboard.php">HOME</a></li>
                    <li><a href="#">PROFILE</a></li>
                    <li><a href="../index.html" class="logout">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="activity-details">
            <center><h1>ACTIVITY DETAILS</h1></center>
            <table>
                <thead>
                    <tr>
                        <th>Student Email</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Time Submitted</th>
                        <th>Remarks</th>
                        <th>Student File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($activity = $activity_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['student_email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($activity['description'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($activity['deadline'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($activity['timepass'] ?? 'N/A'); ?></td>
                            <td>
                                <form action="../php/update_remarks.php" method="post">
                                    <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
                                    <textarea id="remarks" name="remarks" required><?php echo htmlspecialchars($activity['remarks'] ?? ''); ?></textarea>
                                    <button type="submit">Update Remarks</button>
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($activity['student_file_path'])): ?>
                                    <a href="../php/download.php?file_path=<?php echo urlencode($activity['student_file_path']); ?>">Download File</a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php
// Close the statement and connection
$activity_stmt->close();
$conn->close();
?>
