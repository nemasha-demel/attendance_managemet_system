<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lecturer - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!-- Icon of title bar -->
</head>
<body class="body-home">
    
    <?php
    include("../DB_connection.php");
    ?>

<?php
    if(isset($_POST["submit"])){
        $fullName = $_POST["name"];
        $profession = $_POST["profession"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeat_password"];

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $errors = array();

        if(empty($fullName) || empty($profession) || empty($email) || empty($password) || empty($passwordRepeat)) {
            array_push($errors, "All fields are required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
        }
        if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long.");
        }
        if ($password != $passwordRepeat) {
            array_push($errors, "Passwords do not match");
        }

        // Check if email already exists
        $sql = "SELECT * FROM lecturer WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Email already exists");
        }
        mysqli_stmt_close($stmt);

        if (count($errors) > 0) {
            foreach($errors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            } 
        } else {
            // Insert the lecturer
            $sql = "INSERT INTO lecturer(name, profession, email, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $fullName, $profession, $email, $passwordHash);
            if (mysqli_stmt_execute($stmt)) {
                $lecturer_id = mysqli_insert_id($conn); // Get the last inserted lecturer ID
                mysqli_stmt_close($stmt);
                
                // Insert into user table
                $sqlUser = "INSERT INTO user(user_id, name, role, email, password) VALUES (?, ?, ?, ?, ?)";
                $stmtUser = mysqli_prepare($conn, $sqlUser);
                $role = 'lecturer'; // Fixed role for lecturer
                mysqli_stmt_bind_param($stmtUser, "issss", $lecturer_id, $fullName, $role, $email, $passwordHash);
                if (mysqli_stmt_execute($stmtUser)) {
                    echo "<div class='alert alert-success'>Lecturer added successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Failed to add lecturer to user table.</div>";
                }
                mysqli_stmt_close($stmtUser);
            } else {
                echo "<div class='alert alert-danger'>Failed to add lecturer.</div>";
            }
        }
    }
    ?>

    <div class="black-fill"> <br></br><!-- If you don't want to shade, remove this division -->
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                
                
                <form class="add-lecturer" method="post" action="">    
                    <div class="text-center">
                        <h5>Add Lecturer</h5>  
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Full Name" required>   
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profession</label>
                        <input type="text" class="form-control" name="profession" placeholder="Profession" required>   
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>  
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" name="submit">Save</button>
                    <a href="Lecturer-List.php" class="btn btn-secondary">Back</a>
                    <a href="Lecturer-List.php" class="btn btn-danger">Cancel</a>
                </form>
            </div>
        </div>
        <footer class="text-center">
            <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
