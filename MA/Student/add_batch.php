<?php
include("../DB_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new batch details from POST request
    $newBatch = mysqli_real_escape_string($conn, $_POST['batch']);
    $academicYear = mysqli_real_escape_string($conn, $_POST['academic_year']);
    $currentLevel = mysqli_real_escape_string($conn, $_POST['current_level']);

    // Insert the new batch into the database
    $sql = "INSERT INTO batch (batch, academic_year, current_level) VALUES ('$newBatch', '$academicYear', '$currentLevel')";

    if (mysqli_query($conn, $sql)) {
        // Return success message
        echo "New batch added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
