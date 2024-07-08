<?php
include("../DB_connection.php");

// Get course code from the query parameter
$Ccode = $_GET['editid'];

// Check if the form is submitted
if (isset($_POST['update'])) {
    $course_code = $_POST['course_code'];
    $lecturer_name = $_POST['lecturer'];
    $batch = $_POST['batch'];

    // Fetch the lecturer_id based on the selected lecturer's name
    $lecturer_sql = "SELECT lecturer_id FROM lecturer WHERE name='$lecturer_name'";
    $lecturer_result = mysqli_query($conn, $lecturer_sql);
    if ($lecturer_result && mysqli_num_rows($lecturer_result) > 0) {
        $lecturer_data = mysqli_fetch_assoc($lecturer_result);
        $lecturer_id = $lecturer_data['lecturer_id'];

        // Update the course table with the new details
        $sql = "UPDATE `course` SET lecturer='$lecturer_name', batch='$batch', lecturer_id='$lecturer_id' WHERE course_code='$course_code'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "Data Updated Successfully";
            header('location:Course-Allocation.php');
        } else {
            die(mysqli_error($conn));
        }
    } else {
        die("Lecturer not found");
    }
}

// Fetch current course details
$course_sql = "SELECT lecturer, batch FROM course WHERE course_code='$Ccode'";
$course_result = mysqli_query($conn, $course_sql);
if ($course_result && mysqli_num_rows($course_result) > 0) {
    $course_data = mysqli_fetch_assoc($course_result);
    $current_lecturer = $course_data['lecturer'];
    $current_batch = $course_data['batch'];
} else {
    die("Course not found");
}

// Fetch all lecturers to populate the dropdown
$lecturers = [];
$lecturers_sql = "SELECT name FROM lecturer";
$lecturers_result = mysqli_query($conn, $lecturers_sql);
if ($lecturers_result) {
    while ($row = mysqli_fetch_assoc($lecturers_result)) {
        $lecturers[] = $row['name'];
    }
}

// Fetch all batches to populate the dropdown
$batches = [];
$batches_sql = "SELECT batch FROM batch";
$batches_result = mysqli_query($conn, $batches_sql);
if ($batches_result) {
    while ($row = mysqli_fetch_assoc($batches_result)) {
        $batches[] = $row['batch'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course Allocation - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png"> <!--Icon of title bar -->
</head>
<body class="body-home">

    <div class="black-fill"><br></br><!--If you don't want to shade remove this division-->
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <form class="add-lecturer" method="post">
                    <div class="text-center">
                        <h5>Edit Course Allocation Details</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course Code:</label>
                        <input type="text" class="form-control" name="course_code" value="<?php echo $Ccode; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lecturer:</label>
                        <select class="form-control" name="lecturer">
                            <!-- First option is the current lecturer -->
                            <option value="<?php echo $current_lecturer; ?>" selected><?php echo $current_lecturer; ?></option>
                            <?php foreach ($lecturers as $lecturer): ?>
                                <!-- Exclude the current lecturer from the rest of the list -->
                                <?php if ($lecturer !== $current_lecturer): ?>
                                    <option value="<?php echo $lecturer; ?>"><?php echo $lecturer; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batch:</label>
                        <select class="form-control" name="batch">
                            <!-- First option is the current batch -->
                            <option value="<?php echo $current_batch; ?>" selected><?php echo $current_batch; ?></option>
                            <?php foreach ($batches as $batch): ?>
                                <!-- Exclude the current batch from the rest of the list -->
                                <?php if ($batch !== $current_batch): ?>
                                    <option value="<?php echo $batch; ?>"><?php echo $batch; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="update">Edit</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='Course-Allocation.php'">Cancel</button>
                    <a href="Lecturer-List.php" class="text-decoration-none">Back</a>
                </form>
            </div>
        </div>

        <footer class="text-center">
            <p>&copy; 2024 Geethma & Nemasha. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
