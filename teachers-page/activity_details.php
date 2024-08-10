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
                <li><a href="dashboard.php">  HOME </i> </a></li>
                <li><a href="#"> PROFILE </i> </a></li>
                <li><a href="../index.html" class="logout">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="activity-details">
        <center><h1>ACTIVITY DETAILS</h1></center>

        <h2>Student Email:</h2>
        <p><?php echo htmlspecialchars($activity['student_email'] ?? ''); ?></p>
        <br>
        <h2>Description:</h2>
        <p><?php echo htmlspecialchars($activity['description'] ?? ''); ?></p>
        <br>
        <h2>Deadline:</h2>
        <p><?php echo htmlspecialchars($activity['deadline'] ?? ''); ?></p>
        <br>
        <h2>Date Submitted:</h2>
        <p><?php echo htmlspecialchars($activity['timepass'] ?? ''); ?></p>
        <br>
        <h2>Remarks:</h2>
        <br>
        <form action="" method="post">
            <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
            <textarea id="remarks" name="remarks" required><?php echo htmlspecialchars($activity['remarks'] ?? ''); ?></textarea>
            <button type="submit" class="update-button"><i class="fa fa-pencil"></i> Update Remarks</button>
        </form>
        <br>
        <h2>Student File:</h2>
        <?php if (!empty($activity['student_file'])): ?>
            <form action="" method="post">
                <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
                <textarea id="remarks" name="remarks" required><?php echo htmlspecialchars($activity['remarks'] ?? ''); ?></textarea>
                <button type="submit" class="download-button">
                    <i class="fa fa-save"></i> Update Remarks
                </button>
            </form>

        <?php else: ?>
            <p>No file submitted.</p>
        <?php endif; ?>
        <br>
    </div>
</main>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
    </div>
</footer>

</body>
</html>
