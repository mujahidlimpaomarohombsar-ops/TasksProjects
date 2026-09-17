<?php
//get data from form
$student_name = trim($_POST["student_name"] ?? "");
$student_id = trim($_POST["student_id"] ?? "");

$grade1 = $_POST["grade1"] ?? "";
$grade2 = $_POST["grade2"] ?? "";
$grade3 = $_POST["grade3"] ?? "";

// Check 
if (
    $student_name === "" ||
    $student_id === "" ||
    $grade1 === "" ||
    $grade2 === "" ||
    $grade3 === ""
) {
    echo "Please complete all fields.";
    exit;
}

// Convert 
$grade1 = (float)$grade1;
$grade2 = (float)$grade2;
$grade3 = (float)$grade3;

// Validate 
if (
    $grade1 < 0 || $grade1 > 100 ||
    $grade2 < 0 || $grade2 > 100 ||
    $grade3 < 0 || $grade3 > 100
) {
    echo "Grades must be between 0 and 100.";
    exit;
}

// Calculate average
$average = ($grade1 + $grade2 + $grade3) / 3;

// status
if ($average >= 75) {
    $status = "PASSED";
} else {
    $status = "FAILED";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Grade Result</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="top-nav" aria-label="Main navigation">
        <a href="index.php">Student Grade Calculator</a>
    </nav>

    <div class="container result-container">

        <div class="student-info">

            <p>
                <strong>Student Name:</strong>
                <?php echo htmlspecialchars($student_name); ?>
            </p>

            <p>
                <strong>Student ID:</strong>
                <?php echo htmlspecialchars($student_id); ?>
            </p>

        </div>

        <div class="grades-result">

            <div class="grade-box">
                <span>Grade 1</span>
                <strong><?php echo $grade1; ?></strong>
            </div>

            <div class="grade-box">
                <span>Grade 2</span>
                <strong><?php echo $grade2; ?></strong>
            </div>

            <div class="grade-box">
                <span>Grade 3</span>
                <strong><?php echo $grade3; ?></strong>
            </div>

        </div>

        <div class="average">

            <p>Final Average</p>

            <h2>
                <?php echo number_format($average, 2); ?>
            </h2>

        </div>

        <div class="status">

            <p>Status</p>

            <h2>
                <?php echo $status; ?>
            </h2>

        </div>

        <a href="index.php" class="back-button">
            Calculate Another Student
        </a>

    </div>

</body>
</html>