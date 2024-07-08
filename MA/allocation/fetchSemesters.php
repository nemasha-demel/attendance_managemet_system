<?php
include("../DB_connection.php");

if (isset($_POST['batch'])) {
    $batch = $_POST['batch'];

    $sql = "SELECT DISTINCT semester FROM course WHERE batch = '$batch' ORDER BY semester ASC";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        echo '<option value="" disabled selected>Semester</option>';
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<option value="'.$row['semester'].'">'.$row['semester'].'</option>';
        }
    } else {
        echo '<option value="" disabled>No Semesters Available</option>';
    }
}
?>
