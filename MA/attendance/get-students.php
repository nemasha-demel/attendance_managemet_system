<?php
include("../DB_connection.php");

if (isset($_GET['batch']) && isset($_GET['specialization'])) {
    $batch = mysqli_real_escape_string($conn, $_GET['batch']);
    $specialization = mysqli_real_escape_string($conn, $_GET['specialization']);

    $batch = trim($batch);
    $specialization = trim($specialization);

    // Check for the special case of "Computer Engineering and EEE"
    if ($specialization == "Computer Engineering and EEE") {
        $sql = "SELECT fname, mname, lname, reg_no FROM student 
                WHERE batch = '$batch' AND (specialization = 'Computer Engineering' OR specialization = 'Electrical and Electronic Engineering')";
    } else {
        $sql = "SELECT fname, mname, lname, reg_no FROM student 
                WHERE batch = '$batch' AND specialization = '$specialization'";
    }
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $regno = $row['reg_no'];
            $fname = $row['fname'];
            $mname = $row['mname'];
            $lname = $row['lname'];

            // Format name with initials
            $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
            $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
            $fullNameWithInitials = $fnameInitial . ' ' . $mnameInitial . ' ' . $lname;

            echo '<tr>
                <td>' . $fullNameWithInitials . '</td>
                <td>' . $regno . '</td>
                <td><input type="checkbox" name="student[' . $regno . ']"></td>
            </tr>';
        }
    } else {
        echo '<tr><td colspan="3">No students found for the selected batch and specialization</td></tr>';
    }
}
?>
