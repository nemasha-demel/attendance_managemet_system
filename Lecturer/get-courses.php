<?php
include("DB_connection.php");

if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['lecturer_id'])) {
    $batch = $_GET['batch'];
    $semester = $_GET['semester'];
    $lecturer_id = $_GET['lecturer_id'];

    // Prepare and execute the query using PDO
    $query = "SELECT course_name, course_code, credits, lecture_hours FROM course 
              WHERE batch = :batch AND semester = :semester AND lecturer_id = :lecturer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':batch', $batch);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':lecturer_id', $lecturer_id);
    $stmt->execute();

    $course_list = "";
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $course_list .= "
            <tr>
                <td>{$row['course_name']}</td>
                <td>{$row['course_code']}</td>
                <td>{$row['credits']}</td>
                <td>{$row['lecture_hours']}</td>
            </tr>";
        }
    } else {
        $course_list = "<tr><td colspan='4'>No courses found</td></tr>";
    }

    echo $course_list;
} else {
    echo "<tr><td colspan='4'>Invalid academic year, semester, or lecturer ID</td></tr>";
}
?>
