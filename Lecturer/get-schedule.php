<?php
include("DB_connection.php");

if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['coursecode']) && isset($_GET['lecturer_id'])) {
    $batch = $_GET['batch'];
    $semester = $_GET['semester'];
    $coursecode = $_GET['coursecode'];
    $lecturer_id = $_GET['lecturer_id'];

    // Prepare and execute the query using PDO
    $query = "SELECT * FROM schedule 
              WHERE course_code = :coursecode ";

    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(':coursecode', $coursecode);

    $stmt->execute();

    $course_list = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $course_list .= "
            <tr>
                <td>{$row['course_code']}</td>
                <td>{$row['chapter']}</td>
                <td>{$row['date']}</td>
                <td>{$row['start_time']}</td>
                <td>{$row['end_time']}</td>
                <td>
                    <a href='update.php?course_code={$row['course_code']}&chapter={$row['chapter']}' class='btn btn-sm btn-success'>Update</a>
                    <a href='delete.php?course_code={$row['course_code']}&chapter={$row['chapter']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>

                </td>
            </tr>";
        }
    } else {
        $course_list = "<tr><td colspan='4'>No courses found</td></tr>";
    }

    echo $course_list;
} else {
    echo "<tr><td colspan='4'>Invalid academic year, semester, or lecturer ID</td></tr>";
}
?>
