<?php
// Connect to your database
include("../DB_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine which request is being made
    if (isset($_POST['fetch_data_for'])) {
        $fetchDataFor = $_POST['fetch_data_for'];

        if ($fetchDataFor == 'courses') {
            $semester = mysqli_real_escape_string($conn, $_POST['semester']);
            $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);

            // Fetch course codes where lecturer_id is 0 and batch is empty
            $query = "SELECT course_code, course_name FROM course 
                      WHERE semester='$semester' AND specialization='$specialization' 
                      AND (lecturer_id = 0 OR batch = '')";
            $result = mysqli_query($conn, $query);

            $courses = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $courses[] = $row;
            }
            echo json_encode($courses);
        } elseif ($fetchDataFor == 'lecturers') {
            $query = "SELECT lecturer_id, name FROM lecturer";
            $result = mysqli_query($conn, $query);

            $lecturers = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $lecturers[] = $row;
            }
            echo json_encode($lecturers);
        } elseif ($fetchDataFor == 'batches') {
            $query = "SELECT DISTINCT batch FROM student";
            $result = mysqli_query($conn, $query);

            $batches = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $batches[] = $row;
            }
            echo json_encode($batches);
        }
    } else {
        // Handle course update request
        $newcode = mysqli_real_escape_string($conn, $_POST['course_code']);
        $lecturerId = mysqli_real_escape_string($conn, $_POST['lecturer']);
        $batch = mysqli_real_escape_string($conn, $_POST['batch']);

        if (!empty($newcode) && !empty($lecturerId) && !empty($batch)) {
            // Fetch the lecturer's name based on lecturer ID
            $lecturerQuery = "SELECT name FROM lecturer WHERE lecturer_id='$lecturerId'";
            $lecturerResult = mysqli_query($conn, $lecturerQuery);

            if ($lecturerResult && mysqli_num_rows($lecturerResult) > 0) {
                $lecturerRow = mysqli_fetch_assoc($lecturerResult);
                $lecturerName = $lecturerRow['name'];

                // Update the course table with the lecturer ID, lecturer name, and batch
                $updateQuery = "UPDATE course SET lecturer_id='$lecturerId', lecturer='$lecturerName', batch='$batch' WHERE course_code='$newcode'";

                if (mysqli_query($conn, $updateQuery)) {
                    echo "Course updated successfully";
                } else {
                    echo "Error updating course: " . mysqli_error($conn);
                }
            } else {
                echo "Error: Lecturer not found";
            }
        } else {
            echo "Error: All fields are required.";
        }
    }

    mysqli_close($conn);
}
