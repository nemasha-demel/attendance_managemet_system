<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Lecturer') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";

$lecturer_id = $_SESSION['id'];
$course_code = $_GET['course_code'] ?? '';

// Fetch distinct dates for the selected course code
$dateSql = "SELECT DISTINCT date FROM attendance WHERE course_code = ? ORDER BY date ASC";
$dateStmt = $conn->prepare($dateSql);

if (!$dateStmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit;
}

$dateStmt->bind_param('s', $course_code);

if (!$dateStmt->execute()) {
    echo "Execute failed: (" . $dateStmt->errno . ") " . $dateStmt->error;
    exit;
}

$dateResult = $dateStmt->get_result();
$dates = $dateResult->fetch_all(MYSQLI_ASSOC);
$dateStmt->close();

// Fetch attendance for the selected course code
$sql = "SELECT s.fname, s.mname, s.lname, a.reg_no, a.date, a.status 
        FROM attendance a
        INNER JOIN course c ON a.course_code = c.course_code
        INNER JOIN student s ON a.reg_no = s.reg_no
        WHERE c.course_code = ? ORDER BY a.reg_no ASC, a.date ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit;
}

$stmt->bind_param('s', $course_code);

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    exit;
}

$result = $stmt->get_result();
$attendance_data = [];
$total_dates = count($dates);

while ($row = $result->fetch_assoc()) {
    $attendance_data[$row['reg_no']]['name'] = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];
    $attendance_data[$row['reg_no']]['dates'][$row['date']] = $row['status'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Attendance Report</title>
</head>
<body>
    <div class="container">
        <h1>Attendance Report for Course <?= htmlspecialchars($course_code) ?></h1>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Registration Number</th>
                    <th>Name</th>
                    <?php foreach ($dates as $date): ?>
                        <th><?= htmlspecialchars($date['date']) ?></th>
                    <?php endforeach; ?>
                    <th>Attendance Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_data as $reg_no => $data): ?>
                    <?php
                    $present_count = 0;
                    foreach ($dates as $date) {
                        if (isset($data['dates'][$date['date']]) && $data['dates'][$date['date']] === 'Present') {
                            $present_count++;
                        }
                    }
                    $attendance_percentage = ($total_dates > 0) ? ($present_count / $total_dates) * 100 : 0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($reg_no) ?></td>
                        <td><?= htmlspecialchars($data['name']) ?></td>
                        <?php foreach ($dates as $date): ?>
                            <td>
                                <?= htmlspecialchars($data['dates'][$date['date']] ?? 'Absent') ?>
                            </td>
                        <?php endforeach; ?>
                        <td><?= number_format($attendance_percentage, 2) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
