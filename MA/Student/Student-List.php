<?php
// Function to get the ordinal suffix for a number
function ordinal($number) {
    $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
        return $number. 'th';
    } else {
        return $number. $ends[$number % 10];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="StyleStudent.css">
    <script type="text/javascript" src="fetchStudentdata.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="download.png"> 
     <!--Icon of title bar -->
     <script>
        function addBatchForm() {
    // Check if the form row already exists
    if (document.getElementById("formRow")) return;

    // Create a new table row
    var formRow = document.createElement("tr");
    formRow.id = "formRow";

    // Create table cell for batch input
    var batchCell = document.createElement("td");
    var batchInput = document.createElement("input");
    batchInput.type = "text";
    batchInput.name = "batch";
    batchInput.placeholder = "Enter new batch";
    batchInput.required = true;
    batchCell.appendChild(batchInput);

    // Create table cell for academic year input
    var yearCell = document.createElement("td");
    var yearInput = document.createElement("input");
    yearInput.type = "text";
    yearInput.name = "academic_year";
    yearInput.placeholder = "Enter academic year";
    yearInput.required = true;
    yearCell.appendChild(yearInput);

    // Create table cell for current level input
    var levelCell = document.createElement("td");
    var levelInput = document.createElement("input");
    levelInput.type = "text";
    levelInput.name = "current_level";
    levelInput.placeholder = "Enter current level";
    levelInput.required = true;
    levelCell.appendChild(levelInput);

    // Create table cell for save button
    var actionCell = document.createElement("td");
    var saveButton = document.createElement("button");
    saveButton.type = "button";
    saveButton.textContent = "Add";
    saveButton.onclick = saveBatch;
    actionCell.appendChild(saveButton);

    // Create a cancel button
    var cancelButton = document.createElement("button");
    cancelButton.type = "button";
    cancelButton.textContent = "Cancel";
    cancelButton.onclick = function() {
        formRow.remove(); // Remove the form row
    };
    actionCell.appendChild(cancelButton);

    // Append cells to the form row
    formRow.appendChild(batchCell);
    formRow.appendChild(yearCell);
    formRow.appendChild(levelCell);
    formRow.appendChild(actionCell);

    // Append the form row to the table body
    var tableBody = document.querySelector(".table1 tbody");
    tableBody.appendChild(formRow);
}


function saveBatch() {
    var batchInput = document.querySelector("#formRow input[name='batch']");
    var yearInput = document.querySelector("#formRow input[name='academic_year']");
    var levelInput = document.querySelector("#formRow input[name='current_level']");
    
    var newBatch = batchInput.value.trim();
    var academicYear = yearInput.value.trim();
    var currentLevel = levelInput.value.trim();

    if (newBatch && academicYear && currentLevel) {
        // Use AJAX to send data to PHP script without refreshing the page
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_batch.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Reload the page to reflect the new batch
                location.reload();
            }
        };
        xhr.send("batch=" + encodeURIComponent(newBatch) + 
                 "&academic_year=" + encodeURIComponent(academicYear) + 
                 "&current_level=" + encodeURIComponent(currentLevel));
    } else {
        alert("Please enter valid details for all fields.");
    }
}

    </script>
</head>
<body class="body-home">

    <?php
    include("../DB_connection.php");
    include "../inc/navbar.php";
    ?>
   
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="container">
    

        <div class="content">

        <h5 class="title" style="text-align:center;">Batch And Students</h5>
            <table class="table table1">
                <thead>
                    <tr>
                        <th>Batches</th>
                        <th>Academic Year</th>
                        <th>Current Level</th>
                        <th>No. of Students</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT DISTINCT * FROM batch ORDER BY academic_year DESC";
                $res = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($res)) {
                    $batch = $row['batch'];
                    $academic_year = $row['academic_year'];
                    $current_level = $row['current_level'];
                    // Query to count students in each batch
                    $countSql = "SELECT COUNT(*) AS count FROM student WHERE batch = '$batch'";
                    $countResult = mysqli_query($conn, $countSql);
                    $countRow = mysqli_fetch_assoc($countResult);
                    $studentCount = $countRow['count'];

                    $formattedBatch = ordinal($batch) . " batch";
                    echo '<tr>
                    <td>' . $formattedBatch . '</td>
                    <td>' . $academic_year . '</td>
                    <td>' . $current_level . '</td>
                    <td>' . $studentCount . '</td>
                    <td>
                        <button class="btn btn-primary"><a href="Edit-Batch.php?editid=' .$batch. '" class="text-light">Edit</a></button>
                    </td>
                    </tr>';
                }
                ?>


                </tbody>
            </table>
            <div class="add">
        <button onclick="addBatchForm()">+</button>
    </div>

            <div id="filters Batch" >
                    <?php
                    $sql = "SELECT DISTINCT batch,academic_year FROM student ORDER BY academic_year DESC";
                    $res = mysqli_query($conn, $sql);
                    ?>
                    <select class="form-control" name="fetchval" id="batchSelect" onchange="selectBatch()">
                        <option value="" disabled selected>Batch</option>
                        <?php while ($rows = mysqli_fetch_assoc($res)) { ?>    
                            <option value="<?php echo $rows['batch']; ?>"><?php echo $rows['batch']; ?></option>  
                            
                        <?php } ?>
                    </select>
                </div>
       
            <table class="table table2">
                
                <thead>
                    <tr>
                        <th>Name With Initials</th>
                        <th>Email</th>
                        <th>Registration No.</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Edit Or Delete</th>
                    </tr>
                </thead>
                <tbody id="courseTableBody">
                <?php
                    $sql= "SELECT batch,fname, mname, lname, email, reg_no, status ,specialization FROM `student`";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = "batch";
                            $regno = $row['reg_no'];
                            $fname = $row['fname'];
                            $mname = $row['mname'];
                            $lname = $row['lname'];
                            $status = $row['status'];
                            $email = $row['email'];
                            $specialization = $row['specialization'];

                            // Format name with initials
                            $fnameInitial = $fname ? substr($fname, 0, 1) . '.' : '';
                            $mnameInitial = $mname ? substr($mname, 0, 1) . '.' : '';
                            $fullNameWithInitials = $fnameInitial . ' ' . $mnameInitial . ' ' . $lname;

                            echo '<tr>
                            <td>' . $fullNameWithInitials . '</td>
                            <td>' . $email . '</td>
                            <td>' . $regno . '</td>
                            <td>' . $specialization . '</td>
                            <td>' . $status . '</td>
                            <td>
                            <button class="btn btn-primary"><a href="Edit-Student.php?editid=' .$regno. '" class="text-light">Edit</a></button>
                            <button class="btn btn-danger"><a href="Delete-Student.php?deleteid=' .$regno.'" class="text-light">Delete</a></button>
                            </td>
                            </tr>';
                        }
                    }
                ?>
            </tbody>
            </table>
            </div>
            <div class="add">
                <button>
                    <a href="#" class="text-light" id="addStudentButton">+</a>
                </button>
            </div>

            <footer class="text-center">
                <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
            </footer>
        </div>
        
    </div>    
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('addStudentButton').addEventListener('click', function() {
            var batch = document.getElementById('batchSelect').value;
            if (batch) {
                window.location.href = 'Add-Student.php?addid=' + batch;
            } else {
                alert('Please select a batch.');
            }
        });
    </script>
</body>
</html>
