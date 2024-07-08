<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Semester 03 Attendance</title>
</head>
<body>
<div class="container">
    <h5 style="text-align:center;">Attendance Dates</h5>
    <?php
    // Assuming you have established a database connection
    include("../DB_connection.php");
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['id'])) {
        header("Location: ../login.php");
        exit();
    }

    // Query to get course list for a particular semester from the course table
    $semester = 'Semester 3'; // Change this to the desired semester
    $courseQuery = "SELECT DISTINCT c.course_code FROM course c JOIN attendance a ON c.course_code = a.course_code WHERE c.semester = ? AND a.reg_no = ?";
    $stmtCourse = $conn->prepare($courseQuery);
    $stmtCourse->bind_param("ss", $semester, $_SESSION['id']);

    if ($stmtCourse->execute()) {
        $courseResult = $stmtCourse->get_result();

        if ($courseResult->num_rows > 0) {
            // Store course codes in an array
            $courseCodes = [];
            while ($row = $courseResult->fetch_assoc()) {
                $courseCodes[] = $row['course_code'];
            }

            // Store distinct dates in an array
            $distinctDates = [];

            // Retrieve distinct dates for each course and store in the array
            foreach ($courseCodes as $courseCode) {
                $datesQuery = "SELECT DISTINCT date FROM attendance WHERE course_code = ? AND reg_no = ? ORDER BY date ASC";
                $stmtDates = $conn->prepare($datesQuery);
                $stmtDates->bind_param("ss", $courseCode, $_SESSION['id']);
                if ($stmtDates->execute()) {
                    $datesResult = $stmtDates->get_result();
                    if ($datesResult->num_rows > 0) {
                        while ($dateRow = $datesResult->fetch_assoc()) {
                            $distinctDates[$courseCode][] = $dateRow['date'];
                        }
                    }
                }
                $stmtDates->close();
            }

            // Get unique dates for all courses
            $uniqueDates = array_unique(array_merge(...array_values($distinctDates)));
            sort($uniqueDates); // Sort the dates in ascending order

            // Display the course list with unique dates as columns
            echo '<table class="table table-striped table-bordered">';
            echo '<thead><tr><th>Course Code</th>';
            foreach ($uniqueDates as $date) {
                echo '<th>' . $date . '</th>';
            }
            echo '<th>Total Days</th>'; // New column for Total Days
            echo '<th>Attendance Percentage</th>'; // New column for Attendance Percentage
            echo '</tr></thead><tbody>';

            // Display attendance data for each course
            foreach ($courseCodes as $courseCode) {
                // Check if the course code has already been displayed
                if (!in_array($courseCode, array_keys($distinctDates))) {
                    continue; // Skip this course code
                }

                echo '<tr><td>' . $courseCode . '</td>';
                $totalDays = 0;
                $presentDays = 0;
                foreach ($uniqueDates as $date) {
                    // Retrieve attendance status for each date and display
                    $statusQuery = "SELECT status FROM attendance WHERE course_code = ? AND reg_no = ? AND date = ?";
                    $stmtStatus = $conn->prepare($statusQuery);
                    $stmtStatus->bind_param("sss", $courseCode, $_SESSION['id'], $date);
                    if ($stmtStatus->execute()) {
                        $statusResult = $stmtStatus->get_result();
                        if ($statusResult->num_rows > 0) {
                            $statusRow = $statusResult->fetch_assoc();
                            $status = $statusRow['status'];
                            if ($status == 'Present' || $status == 'Absent') {
                                $totalDays++; // Increment total days for "Present" or "Absent"
                            }
                            if ($status == 'Present') {
                                $presentDays++; // Increment present days
                            }
                            echo '<td>' . ($status == 'Absent' ? 'Absent' : 'Present') . '</td>';
                        } else {
                            echo '<td>Null</td>'; // Display "Null" if no record exists for that day
                        }
                    } else {
                        echo '<td>Error</td>';
                    }
                    $stmtStatus->close();
                }
                // Calculate attendance percentage
                $attendancePercentage = ($totalDays > 0) ? (($presentDays / $totalDays) * 100) : 0;
                echo '<td>' . $presentDays . ' / ' . $totalDays . '</td>'; // Display presented days / total days
                echo '<td>' . $attendancePercentage . '%</td>';
                echo '</tr>';

                // Remove the course code from the array to prevent duplication
                unset($distinctDates[$courseCode]);
            }

            echo '</tbody></table>';
        } else {
            echo 'No courses found for ' . $semester;
        }
    } else {
        echo 'Error executing course query: ' . $stmtCourse->error;
    }

    // Close the database connection
    $stmtCourse->close();
    ?>
</div>
</body>
</html>
