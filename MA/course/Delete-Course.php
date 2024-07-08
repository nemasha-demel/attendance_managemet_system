<?php
include("../DB_connection.php");
if (isset($_GET['deleteid'])) {
    $Ccode = $_GET['deleteid'];
    $sql = "DELETE FROM `course` WHERE course_code = '$Ccode'";
    $result = mysqli_query($conn,$sql);
    if($result)
    {
        header('location:Course-List.php');
    }else{
        die(mysqli_error($conn));
    }
}

?>
