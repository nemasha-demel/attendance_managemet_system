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
            margin-top: 1px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Course List</title>
</head>
<body>
    <?php include "../inc/navbar.php"; ?>

    <div class="container">
        <h5 id="course-list-title" style="text-align:center;">Course List</h5>

        <div class="row justify-content-center mb-3"> <!-- Added margin bottom -->
            <div class="col-md-4 mb-3"> <!-- Adjust col size as needed -->
                <select class="form-control form-control-sm" name="fetchval" id="academic_year">
                    <option value="" disabled="" selected="">Academic Year</option>
                    <?php echo $acYear_list; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3"> <!-- Adjust col size as needed -->
                <select class="form-control form-control-sm" name="fetchval" id="semester">
                    <option value="" disabled="" selected="">Semester</option>
                </select>
            </div>
            <div class="col-md-2 mb-3"> <!-- Adjust col size as needed -->
                <button id="searchBtn" class="btn btn-primary btn-sm">Search</button>
            </div>
        </div>

        <table class="table table1">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Credits</th>
                    <th>Lecture Hours</th>
                </tr>
            </thead>
            <tbody id="course-list">
                <!-- Course list will be populated here -->
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(2) a").addClass('active');
        });

        $(document).ready(function() {
            $("#academic_year").on("change", function() {
                var batchId = $(this).val();
                
                // Fetch semesters based on academic year
                $.get("get-semester.php?batch=" + batchId, function(data, status) {
                    $("#semester").html(data);
                });
            });

            $("#searchBtn").on("click", function() {
                var academic_year = $("#academic_year").val();
                var semester = $("#semester").val();

                if (academic_year && semester) {
                    $.get("get-courses.php?batch=" + academic_year + "&semester=" + semester + "&lecturer_id=<?php echo $lecturer_id; ?>", function(data, status) {
                        $("#course-list").html(data);
                    });
                } else {
                    alert("Please select both Academic Year and Semester.");
                }
            });
        });
    </script>
</body>
</html>
