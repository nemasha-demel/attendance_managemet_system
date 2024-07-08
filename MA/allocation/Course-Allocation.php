<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Allocation - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
    <script>
    function addCourseForm() {
        // Check if the form row already exists
        if (document.getElementById("formRow")) return;

        // Create a new table row
        var formRow = document.createElement("tr");
        formRow.id = "formRow";

        // Create table cell for course code dropdown
        var coursecodeCell = document.createElement("td");
        var coursecodeSelect = document.createElement("select");
        coursecodeSelect.name = "course_code";
        coursecodeSelect.required = true;
        coursecodeCell.appendChild(coursecodeSelect);

        // Create table cell for lecturer dropdown
        var lecturerCell = document.createElement("td");
        var lecturerSelect = document.createElement("select");
        lecturerSelect.name = "lecturer";
        lecturerSelect.required = true;
        lecturerCell.appendChild(lecturerSelect);

        // Create table cell for batch dropdown
        var batchCell = document.createElement("td");
        var batchSelect = document.createElement("select");
        batchSelect.name = "batch";
        batchSelect.required = true;
        batchCell.appendChild(batchSelect);

        // Create table cell for save button
        var saveCell = document.createElement("td");
        var saveButton = document.createElement("button");
        saveButton.type = "button"; // Use button type to prevent form submission
        saveButton.textContent = "Update";
        saveButton.onclick = saveAllocation; // Attach save function to button
        saveCell.appendChild(saveButton);

        // Append cells to the form row
        formRow.appendChild(coursecodeCell);
        formRow.appendChild(lecturerCell);
        formRow.appendChild(batchCell);
        formRow.appendChild(saveCell);

        // Append the form row to the table body
        var tableBody = document.querySelector(".table1 tbody");
        tableBody.appendChild(formRow);

        // Fetch and populate dropdowns
        populateDropdowns(coursecodeSelect, lecturerSelect, batchSelect);
    }

    function populateDropdowns(coursecodeSelect, lecturerSelect, batchSelect) {
        var semester = document.getElementById('semesterSelect').value;
        var specialization = document.getElementById('specializationSelect').value;

        // Fetch course codes based on selected semester and specialization
        $.ajax({
            url: 'add_course.php',
            type: 'POST',
            data: {
                fetch_data_for: 'courses',
                semester: semester,
                specialization: specialization
            },
            success: function(response) {
                var courses = JSON.parse(response);
                coursecodeSelect.innerHTML = '<option value="" disabled selected>Select Course Code</option>';
                courses.forEach(function(course) {
                    var option = document.createElement("option");
                    option.value = course.course_code;
                    option.textContent = course.course_code + " - " + course.course_name;
                    coursecodeSelect.appendChild(option);
                });
            }
        });

        // Fetch lecturer names
        $.ajax({
            url: 'add_course.php',
            type: 'POST',
            data: { fetch_data_for: 'lecturers' },
            success: function(response) {
                var lecturers = JSON.parse(response);
                lecturerSelect.innerHTML = '<option value="" disabled selected>Select Lecturer</option>';
                lecturers.forEach(function(lecturer) {
                    var option = document.createElement("option");
                    option.value = lecturer.lecturer_id;
                    option.textContent = lecturer.name;
                    lecturerSelect.appendChild(option);
                });
            }
        });

        // Fetch batches
        $.ajax({
            url: 'add_course.php',
            type: 'POST',
            data: { fetch_data_for: 'batches' },
            success: function(response) {
                var batches = JSON.parse(response);
                batchSelect.innerHTML = '<option value="" disabled selected>Select Batch</option>';
                batches.forEach(function(batch) {
                    var option = document.createElement("option");
                    option.value = batch.batch;
                    option.textContent = batch.batch;
                    batchSelect.appendChild(option);
                });
            }
        });
    }

    function saveAllocation() {
        var coursecodeInput = document.querySelector("#formRow select[name='course_code']");
        var newcode = coursecodeInput.value.trim();

        var lecturerInput = document.querySelector("#formRow select[name='lecturer']");
        var lecturerId = lecturerInput.value.trim();

        var batchInput = document.querySelector("#formRow select[name='batch']");
        var batchName = batchInput.value.trim();

        if (newcode && lecturerId && batchName) {
            // Use AJAX to send data to PHP script without refreshing the page
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_course.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Debugging: Print server response
                console.log("Server Response: " + xhr.responseText);

                // Check if the response contains a success message
                if (xhr.responseText.includes("successfully")) {
                    // Update the UI without reloading
                    var tableBody = document.querySelector(".table1 tbody");

                    // Create a new row with the updated information
                    var newRow = document.createElement("tr");

                    var newCourseCell = document.createElement("td");
                    newCourseCell.textContent = coursecodeInput.options[coursecodeInput.selectedIndex].textContent;
                    newRow.appendChild(newCourseCell);

                    var newLecturerCell = document.createElement("td");
                    newLecturerCell.textContent = lecturerInput.options[lecturerInput.selectedIndex].textContent;
                    newRow.appendChild(newLecturerCell);

                    var newBatchCell = document.createElement("td");
                    newBatchCell.textContent = batchName;
                    newRow.appendChild(newBatchCell);

                    var actionCell = document.createElement("td");
                    actionCell.textContent = "Updated"; // Or you can add more buttons or links for further actions
                    newRow.appendChild(actionCell);

                    tableBody.appendChild(newRow);

                    // Optionally remove the form row
                    document.getElementById("formRow").remove();
                } else {
                    alert("Error updating course: " + xhr.responseText);
                }
            }
        };
        xhr.send("course_code=" + encodeURIComponent(newcode) + "&lecturer=" + encodeURIComponent(lecturerId) + "&batch=" + encodeURIComponent(batchName));
    } else {
        alert("Please enter valid details.");
    }
    }
</script>

</head>
<body class="body-home">
    <?php
   include("../DB_connection.php");
    include "../inc/navbar.php";
    ?>
    
    <div class="black-fill">
        <br><br><!--If you don't want to shade remove this division-->
        
        <div class="container">
            <div class="content">
                <h5 style="text-align:center;">Course Allocation</h5>
                <div id="filters">
                <?php
                    $sql = "SELECT DISTINCT academic_year,batch FROM student ORDER BY academic_year ASC";
                    $res = mysqli_query($conn, $sql);
                    ?>
                    <select class="form-control" name="fetchval" id="academic_yearSelect" onchange="fetchCourses()">
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
                        $sql = "SELECT DISTINCT semester FROM course ORDER BY semester ASC";
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
                    <select class="form-control" id="specializationSelect" onchange="fetchCourses()">
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
                            <th>Course Code</th>
                            <th>Lecturer</th>
                            <th>Batch</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="courseTableBody">
                        <!-- Details will be populated here -->
                    </tbody>
                </table>
            </div> 
            
            <div class="add">
                <button onclick="addCourseForm()">+</button>
            </div>

        </div>
        
        <footer class="text-center">
            <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>   
    <script>
        
        function fetchCourses() {
    var academicYear = document.getElementById('academic_yearSelect').value;
    var semester = document.getElementById('semesterSelect').value;
    var specialization = document.getElementById('specializationSelect').value;

    if (academicYear && semester && specialization) {
        $.ajax({
            url: 'course-list.php',
            type: 'POST',
            data: {
                academic_year: academicYear,
                semester: semester,
                specialization: specialization
            },
            success: function(response) {
                $('#courseTableBody').html(response);
            }
        });
    }
}

    </script>
</body>
</html>
