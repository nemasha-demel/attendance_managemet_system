<?php
include("../DB_connection.php");

if (isset($_GET['deleteid'])) {
    $Ccode = mysqli_real_escape_string($conn, $_GET['deleteid']);

    // Use UPDATE to reset the columns to their default empty values
    $sql = "UPDATE `course` SET lecturer_id = 0, lecturer = '', batch = '' WHERE course_code = '$Ccode'";
    $result = mysqli_query($conn, $sql);

    if($result)
    {
        header('location:Course-Allocation.php');
    }else{
        die(mysqli_error($conn));
    }
}
?>
