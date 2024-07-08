<?php
include("DB_connection.php");

if (isset($_GET['semester'])) {
    $semester = $_GET['semester']; // No need for mysqli_real_escape_string when using PDO

    // Prepare and execute the query using PDO
    $query = "SELECT course_code, course_name FROM course WHERE semester = :semester ORDER BY course_code ASC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();

    $course_options = "<option value='' disabled selected>Select Course</option>";

    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $course_options .= "<option value='{$result['course_code']}'>{$result['course_name']}</option>";
    }

    echo $course_options;
}
?>
