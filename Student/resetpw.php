<?php
include("DB_connection.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$reg_no = $_SESSION['id'];

$updateSuccess = false;
$errorMessage = "";

// Check if the form is submitted
if (isset($_POST['update'])) {
    // Retrieve form data
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        $errorMessage = "New password and confirm password do not match.";
    } else {
        // Fetch the current hashed password from the user table
        $fetchPasswordQuery = "SELECT password FROM user WHERE user_id = ?";
        $fetchPasswordStmt = $conn->prepare($fetchPasswordQuery);

        if (!$fetchPasswordStmt) {
            die("Fetch password statement preparation failed: (" . $conn->errno . ") " . $conn->error);
        }

        $fetchPasswordStmt->bind_param("s", $reg_no);
        $fetchPasswordStmt->execute();
        $fetchPasswordResult = $fetchPasswordStmt->get_result();
        $row = $fetchPasswordResult->fetch_assoc();

        // Verify the current password
        if (password_verify($currentPassword, $row['password'])) {
            // Hash the new password before storing it in the database
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password in user table
            $updateUserQuery = "UPDATE user SET password = ? WHERE reg_no = ?";
            $updateUserStmt = $conn->prepare($updateUserQuery);

            if (!$updateUserStmt) {
                die("Update statement preparation failed: (" . $conn->errno . ") " . $conn->error);
            }

            $updateUserStmt->bind_param("ss", $hashedPassword, $reg_no);

            if ($updateUserStmt->execute()) {
                $updateSuccess = true;
                // Password updated successfully, redirect to login page
                echo '<script>alert("Password updated successfully! Please login again."); window.location = "login.php";</script>';
                exit();
            } else {
                $errorMessage = "Error updating password in user table: " . $updateUserStmt->error;
            }
        } else {
            $errorMessage = "Current password is incorrect.";
        }
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
    <title>Reset Password</title>
</head>
<body> 
  <?php
  include "navbar.php";
  ?>
  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(2) a").addClass('active');
        });
    </script>
<div class="container">
    <div class="card update-form-card">
        <div class="card-body">
            <h5 class="card-title text-center update-details-text">Reset Password</h5>

            
            <form method="post">
                <div class="form-group mb-4">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter Current Password" required>
                </div>

                <div class="form-group mb-4">
                    <label for="newPassword">New Password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter New Password" required>
                </div>

                <div class="form-group mb-4">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" required>
                </div>
                
                <!-- Display error message if there's an error -->
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-custom my-5" name="update">Reset</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
