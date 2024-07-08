<?php
include("DB_connection.php");
session_start();

$updateSuccess = false;

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

if (isset($_POST['update'])) {
    $newEmail = $_POST['email'];

    // Update the email in the student table
    $updateStudentQuery = "UPDATE student SET email = ? WHERE reg_no = ?";
    $updateStudentStmt = $conn->prepare($updateStudentQuery);

    if (!$updateStudentStmt) {
        die("Update statement preparation failed: (" . $conn->errno . ") " . $conn->error);
    }

    $updateStudentStmt->bind_param("ss", $newEmail, $reg_no);

    if ($updateStudentStmt->execute()) {
        // Update the email in the user table
        $updateUserQuery = "UPDATE user SET email = ? WHERE user_id = ?";
        $updateUserStmt = $conn->prepare($updateUserQuery);

        if (!$updateUserStmt) {
            die("Update statement preparation failed: (" . $conn->errno . ") " . $conn->error);
        }

        $updateUserStmt->bind_param("ss", $newEmail, $reg_no);

        if ($updateUserStmt->execute()) {
            $updateSuccess = true; // Set the flag to true if update is successful
            echo '<script>alert("Email updated successfully!"); window.location = "index.php";</script>'; // Show alert and redirect
            exit(); // Exit to prevent further execution
        } else {
            die("Update statement execution failed: (" . $updateUserStmt->errno . ") " . $updateUserStmt->error);
        }
    } else {
        die("Update statement execution failed: (" . $updateStudentStmt->errno . ") " . $updateStudentStmt->error);
    }
}
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
    <div class="card update-form-card ">
        <div class="card-body">
            <h5 class="card-title text-center update-details-text">Update Details</h5>

            <!-- Display student details within the form -->
            <form method="post">
                <div class="form-group mb-4">
                    <label>Current Level</label>
                    <input type="text" class="form-control" placeholder="Enter Current Level" autocomplete="off" value="<?php echo htmlspecialchars($student['current_level']); ?>" readonly>
                </div>

                <div class="form-group mb-4">
                    <label>Batch No</label>
                    <input type="text" class="form-control" placeholder="Enter Batch No" autocomplete="off" value="<?php echo htmlspecialchars($student['batch']); ?>" readonly>
                </div>

                <div class="form-group mb-4">
                    <label>Registration No</label>
                    <input type="text" class="form-control" placeholder="Enter Registration No" autocomplete="off" value="<?php echo htmlspecialchars($student['reg_no']); ?>" readonly>
                </div>

                <div class="form-group mb-4">
                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="Enter Email" autocomplete="off" value="<?php echo htmlspecialchars($student['email']); ?>" name="email">
                </div>

                <button type="submit" class="btn btn-primary btn-custom" name="update">Update</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function(){
    $("#navLinks li:nth-child(1) a").addClass('active');
    <?php if ($updateSuccess): ?>
      alert("Email updated successfully!");
    <?php endif; ?>
  });
</script>
</body>
</html>
