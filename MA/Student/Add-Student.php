<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>

<body class="body-home">
    <?php
    include("../DB_connection.php");
    if (isset($_GET['addid'])) {
        $id = $_GET['addid'];
        
        // Fetch current_level and academic_year from batch table using the batch id
        $batchQuery = "SELECT current_level, academic_year FROM batch WHERE batch = '$id'";
        $batchResult = mysqli_query($conn, $batchQuery);
        
        if ($batchResult && mysqli_num_rows($batchResult) > 0) {
            $batchData = mysqli_fetch_assoc($batchResult);
            $current_level = $batchData['current_level'];
            $academic_year = $batchData['academic_year'];
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid batch ID or batch details not found.</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Batch is missing.</div>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $regno = $_POST['reg_no'];
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $lname = $_POST['lname'];
       
        $specialization = $_POST['specialization'];
        $email = $_POST['email'];
        $password = $_POST['password'];
       

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Format name with initials
    $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
    $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
    $fullNameWithInitials = $fnameInitial . ' ' . $mnameInitial . ' ' . $lname;

        if ($specialization == 'General' || $specialization == 'Computer Engineering' || $specialization == 'Electrical and Electronic Engineering' || $specialization == 'Mechanical Engineering' || $specialization == 'Civil Engineering') {
            $sqlStudent = "INSERT INTO `student` (reg_no, email, password, fname, mname, lname, academic_year,current_level, specialization, batch) 
                    VALUES ('$regno', '$email', '$hashedPassword', '$fname', '$mname', '$lname', '$academic_year', '$current_level', '$specialization', '$id')";
            $resultStudent = mysqli_query($conn, $sqlStudent);

             // Insert user data into `user` table
            $role = 'student';  // Assuming role is 'student' for all new entries
            $sqlUser = "INSERT INTO `user` (user_id, name, email, role, password) 
                        VALUES ('$regno', '$fullNameWithInitials', '$email', '$role', '$hashedPassword')";
            $resultUser = mysqli_query($conn, $sqlUser);

            if ($resultStudent && $resultUser) {
                echo "<div class='alert alert-success' role='alert'>Student added successfully.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error adding student or user: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid specialization value. Please select General or Computer Engineering or Electrical and Electronic Engineering or Mechanical Engineering.</div>";
        }
    }
    ?>
    <div class="container">
        

        <div class="content d-flex justify-content-center align-items-center">
            <form class="add-lecturer" method="POST" action="Add-Student.php?addid=<?php echo $id; ?>">
                <h5 style="text-align:center;">Add New Student</h5>
                <div class="mb-3">
                    <label for="reg_no" class="form-label">Registration No.</label>
                    <input type="text" class="form-control" id="reg_no" name="reg_no" required>
                </div>

                <div class="mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" required>
                </div>

                <div class="mb-3">
                    <label for="mname" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="mname" name="mname">
                </div>

                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control" id="password" name="password" required>
                </div>

                

                <div class="mb-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <select class="form-control" id="specialization" name="specialization" required>
                        <option value="">Select Specialization</option>
                        <option value="General">General</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Add Student</button>
                <a href="Student-List.php" class="text-decoration-none">Back</a>
            </form>
        </div>
        <footer class="text-center">
            <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
