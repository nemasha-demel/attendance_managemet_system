<?php
include("../DB_connection.php");

if (isset($_GET['batch'])) {
    $batch = mysqli_real_escape_string($conn, $_GET['batch']);
    $query = "SELECT DISTINCT specialization FROM course WHERE batch = '{$batch}' ORDER BY specialization ASC";

    $result_set = mysqli_query($conn, $query);

    $specialization_list = "";
    while ($result = mysqli_fetch_assoc($result_set)) {
        $specialization_list .= "<option value=\"{$result['specialization']}\">{$result['specialization']}</option>";
    }
    echo $specialization_list;
} else {
    echo "<option>Error fetching specializations</option>";
}
?>
