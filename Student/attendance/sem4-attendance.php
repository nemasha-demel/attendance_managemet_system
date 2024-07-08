<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Semester 04 Attendance</title>
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

    // Query to get course list and dates from the attendance table
    $semester = 4; // Change this to the desired semester
    $reg_no = $_SESSION['id'];
    $courseQuery = "
        SELECT DISTINCT a.course_code, a.date, a.status
        FROM attendance a
        WHERE SUBSTRING(a.course_code, 3, 1) = ?
        AND a.reg_no = ?
        ORDER BY a.course_code, a.date ASC
    ";
    $stmtCourse = $conn->prepare($courseQuery);
    $stmtCourse->bind_param("is", $semester, $reg_no);

    if ($stmtCourse->execute()) {
        $courseResult = $stmtCourse->get_result();

        if ($courseResult->num_rows > 0) {
            // Arrays to store course codes and dates
            $attendanceData = [];
            $uniqueDates = [];

            // Process the result set
            while ($row = $courseResult->fetch_assoc()) {
                $courseCode = $row['course_code'];
                $date = $row['date'];
                $status = $row['status'];

                // Store attendance data
                $attendanceData[$courseCode][$date] = $status;

                // Collect unique dates
                if (!in_array($date, $uniqueDates)) {
                    $uniqueDates[] = $date;
                }
            }

            // Sort dates in ascending order
            sort($uniqueDates);

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
            foreach ($attendanceData as $courseCode => $dates) {
                echo '<tr><td>' . $courseCode . '</td>';
                $totalDays = 0;
                $presentDays = 0;
                foreach ($uniqueDates as $date) {
                    if (isset($dates[$date])) {
                        $status = $dates[$date];
                        echo '<td>' . ($status == 'Absent' ? 'Absent' : 'Present') . '</td>';
                        if ($status == 'Present' || $status == 'Absent') {
                            $totalDays++; // Increment total days for "Present" or "Absent"
                        }
                        if ($status == 'Present') {
                            $presentDays++; // Increment present days
                        }
                    } else {
                        echo '<td>Null</td>'; // Display "Null" if no record exists for that day
                    }
                }
                // Calculate attendance percentage
                $attendancePercentage = ($totalDays > 0) ? (($presentDays / $totalDays) * 100) : 0;
                echo '<td>' . $presentDays . ' / ' . $totalDays . '</td>'; // Display presented days / total days
                echo '<td>' . $attendancePercentage . '%</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'No courses found for Semester ' . $semester;
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
