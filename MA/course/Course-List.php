

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
    <script type="text/javascript" src="fetchcoursedata.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="body-home">
    <?php include("../DB_connection.php");
     include "../inc/navbar.php"; ?>
    
    <div class="black-fill"><br></br><!--If you don't want to shade remove this division-->
        <div class="container">
            <div class="content">
                <h5 style="text-align:center;">Course List</h5>

                <div id="filters">
                <?php
                    $sql = "SELECT DISTINCT academic_year,batch FROM student ORDER BY academic_year ASC";
                    $res = mysqli_query($conn, $sql);
                    ?>
                    <select class="form-control" name="fetchval" id="academic_yearSelect" onchange="fetchSemesters()">
                        <option value="" disabled selected>Academic Year</option>
                        <?php while ($rows = mysqli_fetch_assoc($res)) { ?>    
                            <option value="<?php echo $rows['batch']; ?>"><?php echo $rows['academic_year']; ?></option>  
                        <?php } ?>
                    </select>

                </div>

                <div id="filters">
                    <select class="form-control" id="semesterSelect" onchange="fetchCourses()">
                        <option value="" disabled selected>Semester</option>
                        <?php
                        $sql = "SELECT DISTINCT semester FROM course WHERE batch = '{$batch}' ORDER BY semester ASC";
                        $res = mysqli_query($conn, $sql);
                        while ($rows = mysqli_fetch_assoc($res)) { 
                        ?>    
                            <option value="<?php echo $rows['semester']; ?>"><?php echo $rows['semester']; ?></option>  
                        <?php 
                        } 
                        ?>
                    </select>
                </div>

                
                <div id="filters">
               
                <select class="form-control" name="fetchval" id="specializationSelect">
                    <option value="" disabled selected>Specialization</option>
                    <option value="General">General</option>
                    <option value="Computer Engineering">Computer Engineering</option>
                    <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
                    <option value="Computer Engineering and EEE">Computer Engineering and Electrical & Electronic Engineering</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="Civil Engineering">Civil Engineering</option>
                </select>
                </div>

                

                <table class="table table1">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Course Code</th>
                            <th>Credits</th>
                            <th>Lecture Hours</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="courseTableBody">
                        <!-- Dynamic rows will be injected here -->
                    </tbody>
                </table>            
            </div>
            
            <div class="add">
                <button>
                    <a href="#" class="text-light" id="addCourseButton">+</a>
                </button>
            </div>
        </div>
        <footer class="text-center">
            <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        function fetchSemesters() {
            var academicYear = document.getElementById('academic_yearSelect').value;
            if (academicYear) {
                $.ajax({
                    url: 'fetchSemesters.php', // PHP script to fetch semesters
                    type: 'POST',
                    data: { batch: academicYear }, // Sending the selected batch value
                    success: function(response) {
                        // Assuming response is an HTML string of options
                        $('#semesterSelect').html(response);
                        $('#semesterSelect').prop('disabled', false); // Enable semester select
                    }
                });
            } else {
                $('#semesterSelect').html('<option value="" disabled selected>Semester</option>');
                $('#semesterSelect').prop('disabled', true);
            }
        }
        function fetchCourses() {
            var semester = document.getElementById('semesterSelect').value;
            var specialization = document.getElementById('specializationSelect').value;

            if (semester && specialization) {
                $.ajax({
                    url: 'showCourse.php',
                    type: 'POST',
                    data: {
                        semester: semester,
                        specialization: specialization
                    },
                    success: function(response) {
                        $('#courseTableBody').html(response);
                    }
                });
            }
        }

        document.getElementById('semesterSelect').addEventListener('change', fetchCourses);
        document.getElementById('specializationSelect').addEventListener('change', fetchCourses);

        document.getElementById('addCourseButton').addEventListener('click', function() {
            var batch = document.getElementById('academic_yearSelect').value;
            var semester = document.getElementById('semesterSelect').value;
            var specialization = document.getElementById('specializationSelect').value;
            if (semester && specialization && batch) {
                window.location.href = 'Add-Course.php?semester=' + semester + '&specialization=' + specialization + '&batch=' + batch;
            } else {
                alert('Please select a batch, semester, and specialization.');
            }
        });
    </script>
</body>
</html>
