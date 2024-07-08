<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>View Student List</title>
</head>
<body>

<div class="container">
    <h5 id="course-list-title" style="text-align:center;">Student List</h5>
    <table class='table'>
        <thead>
            <tr>
                <th scope='col'>Registration Number</th>
                <th scope='col'>Full Name</th>
            </tr>
        </thead>
        <tbody>                       

            <?php
            include("DB_connection.php");

            if (isset($_GET['course_code']) && isset($_GET['batch'])) {
                $course_code = $_GET['course_code'];
                $batch = $_GET['batch'];

                // Retrieve the specializations for the selected course
                $query = "SELECT specialization FROM course WHERE course_code = :course_code";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':course_code', $course_code);
                $stmt->execute();
                $specializations = $stmt->fetch(PDO::FETCH_ASSOC)['specialization'];

                // Split the specializations into an array
                $specialization_array = array_map('trim', explode("and", $specializations));

                // Replace "EEE" with "Electrical and Electronic Engineering"
                foreach ($specialization_array as &$specialization) {
                    if ($specialization == "EEE") {
                        $specialization = "Electrical and Electronic Engineering";
                    }
                }

                // Prepare the query using PDO with positional parameters
                $placeholders = implode(",", array_fill(0, count($specialization_array), "?"));
                $query = "SELECT reg_no, fname, mname, lname FROM student 
                          WHERE batch = ? AND (specialization IN (" . $placeholders . "))";
                $stmt = $conn->prepare($query);
                
                // Bind the batch and specializations to the query parameters
                $params = array_merge([$batch], $specialization_array);
                foreach ($params as $index => $param) {
                    $stmt->bindValue($index + 1, $param);
                }
                
                $stmt->execute();

                $student_table = "";

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $full_name = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; // Concatenate first name, middle name, and last name
                        $student_table .= "
                        <tr>
                            <td>{$row['reg_no']}</td>
                            <td>{$full_name}</td> <!-- Display full name -->
                        </tr>";
                    }
                } else {
                    $student_table .= "<tr><td colspan='2'>No students found</td></tr>";
                }

                echo $student_table;
            } else {
                echo "<p>Invalid course or academic year</p>";
            }

            ?>
        </tbody>
    </table>

</div>
</body>
</html>
