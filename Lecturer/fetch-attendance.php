<?php
include("../DB_connection.php");
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';

if (empty($course_code)) {
    echo json_encode(['error' => 'Invalid course code.']);
    exit;
}

// Fetch all attendance data for the given course code
$query = "SELECT student.reg_no, student.fname, student.mname, student.lname, attendance.date, attendance.status 
          FROM attendance
          JOIN student ON attendance.reg_no = student.reg_no
          WHERE attendance.course_code = ?
          ORDER BY attendance.date";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => "Prepare failed: (" . $conn->errno . ") " . $conn->error]);
    exit;
}

$stmt->bind_param('s', $course_code);

if (!$stmt->execute()) {
    echo json_encode(['error' => "Execute failed: (" . $stmt->errno . ") " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$attendanceData = [];
$dates = [];

while ($row = $result->fetch_assoc()) {
    $reg_no = $row['reg_no'];
    $date = $row['date'];
    $name = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];

    // Collect dates for column headers
    if (!in_array($date, $dates)) {
        $dates[] = $date;
    }

    // Organize data by student
    if (!isset($attendanceData[$reg_no])) {
        $attendanceData[$reg_no] = [
            'reg_no' => $reg_no,
            'name' => $name,
            'attendance' => []
        ];
    }
    $attendanceData[$reg_no]['attendance'][$date] = $row['status'];
}

// Convert dates array to a set of columns
sort($dates);

// Prepare data for frontend
$data = [
    'dates' => $dates,
    'attendanceData' => $attendanceData
];

header('Content-Type: application/json');
echo json_encode($data);

// Free the result and close the statement
$result->free();
$stmt->close();
?>
