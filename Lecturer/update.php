<?php
include("DB_connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['course_code']) && isset($_POST['chapter']) && isset($_POST['date']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
        $course_code = $_POST['course_code'];
        $old_chapter = $_POST['old_chapter']; // Added to retain the old chapter value for the update query
        $chapter = $_POST['chapter'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // Prepare update query
        $query = "UPDATE schedule 
                  SET chapter = :chapter,
                      date = :date,
                      start_time = :start_time,
                      end_time = :end_time
                  WHERE course_code = :course_code 
                  AND chapter = :old_chapter"; // Updated to use old chapter value for identification

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':chapter', $chapter);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':course_code', $course_code);
        $stmt->bindParam(':old_chapter', $old_chapter); // Binding the old chapter value

        if ($stmt->execute()) {
            echo '<script>alert("Schedule updated successfully!"); window.location = "timeschedule.php";</script>';
        } else {
            echo "Error updating schedule: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Please fill in all the required fields.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Update Schedule</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="course_code" name="course_code" value="<?php echo isset($_GET['course_code']) ? $_GET['course_code'] : ''; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="old_chapter" class="form-label">Old Chapter</label>
                <input type="text" class="form-control" id="old_chapter" name="old_chapter" value="<?php echo isset($_GET['chapter']) ? $_GET['chapter'] : ''; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="chapter" class="form-label">New Chapter</label>
                <input type="text" class="form-control" id="chapter" name="chapter" value="<?php echo isset($_POST['chapter']) ? $_POST['chapter'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo isset($_POST['end_time']) ? $_POST['end_time'] : ''; ?>" required>
            </div>
            <div class="center">
                <button type="submit" id="update-btn">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
