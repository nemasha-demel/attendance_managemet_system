<?php
session_start();
include "../DB_connection.php";

if (isset($_POST['semester']) && isset($_POST['academic_year']) && isset($_SESSION['name'])) {
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    $lecturer_name = $_SESSION['name'];

    $sql = "SELECT course_name, course_code, credits, lecture_hours 
            FROM course 
            WHERE semester = ? AND academic_year = ? AND lecturer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $semester, $academic_year, $lecturer_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = array();
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    echo json_encode($courses);
} else {
    echo json_encode([]);
}
?>
