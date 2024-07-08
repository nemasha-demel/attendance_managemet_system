<?php
include("DB_connection.php");

session_start();
$lecturer_id = $_SESSION['id'];  // Get lecturer id from session

$query1 = "SELECT DISTINCT academic_year, batch FROM student ORDER BY academic_year ASC";
$stmt = $conn->prepare($query1);
$stmt->execute();

$acYear_list = "";
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $acYear_list .= "<option value=\"{$result['batch']}\">{$result['academic_year']}</option>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add space between elements */
        #course-list-title {
            margin-bottom: 20px; /* Adjust margin as needed */
        }

        #filters,
        #searchBtn {
            margin-top: 5px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Time Schedule</title>
</head>
<body>
    <?php include "../inc/navbar.php"; ?>

    <div class="container">
        <h5 id="course-list-title" style="text-align:center;">Time Schedule</h5>

        <div class="row justify-content-center mb-3"> <!-- Added margin bottom -->
            <div class="col-md-3"> <!-- Adjust col size as needed -->
                <select class="form-control form-control-sm" name="fetchval" id="academic_year">
                    <option value="" disabled="" selected="">Academic Year</option>
                    <?php echo $acYear_list; ?>
                </select>
            </div>
            <div class="col-md-3"> <!-- Adjust col size as needed -->
                <select class="form-control form-control-sm" name="fetchval" id="semester">
                    <option value="" disabled="" selected="">Semester</option>
                </select>
            </div>
            <div class="col-md-3"> <!-- Adjust col size as needed -->
                <select class="form-control" name="coursecode" id="coursecode">
                    <option value="" disabled selected>Course Code</option>
                </select>
            </div>
            <div class="col-md-2"> <!-- Adjust col size as needed -->
                <button id="searchBtn" class="btn btn-primary btn-sm">Search</button>
            </div>
        </div>

        <table class="table table1">
        <thead>
            <tr>
                <th scope="col">Course Code</th>
                <th scope="col">Chapter</th>
                <th scope="col">Date</th>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
            <tbody id="shedule-list">
                <?php
                $query = "SELECT s.course_code, s.chapter, s.date, s.start_time, s.end_time
                    FROM schedule s
                    JOIN course c ON s.course_code = c.course_code
                    WHERE c.lecturer_id = :lecturer_id";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':lecturer_id', $lecturer_id, PDO::PARAM_INT);
                $stmt->execute();
                $scheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($scheduleData as $row) {
                    $course_code = $row['course_code'];
                    $chapter = $row['chapter'];
                    $date = $row['date'];
                    $start_time = $row['start_time'];
                    $end_time = $row['end_time'];
                    echo 
                    '<tr>
                        <td>'.$course_code.'</td>
                        <td>'.$chapter.'</td>
                        <td>'.$date.'</td>
                        <td>'.$start_time.'</td>
                        <td>'.$end_time.'</td>
                        <td>
                            <a href="update.php?course_code='.$course_code.'&chapter='.$chapter.'" class="btn btn-success">Update</a>
                            <a href="delete.php?course_code='.$course_code.'&chapter='.$chapter.'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\')">Delete</a>

                        </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Add New Schedule Button -->
        <div class="row justify-content-center">
            <div class="col-md-2">
                <a href="addschedule.php" class="btn btn-success btn-sm add-schedule-btn">Add New Schedule</a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });
        
        $(document).ready(function() {
            $("#academic_year").on("change", function() {
                var batchId = $(this).val();
                
                // Fetch semesters based on academic year
                $.get("get-semester.php?batch=" + batchId, function(data, status) {
                    $("#semester").html(data);
                });
            });

            $("#semester").on("change", function() {
                var semester = $(this).val();

                if (semester) {
                    $.get("get-courses-by-semester.php?semester=" + semester, function(data, status) {
                        $("#coursecode").html(data);
                    });
                } else {
                    $("#coursecode").html("<option value='' disabled selected>Course Code</option>");
                }
            });

            $("#searchBtn").on("click", function() {
                var academic_year = $("#academic_year").val();
                var semester = $("#semester").val();
                var coursecode = $("#coursecode").val();

                if (academic_year && semester) {
                    $.get("get-schedule.php?batch=" + academic_year + "&semester=" + semester + "&coursecode=" + coursecode +"&lecturer_id=<?php echo $lecturer_id; ?>", function(data, status) {
                        $("#shedule-list").html(data);
                    });
                } else {
                    alert("Please select Academic Year and Semester and Course.");
                }
            });
        });
    </script>
</body>
</html>
