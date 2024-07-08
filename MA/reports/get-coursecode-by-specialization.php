<?php
include("../DB_connection.php");

if (isset($_GET['semester']) && isset($_GET['specialization'])) {
    $semester = mysqli_real_escape_string($conn, $_GET['semester']);
    $specialization = mysqli_real_escape_string($conn, $_GET['specialization']);

    $query = "SELECT course_name,course_code FROM course WHERE semester = '{$semester}' AND specialization = '{$specialization}'";
    $result_set = mysqli_query($conn, $query);

    $course_code_list = "<option value='' disabled selected>Course Code</option>";
    while ($result = mysqli_fetch_assoc($result_set)) {
        $course_code_list .= "<option value=\"{$result['course_code']}\">{$result['course_code']} - {$result['course_name']}</option>";
    }
    echo $course_code_list;
} else {
    echo "<option value=''>Error fetching course codes</option>";
}
?>
