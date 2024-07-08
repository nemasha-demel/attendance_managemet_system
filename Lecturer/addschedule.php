<?php
include("DB_connection.php");

if (isset($_POST['submit'])) {
    $course_code = $_POST['course_code'];
    $chapter = $_POST['chapter'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    try {
        $sql = "INSERT INTO `schedule` (course_code, chapter, date, start_time, end_time)
                VALUES (:course_code, :chapter, :date, :start_time, :end_time)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course_code', $course_code);
        $stmt->bindParam(':chapter', $chapter);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);

        if ($stmt->execute()) {
            echo "Schedule Added Successfully";
            header('Location: timeschedule.php');
            exit();
        } else {
            echo "Error in adding schedule.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { // Duplicate entry error
            echo "Error: Schedule already exists for this course and chapter.";
        } else {
            echo "Error: " . $e->getMessage(); // Other PDO exceptions
        }
    }
}
?>

<!-- Your HTML form remains unchanged -->


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Student Attendance Management System</title>
  </head>
  <body>
    <div class="container my-5">
      <form method="post" action="">

        <div class="form-group">
          <label>Course Code</label>
          <input type="text" class="form-control" placeholder="Enter Course Code" name="course_code" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label>Chapter</label>
          <input type="text" class="form-control" placeholder="Enter Chapter" name="chapter" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label>Date</label>
          <input type="date" class="form-control" name="date" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label>Start Time</label>
          <input type="time" class="form-control" name="start_time" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label>End Time</label>
          <input type="time" class="form-control" name="end_time" autocomplete="off" required>
        </div>

        <button type="submit" class="btn btn-primary btn-custom my-5" name="submit">Save</button>
      </form>
    </div>
  </body>
</html>
