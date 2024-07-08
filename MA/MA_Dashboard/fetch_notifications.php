<?php
include("../DB_connection.php");

// Fetch notifications from the messages table where checked is 'No'
$sql = "SELECT email, name, role, message, date_time, checked FROM messages WHERE checked = 'No' ORDER BY date_time DESC";
$result = mysqli_query($conn, $sql);

$notifications = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
}

mysqli_close($conn);

// Return notifications as JSON
header('Content-Type: application/json');
echo json_encode($notifications);
?>
