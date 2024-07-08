<?php
include("../DB_connection.php");

if (isset($_GET['batch']) && isset($_GET['course_code'])&& isset($_GET['specialization'])) {
    $batch = mysqli_real_escape_string($conn, $_GET['batch']);
    $course_code = mysqli_real_escape_string($conn, $_GET['course_code']);
    $specialization = mysqli_real_escape_string($conn, $_GET['specialization']);

    // Fetch schedule details
    $scheduleQuery = "SELECT date, start_time, end_time 
    FROM schedule 
    WHERE course_code = '$course_code' 
    AND date <= CURRENT_DATE 
    ORDER BY date";
    
    $scheduleResult = mysqli_query($conn, $scheduleQuery);
    $scheduleData = [];
    while ($row = mysqli_fetch_assoc($scheduleResult)) {
        $scheduleData[] = $row;
    }

    if ($specialization == "Computer Engineering and EEE") {
        $sql = "SELECT reg_no, fname, mname, lname FROM student 
                WHERE batch = '$batch' AND (specialization = 'Computer Engineering' OR specialization = 'Electrical and Electronic Engineering')";
    } else {
        $sql = "SELECT reg_no, fname, mname, lname FROM student WHERE batch = '$batch' AND specialization = '$specialization'";
    }

    // Fetch all students from the batch and specialization

    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $response = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $regno = $row['reg_no'];
            $fname = $row['fname'];
            $mname = $row['mname'];
            $lname = $row['lname'];

            // Format name with initials
            $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
            $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
            $fullNameWithInitials = $fnameInitial . ' ' . $mnameInitial . ' ' . $lname;

            // Fetch attendance status for each scheduled date
            $attendanceStatus = [];
            foreach ($scheduleData as $schedule) {
                $date = $schedule['date'];
                $attendanceQuery = "SELECT status FROM attendance WHERE reg_no = '$regno' AND course_code = '$course_code' AND date = '$date'";
                $attendanceResult = mysqli_query($conn, $attendanceQuery);
                if ($attendanceResult && mysqli_num_rows($attendanceResult) > 0) {
                    $attendanceRow = mysqli_fetch_assoc($attendanceResult);
                    $attendanceStatus[] = $attendanceRow['status'];
                } else {
                    $attendanceStatus[] = 'Pending';
                }
            }

            // Calculate the total days for the specific course
            $totalDaysQuery = "SELECT COUNT(DISTINCT s.date) as total_days
FROM schedule s
JOIN attendance a ON s.course_code = a.course_code AND s.date = a.date
WHERE s.course_code = '$course_code'";
            $totalDaysResult = mysqli_query($conn, $totalDaysQuery);
            $totalDaysRow = mysqli_fetch_assoc($totalDaysResult);
            $totalDays = $totalDaysRow['total_days'];

            // Calculate the attended days for the student for the specific course
            $attendedDaysQuery = "SELECT COUNT(*) as attended_days FROM attendance WHERE reg_no = '$regno' AND course_code = '$course_code' AND status = 'Present'";
            $attendedDaysResult = mysqli_query($conn, $attendedDaysQuery);
            $attendedDaysRow = mysqli_fetch_assoc($attendedDaysResult);
            $attendedDays = $attendedDaysRow['attended_days'];

            // Calculate the attendance percentage
            $percentage = ($totalDays > 0) ? ($attendedDays / $totalDays) * 100 : 0;
            $percentage = number_format($percentage, 2);

            $response[] = [
                'regno' => $regno,
                'fullName' => $fullNameWithInitials,
                'percentage' => $percentage,
                'attendanceStatus' => $attendanceStatus
            ];
        }

        echo json_encode(['schedule' => $scheduleData, 'students' => $response]);
    } else {
        echo json_encode(['schedule' => $scheduleData, 'students' => []]);
    }
} else {
    echo json_encode(['error' => 'Invalid batch or course code']);
}
?>
