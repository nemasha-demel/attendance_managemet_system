<?php
include("DB_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the form data
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $name_with_initials = $_POST['name_with_initials'];
    $reg_no = $_POST['reg_no'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $current_level = $_POST['current_level'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Validate the input (simple example, you can add more validation)
    if ($password !== $repeat_password) {
        echo '<script>alert("Passwords do not match!"); window.history.back();</script>';
    } else {
        // Fetch the academic_year and batch based on the current_level
        $query = "SELECT academic_year, batch FROM batch WHERE current_level = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error preparing the statement: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("s", $current_level);
        $stmt->execute();
        $stmt->bind_result($academic_year, $batch);
        $stmt->fetch();
        $stmt->close();

        // Check if the academic_year and batch were found
        if (!$academic_year || !$batch) {
            echo '<script>alert("Invalid current level!"); window.history.back();</script>';
        } else {
            // Insert the data into the student table
            $query = "INSERT INTO student (reg_no, email, password, fname, mname, lname, academic_year, batch, current_level, specialization, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";  // 'pending' as default for status

            $stmt = $conn->prepare($query);

            // Check if the statement was prepared correctly
            if ($stmt === false) {
                die("Error preparing the statement: " . htmlspecialchars($conn->error));
            }

            // Bind the parameters
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssssssssss", $reg_no, $email, $hashed_password, $fname, $mname, $lname, $academic_year, $batch, $current_level, $specialization);

            if ($stmt->execute()) {
                echo '<script>alert("Registration form submitted successfully for approval!"); window.location = "login.php";</script>';
            } else {
                echo "Error submitting registration request: " . htmlspecialchars($stmt->error);
            }

            // Close the statement
            $stmt->close();
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <link rel="icon" href="img/download.png">  <!--Icon of title bar -->
</head>
<body class="body-login">
    <div class="black-fill"><br><!--If you don't want to shade remove this division-->
        
        <div class="d-flex justify-content-center align-items-center">
            <form class="register" method="POST" action="">
                <div class="text-center">
                    <img src="img/download.png" alt="logo">
                    <h5>Register</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="fname" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="mname" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lname" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name With Initials</label>
                    <input type="text" class="form-control" name="name_with_initials" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Registration No.</label>
                    <input type="text" class="form-control" name="reg_no" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Level</label>
                    <select class="form-select" name="current_level" required>
                        <option value="" disabled selected>Select your current level</option>
                        <option value="Year 1">Year 1</option>
                        <option value="Year 2">Year 2</option>
                        <option value="Year 3">Year 3</option>
                        <option value="Year 4">Year 4</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Specialization</label>
                    <select class="form-select" name="specialization" required>
                        <option value="" disabled selected>Select your specialization</option>
                        <option value="General">General</option>
                        <option value="Computer engineering">Computer Engineering</option>
                        <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Repeat Password</label>
                    <input type="password" class="form-control" name="repeat_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="index.php" class="text-decoration-none">Home</a>
            </form>
        </div>
        <footer class="text-center">
            <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
