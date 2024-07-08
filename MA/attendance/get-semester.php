<?php
include("../DB_connection.php");

if (isset($_GET['batch'])) {
    $batch = mysqli_real_escape_string($conn, $_GET['batch']);
    $query = "SELECT DISTINCT semester FROM course WHERE batch = '{$batch}'";
    $result_set = mysqli_query($conn, $query);

    if ($result_set) {
        $semester_list = "";
        while ($result = mysqli_fetch_assoc($result_set)) {
            $semester_list .= "<option value=\"{$result['semester']}\">{$result['semester']}</option>";
        }
        echo $semester_list;
    } else {
        echo "<option>Error retrieving semesters</option>";
    }
} else {
    echo "<option>Error: No batch selected</option>";
}
?>
