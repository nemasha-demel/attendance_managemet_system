<?php
include("DB_connection.php");

if (isset($_GET['course_code']) && isset($_GET['chapter'])) {
    $course_code = $_GET['course_code'];
    $chapter = $_GET['chapter'];

    // Prepare and execute the DELETE query using PDO
    $query = "DELETE FROM schedule WHERE course_code = :course_code AND chapter = :chapter";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':course_code', $course_code);
    $stmt->bindParam(':chapter', $chapter);

    if ($stmt->execute()) {
        echo '<script>alert("Record deleted successfully!"); window.location = "timeschedule.php";</script>';
        exit; // Make sure to exit after redirection
    } else {
        die("Error deleting record: " . $stmt->errorInfo()[2]);
    }
} else {
    echo "Invalid parameters";
}
?>
