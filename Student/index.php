<?php
include("DB_connection.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$reg_no = $_SESSION['id'];

// Error handling for database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT current_level, batch, reg_no, email FROM student WHERE reg_no = ?";
$stmt = $conn->prepare($query);

// Error handling for SQL statement preparation
if (!$stmt) {
    die("Statement preparation failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("s", $reg_no);

// Error handling for SQL execution
if (!$stmt->execute()) {
    die("Statement execution failed: (" . $stmt->errno . ") " . $stmt->error);
}

$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Welcome Page</title>
</head>
<body> 
  <?php
  include "navbar.php";
  ?>
<div class="container">
    <h5 style="text-align:center;">Welcome <?php echo htmlspecialchars($_SESSION['name']); ?>!</h5>

    <div class="card mb-3 mx-auto details-card" style="width: 50rem;">
        <div class="card-body" style="padding: 5px;">
            <h5 class="card-title" style="margin-bottom: 5px;">Current Level: <?php echo htmlspecialchars($student['current_level']); ?></h5>  
        </div>
        <div class="card-body" style="padding: 5px;">
            <h5 class="card-title" style="margin-bottom: 5px;">Batch No: <?php echo htmlspecialchars($student['batch']); ?></h5>  
        </div>
        <div class="card-body" style="padding: 5px;">
            <h5 class="card-title" style="margin-bottom: 5px;">Reg No: <?php echo htmlspecialchars($student['reg_no']); ?></h5>  
        </div>
        <!--<div class="card-body" style="padding: 10px;">
            <h5 class="card-title" style="margin-bottom: 5px;">Index No:  ?></h5>  
        </div>-->
        <div class="card-body" style="padding: 5px;">
            <h5 class="card-title" style="margin-bottom: 5px;">Email: <?php echo htmlspecialchars($student['email']); ?></h5>  
        </div>
        <div class="card-body d-flex justify-content-end">
            <a href="edit.php" class="btn btn-primary btn-custom">Edit</a>
        </div>
    </div>
    <h5 style="margin-top: 20px;">Check your attendance below</h5>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="attendance/sem1-attendance.php" class="btn btn-secondary btn-custom">Semester 1</a>
        <a href="attendance/sem2-attendance.php" class="btn btn-secondary btn-custom">Semester 2</a>
        <a href="attendance/sem3-attendance.php" class="btn btn-secondary btn-custom">Semester 3</a>
        <a href="attendance/sem4-attendance.php" class="btn btn-secondary btn-custom">Semester 4</a>
        <a href="attendance/sem5-attendance.php" class="btn btn-secondary btn-custom">Semester 5</a>
        <button class="btn btn-secondary btn-custom">Semester 6</button>
        <button class="btn btn-secondary btn-custom">Semester 7</button>
        <button class="btn btn-secondary btn-custom">Semester 8</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function(){
    $("#navLinks li:nth-child(1) a").addClass('active');
  });
</script>
</body>
</html>
