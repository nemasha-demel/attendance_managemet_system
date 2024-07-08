<?php
include("../DB_connection.php");
if (isset($_GET['deleteid'])) {
    $regno = $_GET['deleteid'];
    $sqlStudent = "DELETE FROM `student` WHERE reg_no = '$regno'";
    $resultStudent = mysqli_query($conn,$sqlStudent);
    $sqlUser = "DELETE FROM `user` WHERE user_id = '$regno'";
    $resultUser = mysqli_query($conn,$sqlUser);
    if($resultStudent && $resultUser)
    {
        header('location:Student-List.php');
    }else{
        die(mysqli_error($conn));
    }
}

?>
