<?php
include("../DB_connection.php");


    $course_code = isset($_GET['coursecode']) ? $_GET['coursecode'] : '';
    $semester = isset($_GET['semester']) ? $_GET['semester'] : '';
    $batch = isset($_GET['batch']) ? $_GET['batch'] : '';
    $date = isset($_GET['date']) ? $_GET['date'] : '';
    $time = isset($_GET['time']) ? $_GET['time'] : '';
    $lecturer = isset($_GET['lecturer']) ? $_GET['lecturer'] : '';
    $specialization = isset($_GET['specialization']) ? urldecode($_GET['specialization']) : '';  // Decode URL-encoded specialization

    // Fetch batch options
    $query1 = "SELECT DISTINCT batch FROM student ORDER BY batch ASC";
    $result_set = mysqli_query($conn, $query1);

    $batch_list = "";
    while ($result = mysqli_fetch_assoc($result_set)) {
        $selected = $result['batch'] == $batch ? 'selected' : '';
        $batch_list .= "<option value=\"{$result['batch']}\" $selected>{$result['batch']}</option>";
    }

    // Fetch semester options
    $semester_list = "";
    $query2 = "SELECT DISTINCT semester FROM course WHERE batch = '{$batch}'";
    $result_set2 = mysqli_query($conn, $query2);
    while ($result = mysqli_fetch_assoc($result_set2)) {
        $selected = $result['semester'] == $semester ? 'selected' : '';
        $semester_list .= "<option value=\"{$result['semester']}\" $selected>{$result['semester']}</option>";
    }

    // Fetch course codes
    $course_code_list = "";
    $query3 = "SELECT course_code FROM course WHERE batch = '{$batch}' AND semester = '{$semester}'";
    $result_set3 = mysqli_query($conn, $query3);
    while ($result = mysqli_fetch_assoc($result_set3)) {
        $selected = $result['course_code'] == $course_code ? 'selected' : '';
        $course_code_list .= "<option value=\"{$result['course_code']}\" $selected>{$result['course_code']}</option>";
    }

    // Fetch specializations
    $specialization_list = "";
    $query4 = "SELECT DISTINCT specialization FROM course ORDER BY specialization ASC";
    $result_set4 = mysqli_query($conn, $query4);
    while ($result = mysqli_fetch_assoc($result_set4)) {
        $selected = $result['specialization'] == $specialization ? 'selected' : '';
        $specialization_list .= "<option value=\"{$result['specialization']}\" $selected>{$result['specialization']}</option>";
    }

    // Fetch lecturers
    $lecturer_list = "";
    $query5 = "SELECT lecturer FROM course WHERE course_code = '{$course_code}' ";
    $result_set5 = mysqli_query($conn, $query5);
    while ($result = mysqli_fetch_assoc($result_set5)) {
        $selected = $result['lecturer'] == $lecturer ? 'selected' : '';
        $lecturer_list .= "<option value=\"{$result['lecturer']}\" $selected>{$result['lecturer']}</option>";
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../download.png">
</head>
<body class="body-home">
<?php include "../inc/navbar.php"; ?>
    <div class="black-fill">
        <div class="container">
            <div class="content">
               
                <h5 style="text-align:center;">Take Attendance</h5>
                
                <form id="attendance-form">

                <?php if (!empty($specialization)): ?>
                    <div class="mb-3">
                        <label class="form-label">Select specialization as:</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($specialization); ?></p> <!-- Display specialization as text -->
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($course_code)): ?>
                    <div class="mb-3">
                        <label class="form-label">Select Course Code:</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($course_code); ?></p> <!-- Display course code as text -->
                    </div>
                    <?php endif; ?> 

                <div id="filters">
        <select class="form-control" name="batch" id="batch">
            <option value="" disabled selected>Batch</option>
            <?php echo $batch_list; ?>
        </select>
    </div>

    <div id="filters">
        <select class="form-control" name="semester" id="semester">
            
            <option value="" disabled selected>Select Semester</option>
            <?php echo $semester_list; ?>
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
            
        </select>
    </div>
                    <div class="selection">
                        <div id="filters" data-provide="datepicker" style="margin-top: 50px; margin-right:50px;">
                            <label for="select" class="control-label">Date: </label>
                            <input type="date" class="form-control" name="date" value="<?php echo $date; ?>" required>
                        </div>
                        <div id="filters" style="margin-top: 50px; margin-right:50px;">
                            <label for="select" class="control-label">Time:</label>
                            <input type="time" class="form-control" name="time" value="<?php echo $time; ?>" required>
                        </div>


                        <div id="filters" style="margin-top: 50px;">
                        <label for="select" class="control-label" style="display: block;">Lecturer:</label>
                        <select class="form-control" name="lec_name" id="lecturer" style="margin-top: 0;">
                            <option value="" disabled selected>Select Lecturer</option>
                            <?php echo $lecturer_list; ?>
                        </select>
                        
                    </div>
                    
                    <table class="table table1">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Registration No.</th>
                                <th>All <input type="checkbox" id="all-present"> Present</th>
                            </tr>
                        </thead>
                        <tbody id="student-list">
                            <!-- Student list will be populated here -->
                        </tbody>
                    </table>
                    <div class="save">
                        <button type="submit" class="btn btn">Save</button>
                    </div>
                </form>
            </div>
            <footer class="text-center">
                <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>   
        <script>
            $(document).ready(function(){
    function resetForm() {
        $('#attendance-form')[0].reset();
        $("#semester").html('<option value="" disabled selected>Semester</option>');
        $("#coursecode").html('<option value="" disabled selected>Course Code</option>');
        $("#student-list").html('');
    }

    $("#batch").on("change", function() {
        var batchID = $(this).val(); // Correct the variable name to batchID

        if (batchID) {
            $.get("get-semester.php?batch=" + batchID, function(data, status) {
                $("#semester").html(data);
            });
        } else {
            $("#semester").html('<option value="" disabled selected>Select Semester</option>');
        }
    });
    $("#batch, #specialization").on("change", function() {
        var batchId = $("#batch").val();
        var specializationId = $("#specialization").val();

       
        if (batchId && specializationId) {
           
            // Fetch students based on batch and specialization
            $.get("get-students.php?batch=" + batchId + "&specialization=" + specializationId, function(data, status) {
                $("#student-list").html(data);
            });
        } else {
            $("#semester").html('<option value="" disabled selected>Semester</option>');
            $("#student-list").html('');
        }
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

    

    $("#coursecode").on("change", function() {
        var courseCode = $(this).val();

        if (courseCode) {
            $.get("get-lecturer.php?coursecode=" + courseCode, function(data, status) {
                $("#lecturer").html(data);
            });
        } else {
            $("#lecturer").html('<option value="" disabled selected>Select Lecturer</option>');
        }
    });


    $("#all-present").on("change", function() {
        $("input[type='checkbox']").prop('checked', $(this).prop('checked'));
    });

    $("#attendance-form").on("submit", function(e){
        e.preventDefault();

        $.ajax({
            url: 'save-attendance.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                alert(response);
                resetForm();
            },
            error: function(xhr, status, error) {
                alert("An error occurred: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
});

        </script>
    </body>
</html>
