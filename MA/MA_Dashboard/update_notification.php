<?php
include("../DB_connection.php");

if (isset($_POST['email']) && isset($_POST['date_time'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $date_time = mysqli_real_escape_string($conn, $_POST['date_time']);

    // Update the checked status to 'Yes'
    $sql = "UPDATE messages SET checked='Yes' WHERE email='$email' AND date_time='$date_time'";

    if (mysqli_query($conn, $sql)) {
        echo "Notification marked as read.";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
