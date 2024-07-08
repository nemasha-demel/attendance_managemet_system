<?php
include("../DB_connection.php");

$course_code = isset($_GET['coursecode']) ? $_GET['coursecode'] : '';

if ($course_code) {
    $query = "SELECT lecturer FROM course WHERE course_code = '$course_code'";
    $result = mysqli_query($conn, $query);

    $lecturer_list = "<option value='' disabled selected>Select Lecturer</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $lecturer_list .= "<option value='{$row['lecturer']}'>{$row['lecturer']}</option>";
    }
    echo $lecturer_list;
} else {
    echo "<option value='' disabled selected>Select Lecturer</option>";
}
?>
