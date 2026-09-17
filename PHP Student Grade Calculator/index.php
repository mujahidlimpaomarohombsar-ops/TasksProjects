<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grade Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="top-nav" aria-label="Main navigation">
        <a href="index.php">Student Grade Calculator</a>
    </nav>

    <div class="container">

        <p class="subtitle">Enter the student's information and grades.</p>

        <form action="process.php" method="POST" id="gradeForm">

            <div class="form-group">
                <label for="student_name">Student Name</label>
                <input 
                    type="text" 
                    id="student_name" 
                    name="student_name"
                    placeholder="Enter student name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input 
                    type="text" 
                    id="student_id" 
                    name="student_id"
                    placeholder="Enter student ID"
                    required
                >
            </div>

            <h2>Grades</h2>

            <div class="grades">

                <div class="form-group">
                    <label for="grade1">Grade 1</label>
                    <input 
                        type="number" 
                        id="grade1" 
                        name="grade1"
                        min="0"
                        max="100"
                        placeholder="0 - 100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="grade2">Grade 2</label>
                    <input 
                        type="number" 
                        id="grade2" 
                        name="grade2"
                        min="0"
                        max="100"
                        placeholder="0 - 100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="grade3">Grade 3</label>
                    <input 
                        type="number" 
                        id="grade3" 
                        name="grade3"
                        min="0"
                        max="100"
                        placeholder="0 - 100"
                        required
                    >
                </div>

            </div>

            <button type="submit">Calculate Final Result</button>

        </form>

    </div>

    <script src="script.js"></script>

</body>
</html>