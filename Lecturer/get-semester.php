<?php
include("DB_connection.php");

if (isset($_GET['batch'])) {
    $batch = $_GET['batch']; // No need for mysqli_real_escape_string when using PDO
    $semester_options = "<option value='' disabled selected>Select Semester</option>";

    // Prepare and execute the query using PDO
    $query = "SELECT DISTINCT semester FROM course WHERE batch = :batch ORDER BY semester ASC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':batch', $batch);
    $stmt->execute();

    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $semester_options .= "<option value='{$result['semester']}'>{$result['semester']}</option>";
    }

    echo $semester_options;
}
?>
