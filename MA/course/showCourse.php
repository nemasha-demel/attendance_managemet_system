<?php
include("../DB_connection.php");

$semester = isset($_POST['semester']) ? trim($_POST['semester']) : '';
$specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';

if ($semester && $specialization) {
    $whereClause = "WHERE semester = '$semester'";

    switch ($specialization) {
        case 'Computer Engineering':
            $whereClause .= " AND (specialization = 'Computer Engineering' OR specialization = 'Computer Engineering and EEE')";
            break;
        case 'Electrical and Electronic Engineering':
            $whereClause .= " AND (specialization = 'Electrical and Electronic Engineering' OR specialization = 'Computer Engineering and EEE')";
            break;
        case 'Civil Engineering':
            $whereClause .= " AND specialization = 'Civil Engineering'";
            break;

        case 'Mechanical Engineering':
            $whereClause .= " AND specialization = 'Mechanical Engineering'";
            break;
        case 'Computer Engineering and EEE':
            $whereClause .= " AND specialization = 'Computer Engineering and EEE'";
            break;
        case 'General':
            $whereClause .= " AND specialization = 'General'";
            break;
        default:
            $whereClause .= " AND specialization = '$specialization'";
            break;
    }

    $sql = "SELECT * FROM course $whereClause";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<tr>
                <td>' . $row['course_name'] . '</td>
                <td>' . $row['course_code'] . '</td>
                <td>' . $row['credits'] . '</td>
                <td>' . $row['lecture_hours'] . '</td>
                <td>
                    <button class="btn btn-primary">
                        <a href="Edit-Course.php?editid=' . $row['course_code'] . '" class="text-light">Edit</a>
                    </button>
                    <button class="btn btn-danger">
                        <a href="Delete-Course.php?deleteid=' . $row['course_code'] . '" class="text-light">Delete</a>
                    </button>
                </td>
            </tr>';
        }
    } else {
        echo '<tr><td colspan="5">No courses found for the selected criteria.</td></tr>';
    }
} else {
    echo '<tr><td colspan="5">Please select both semester and specialization.</td></tr>';
}
?>
+