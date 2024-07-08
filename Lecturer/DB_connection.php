<?php

$sName = "localhost";
$uName = "root"; // Fixed variable name typo
$pass = "";
$db_name = "attendance_management_system";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass); // Fixed syntax error and variable name
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fixed typo in setAttribute method name

} catch(PDOException $e) {
    echo "Connection failed: ".$e->getMessage();
    exit;
}
?>
