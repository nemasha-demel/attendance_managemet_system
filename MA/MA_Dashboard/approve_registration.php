<?php
include("../DB_connection.php");


if(isset($_GET['reg_no'])) {
    $reg_no = $_GET['reg_no'];

    // Fetch student details based on reg_no
    $sql_select = "SELECT fname, mname, lname, email FROM student WHERE reg_no = '$reg_no' AND status = 'Pending'";
    $result_select = mysqli_query($conn, $sql_select);

    if(mysqli_num_rows($result_select) == 1) {

        $student_data = mysqli_fetch_assoc($result_select);

        $fname = $student_data['fname'];
        $mname = $student_data['mname'];
        $lname = $student_data['lname'];
        $email = $student_data['email'];
      
        $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
        $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
        $fullNameWithInitials = $fnameInitial . ' ' . $mnameInitial . ' ' . $lname;

       
        $password = password_hash('default_password', PASSWORD_DEFAULT);

        // Insert into user table
        $sql_insert_user = "INSERT INTO user (user_id, name,role, email, password) 
                            VALUES ('$reg_no','$fullNameWithInitials', 'Student', '$email', '$password')";
        $result_insert_user = mysqli_query($conn, $sql_insert_user);

        if($result_insert_user) {
            // Update student status to Active
            $sql_update_student = "UPDATE student SET status = 'Active' WHERE reg_no = '$reg_no'";
            $result_update_student = mysqli_query($conn, $sql_update_student);

            if($result_update_student) {
                
                header("Location: ".$_SERVER['HTTP_REFERER']);
                exit();
            } else {
                echo "Error updating student status: " . mysqli_error($conn);
            }
        } else {
            echo "Error inserting user record: " . mysqli_error($conn);
        }
    } else {
        echo "Student not found or already approved.";
    }
} else {
    echo "Invalid request.";
}
?>
