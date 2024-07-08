<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>
<body class="body-home">

<?php
include("../DB_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        echo "New password and confirm password do not match.";
        exit;
    }

    // Fetch the old password from the database
    $sql = "SELECT password FROM MA WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($old_password, $row['password'])) {
        // Old password matches, update with new password
        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE MA SET password = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $updateuser_sql = "UPDATE user SET password = ? WHERE email = ?";
        $updateuser_stmt = $conn->prepare($updateuser_sql);
        $update_stmt->bind_param("ss", $new_password_hashed, $email);

        if ($update_stmt->execute()) {
            echo "Password changed successfully.";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Old password is incorrect.";
    }

    $stmt->close();
    $conn->close();
}
?>
     <?php include "../inc/navbar.php"; ?>
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
        <div class="container">
            
            <div class="d-flex justify-content-center align-items-center">
                <form class="add-lecturer" action="Reset-Password.php" method="POST">
                    <div class="text-center">
                        <h5>Reset Password</h5>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <input type="password" class="form-control" name="old_password" required>   
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-danger">Cancel</button>
                </form>
            </div>
            <footer class="text-center">
                <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

