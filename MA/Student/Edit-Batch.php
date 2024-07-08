<?php
include("../DB_connection.php");
$batch = $_GET['editid'];


if ($batch) {
    $stmt = $conn->prepare("SELECT batch,academic_year,current_level FROM batch WHERE batch = ?");
    $stmt->bind_param("s", $batch);
    $stmt->execute();
    $result = $stmt->get_result();
    $batch = $result->fetch_assoc();
    $stmt->close();
}

if(isset($_POST['update']))
{
    $batch = $_POST['batch'];
    $academic_year =$_POST['academic_year'];
    $current_level =$_POST['current_level'];
    
 
    $stmt = $conn->prepare("UPDATE batch SET academic_year = ?, current_level = ? WHERE batch = ?");
    $stmt->bind_param("sss", $academic_year, $current_level, $batch);
    $result = $stmt->execute();

    if($result)
    {
        
        echo "Data Updated Successfully";
        header('location:Student-List.php');

    }

    else{
        die (mysqli_error($conn));
    }
    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Batch - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>
<body  class="body-home">

    <?php
    include("../DB_connection.php");
    ?>
    
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="container">
    
        <div class="d-flex justify-content-center align-items-center">
        <form class="add-lecturer" method = "post">

            <div class="text-center"> 
                <h5>Edit Batch</h5>
            </div>
   
            <div class="mb-3">
                        <label class="form-label">Batch:</label>
                        <input type="text" class="form-control" name="batch" value="<?php echo htmlspecialchars($batch['batch'] ?? ''); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Academic Year:</label>
                        <input type="text" class="form-control" name="academic_year" value="<?php echo htmlspecialchars($batch['academic_year'] ?? ''); ?>" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Level:</label>
                        <input type="text" class="form-control" name="current_level" value="<?php echo htmlspecialchars($batch['current_level'] ?? ''); ?>" autocomplete="off">
                    </div>

                  
            
            <button type="submit" class="btn btn-primary" name="update">Edit</button>
            
            <a href="Student-List.php" class="text-decoration-none">Back</a>
        </form>
        </div>

       
                    
     
        
        </div>
        <footer class="text-center">
            <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
    </footer>

    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>