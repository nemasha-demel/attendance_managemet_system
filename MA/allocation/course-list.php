<?php
include("../DB_connection.php");

// Function to add ordinal suffix to a number
function addOrdinalSuffix($number) {
    // Ensure the input is a number
    if (!is_numeric($number) || intval($number) != $number) {
        return $number; // Return as is if it's not a valid number
    }

    $number = intval($number);
    $suffix = 'th batch';
    if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            case 1: $suffix = 'st'; break;
            case 2: $suffix = 'nd'; break;
            case 3: $suffix = 'rd'; break;
        }
    }
    return $number . $suffix;
}
$batch = isset($_POST['academic_year']) ? trim($_POST['academic_year']) : '';
$semester = isset($_POST['semester']) ? trim($_POST['semester']) : '';
$specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : '';

if ($batch &&$semester && $specialization) {
    // Prepare the where clause based on the provided inputs
    $whereClause = "WHERE batch = '" . mysqli_real_escape_string($conn, $batch) . "'";
    $whereClause .= " AND semester = '" . mysqli_real_escape_string($conn, $semester) . "'";
    
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
            $whereClause .= " AND specialization = '" . mysqli_real_escape_string($conn, $specialization) . "'";
            break;
    }
    // Add condition to ensure lecturer_id is not 0
   
        $whereClause .= " AND lecturer_id != 0 AND batch IS NOT NULL";
    

    $sql = "SELECT course_code, lecturer, batch FROM course $whereClause ";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $Ccode = htmlspecialchars($row['course_code']);
            $lecturer = htmlspecialchars($row['lecturer']);
            $batchNumber = is_numeric($row['batch']) ? intval($row['batch']) : null; // Ensure batchNumber is an integer or null
            $batch = $batchNumber !== null ? addOrdinalSuffix($batchNumber) : 'N/A';

            echo '<tr>
                <td>' . $Ccode . '</td>
                <td>' . $lecturer . '</td>
                <td>' . $batch . '</td>
                <td>
                    <button class="btn btn-primary"><a href="Edit-Allocation.php?editid=' . $Ccode . '" class="text-light">Edit</a></button>
                    <button class="btn btn-danger"><a href="Delete-Allocation.php?deleteid=' . $Ccode . '" class="text-light">Delete</a></button>
                </td>
            </tr>';
        }
    } else {
        echo '<tr><td colspan="4">No courses found for the selected criteria.</td></tr>';
    }
} else {
    echo '<tr><td colspan="4">Please select both semester and specialization.</td></tr>';
}
?>
