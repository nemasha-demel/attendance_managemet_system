<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";

$lecturer_id = $_SESSION['id'];

// Fetch subjects for the logged-in lecturer
$sql = "SELECT c.course_code, c.course_name 
        FROM course c
        WHERE lecturer_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // Handle the error if prepare() fails
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit;
}

$stmt->bind_param('s', $lecturer_id); // Binding the parameter

if (!$stmt->execute()) {
    // Handle the error if execute() fails
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    exit;
}

$result = $stmt->get_result(); // Get the result set
$courses = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as associative array

// Free the result and close the statement
$result->free();
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Student Attendance Management System</title>
</head>
<body>
<?php include "../inc/navbar.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
      $(document).ready(function(){
        $("#navLinks li:nth-child(1) a").addClass('active');
      });
  </script>

  <!-- Show course and attendance -->
  <section id="CourseandAtt" class="Course_attendance d-flex justify-content-center align-items-center flex-column">
    <h4>Course and Attendance</h4>
    <div class="card mb-3" style="width: 50rem;">
      <?php foreach ($courses as $course): ?>
      <div class="card-body d-flex justify-content-between align-items-center">
        <h5 class="card-title"><?= htmlspecialchars($course['course_code']) ?> - <?= htmlspecialchars($course['course_name']) ?></h5>
        <a href="report.php?course_code=<?= urlencode($course['course_code']) ?>" class="btn btn-primary">View Attendance</a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
</body>
</html>
