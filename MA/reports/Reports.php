<?php
include("../DB_connection.php");

$query1 = "SELECT DISTINCT academic_year, batch FROM student ORDER BY academic_year ASC";
$result_set = mysqli_query($conn, $query1);

$acYear_list = "";
while ($result = mysqli_fetch_assoc($result_set)) {
    $acYear_list .= "<option value=\"{$result['batch']}\">{$result['academic_year']}</option>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">
</head>
<body class="body-home">
<?php include "../inc/navbar.php"; ?>
    <div class="black-fill">
        <br><br>
        
        <div class="container">
            

            <div class="content">
                <h5 style="text-align:center;">Reports</h5>

                <div id="filters">
                    <select class="form-control" name="fetchval" id="academic_year">
                        <option value="" disabled="" selected="">Academic Year</option>
                        <?php echo $acYear_list; ?>
                    </select>
                </div>

                <div id="filters">
                    <select class="form-control" name="fetchval" id="semester">
                        <option value="" disabled="" selected="">Semester</option>
                    </select>
                </div>

                <div id="filters">
                    <select class="form-control" name="specialization" id="specialization">
                    <option value="" disabled selected>Specialization</option>
                                <option value="General">General</option>
                                <option value="Computer Engineering">Computer Engineering</option>
                                <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
                                <option value="Computer Engineering and EEE">Computer Engineering and Electrical & Electronic Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                                <option value="Civil Engineering">Civil Engineering</option>
                    </select>
                </div>

                <div id="filters">
                    <select class="form-control" name="coursecode" id="coursecode">
                        <option value="" disabled selected>Course Code</option>
                        <?php echo $course_code_list; ?>
                    </select>
                </div>
  
                
                <table class="table table1">
                    <thead id="schedule-headers">
                        <!-- Schedule headers will be populated here -->
                    </thead>
                    <tbody id="student-list">
                        <!-- Student list will be populated here -->
                    </tbody>
                </table>
            </div> 



            <footer class="text-center">
                <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>   
    <script>
    $(document).ready(function() {
        $("#academic_year").on("change", function() {
            var batchId = $(this).val(); 
            
            // Clear previous values
            $("#semester").html('<option value="" disabled="" selected="">Semester</option>');
            $("#course_name").html('<option value="" disabled="" selected="">Course Name</option>');
            $("#schedule-headers").html('');
            $("#student-list").html('');

            // Fetch semesters based on batch
            $.get("get-semester.php?batch=" + batchId, function(data, status) {
                $("#semester").html(data);
            });

            
        });

        $("#semester, #specialization").on("change", function() {
        var specializationId = $("#specialization").val();
        var semesterId = $("#semester").val();

        if (semesterId && specializationId) {
            $.get("get-coursecode-by-specialization.php?semester=" + semesterId + "&specialization=" + specializationId, function(data, status) {
                $("#coursecode").html(data);
            });
        } else {
            $("#coursecode").html('<option value="" disabled selected>Course Code</option>');
        }
    });

    $("#coursecode, #specialization").on("change", function() {
        var batchId = $("#academic_year").val(); 
        var specializationId = $("#specialization").val();
        var courseId = $("#coursecode").val();

            
            // Fetch students and schedule based on batch, course, and specialization
        $.get("get-students.php?batch=" + batchId + "&course_code=" + courseId + "&specialization=" + specializationId, function(data, status) {
            data = JSON.parse(data);

            var scheduleHeaders = '<tr><th>Registration No.</th><th>Student Name</th><th>Percentage</th>';
            data.schedule.forEach(function(schedule) {
                scheduleHeaders += '<th>' + schedule.date + '<br>' + schedule.start_time + ' - ' + schedule.end_time + '</th>';
            });
            scheduleHeaders += '</tr>';
            $("#schedule-headers").html(scheduleHeaders);

            var studentList = '';
            data.students.forEach(function(student) {
                studentList += '<tr><td>' + student.regno + '</td><td>' + student.fullName + '</td><td>' + student.percentage + '%</td>';
                student.attendanceStatus.forEach(function(status) {
                    studentList += '<td>' + status + '</td>';
                });
                studentList += '</tr>';
            });
            if (studentList === '') {
                studentList = '<tr><td colspan="3">No students found for the selected batch</td></tr>';
            }
            $("#student-list").html(studentList);
            });
        });
    });
    </script>
</body>
</html>
