<?php
include("../DB_connection.php");
$regno = $_GET['editid'];
$student = null;

if ($regno) {
    $stmt = $conn->prepare("SELECT reg_no, fname, mname, lname, email, status, password FROM student WHERE reg_no = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

if(isset($_POST['update']))
{
    $regno = $_POST['reg_no'];
    $fname =$_POST['fname'];
    $mname =$_POST['mname'];
    $lname =$_POST['lname'];
    $email =$_POST['email'];
    $status =$_POST['status'];
 
    $stmt = $conn->prepare("UPDATE student SET fname = ?, mname = ?, lname = ?, email = ?, status = ? WHERE reg_no = ?");
    $stmt->bind_param("ssssss", $fname, $mname, $lname, $email, $status, $regno);
    $result = $stmt->execute();

    if ($result) {
        // If the status is set to "Active", insert or update the user in the user table
        if ($status == 'Active') {
            $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
            $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
            $fullNameWithInitials = trim($fnameInitial . ' ' . $mnameInitial . ' ' . $lname);
            
            // Retrieve the password from the student table
            $password = $student['password'];

            // Check if the user already exists in the user table
            $userCheckStmt = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");
            $userCheckStmt->bind_param("s", $regno);
            $userCheckStmt->execute();
            $userCheckResult = $userCheckStmt->get_result();

            if ($userCheckResult->num_rows > 0) {
                // If the user already exists, update their details
                $userUpdateStmt = $conn->prepare("UPDATE user SET name = ?, role = 'Student', email = ?, password = ? WHERE user_id = ?");
                $userUpdateStmt->bind_param("sssss", $fullNameWithInitials, $email, $password, $regno);
                $userUpdateStmt->execute();
                $userUpdateStmt->close();
            } else {
                // If the user does not exist, insert a new record
                $userInsertStmt = $conn->prepare("INSERT INTO user (user_id, name, role, email, password) VALUES (?, ?, 'Student', ?, ?)");
                $userInsertStmt->bind_param("ssss", $regno, $fullNameWithInitials, $email, $password);
                $userInsertStmt->execute();
                $userInsertStmt->close();
            }

            $userCheckStmt->close();
        }

        echo "Data Updated Successfully";
        header('Location: Student-List.php');
    } else {
        die(mysqli_error($conn));
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>
<body  class="body-home">

    <?php
    include("../DB_connection.php");
    ?>
    
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="container">
    
        <div class="d-flex justify-content-center align-items-center">
        <form class="add-lecturer" method = "post">

            <div class="text-center"> 
                <h5>Edit Student Details</h5>
            </div>
   
            <div class="mb-3">
                        <label class="form-label">Registration No.:</label>
                        <input type="text" class="form-control" name="reg_no" value="<?php echo htmlspecialchars($student['reg_no'] ?? ''); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="fname" value="<?php echo htmlspecialchars($student['fname'] ?? ''); ?>" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Middle Name:</label>
                        <input type="text" class="form-control" name="mname" value="<?php echo htmlspecialchars($student['mname'] ?? ''); ?>" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="lname" value="<?php echo htmlspecialchars($student['lname'] ?? ''); ?>" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <select class="form-control" name="status" required>
                            <option value="">Select Status</option>
                            <option value="Pending" <?php echo (isset($student['status']) && $student['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Active" <?php echo (isset($student['status']) && $student['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                        </select>
                    </div>
            
            <button type="submit" class="btn btn-primary" name="update">Edit</button>
            
            <a href="Student-List.php" class="text-decoration-none">Back</a>
        </form>
        </div>

       
                    
     
        
        </div>
        <footer class="text-center">
            <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
    </footer>

    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>