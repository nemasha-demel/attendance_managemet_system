<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>

<body class="body-home">
<?php
// Include the DB connection
include("../DB_connection.php");

// Check for required query parameters
if (isset($_GET['semester']) && isset($_GET['specialization']) && isset($_GET['batch'])) {
    $batch = mysqli_real_escape_string($conn, $_GET['batch']);
    $semester = mysqli_real_escape_string($conn, $_GET['semester']);
    $specialization = mysqli_real_escape_string($conn, $_GET['specialization']);
} else {
    echo "<div class='alert alert-danger' role='alert'>Batch, Semester, or Specialization is missing.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $credits = intval($_POST['credits']);
    $lecture_hours = intval($_POST['lecture_hours']);
    
    if ($credits == 2 || $credits == 3) {
        $sql = "INSERT INTO `course` (course_name, course_code, credits, lecture_hours, semester, specialization, batch) VALUES
                ('$course_name', '$course_code', '$credits', '$lecture_hours', '$semester', '$specialization', '$batch')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<div class='alert alert-success' role='alert'>Course added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error adding course: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Invalid credits value. Please select 2 or 3.</div>";
    }
}
?>




    <div class="container">
        <div class="content d-flex justify-content-center align-items-center">
        <form class="add-lecturer" method="POST" action="Add-Course.php?batch=<?php echo $batch; ?>&semester=<?php echo $semester; ?>&specialization=<?php echo $specialization; ?>">

                <h5 style="text-align:center;">Add New Course</h5>
                
                <div class="mb-3">
                    <label for="course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required>
                </div>

                <div class="mb-3">
                    <label for="course_code" class="form-label">Course Code</label>
                    <input type="text" class="form-control" id="course_code" name="course_code" required>
                </div>

                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <select class="form-control" id="credits" name="credits" required>
                        <option value="">Select Credits</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="lecture_hours" class="form-label">Lecture Hours</label>
                    <input type="number" class="form-control" id="lecture_hours" name="lecture_hours" required>
                </div>

                

                <button type="submit" class="btn btn-primary">Add Course</button>
                <a href="Course-List.php" class="text-decoration-none btn btn-secondary">Back</a>
            </form>
        </div>
        <footer class="text-center">
            <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
