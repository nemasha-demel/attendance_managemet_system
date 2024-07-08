<?php
include("../DB_connection.php");
if (isset($_GET['deleteid'])) {
    $email = $_GET['deleteid'];
    $sql = "DELETE FROM `lecturer` WHERE email = '$email'";
    $sqlUser = "DELETE FROM `user` WHERE email = '$email'";

    $result = mysqli_query($conn,$sql);
    $resultUser = mysqli_query($conn,$sqlUser);
    if($result && $resultUser)
    {
        header('location:Lecturer-List.php');
    }else{
        die(mysqli_error($conn));
    }
}

?>
