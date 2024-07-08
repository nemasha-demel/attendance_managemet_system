<?php
include("../DB_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $batch = mysqli_real_escape_string($conn, $_POST['batch']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $coursecode = mysqli_real_escape_string($conn, $_POST['coursecode']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);

    $students = isset($_POST['student']) ? $_POST['student'] : [];
    
    $query = "SELECT reg_no FROM student WHERE batch = '$batch' AND specialization = '$specialization'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reg_no = $row['reg_no'];
            $status = isset($students[$reg_no]) ? 'Present' : 'Absent';

            $sql = "INSERT INTO attendance (reg_no, course_code, date, time, status) 
                    VALUES ('$reg_no', '$coursecode', '$date', '$time', '$status')";

            if (!mysqli_query($conn, $sql)) {
                echo "Error: " . mysqli_error($conn);
                exit;
            }
        }
        echo "Attendance saved successfully!";
    } else {
        echo "Error fetching students: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}
?>
