<?php
include("../DB_connection.php");

$batch = $_POST['id'];
$batch = trim($batch);

$sql = "SELECT * FROM student WHERE batch = '$batch'";
$res = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($res)) {
    $regno = $row['reg_no'];
    $fname = $row['fname'];
    $mname = $row['mname'];
    $lname = $row['lname'];
    $status = $row['status'];
    $email = $row['email'];
    $specialization = $row['specialization'];
    // Format name with initials
    $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
    $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
    $fullNameWithInitials = $fnameInitial . ' ' . $mnameInitial . ' ' . $lname;
    echo '<tr>
    <td>' . $fullNameWithInitials . '</td>
    <td>' . $email . '</td>
    <td>' . $regno . '</td>
    <td>' . $specialization . '</td>
    <td>' . $status . '</td>
    <td>
    <button class="btn btn-primary"><a href="Edit-Student.php?editid=' .$regno. '" class="text-light">Edit</a></button>
    <button class="btn btn-danger"><a href="Delete-Student.php?deleteid=' .$regno. '" class="text-light">Delete</a></button>
    </td>
    </tr>';
}
?>
