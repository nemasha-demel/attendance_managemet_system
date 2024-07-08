<?php
include("../DB_connection.php");
$Ccode = $_GET['editid'];
if(isset($_POST['update']))
{
    $Ccode = $_POST['course_code'];
    $cname =$_POST['course_name'];
    $credits =$_POST['credits'];
    $lhours =$_POST['lecture_hours'];
   

    $sql = "UPDATE `course` SET course_name='$cname',lecture_hours='$lhours', 
  credits='$credits' WHERE course_code='$Ccode'";

    $result = mysqli_query($conn,$sql);
    if($result)
    {
        
        echo "Data Updated Successfully";
        header('location:Course-List.php');

    }

    else{
        die (mysqli_error($conn));
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lecturer Details - Student Attendance Management System</title>
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
                <h5>Edit Course Details</h5>
            </div>
   
            <div class="mb-3">
            <label class="form-label">Course Code:</label>
            <input type="text" class="form-control" placeholder="" name="course_code" autocomplete="off" value="<?php echo $Ccode;?>">
            </div>

            <div class="mb-3">
            <label class="form-label">Course Name:</label>
            <input type="text" class="form-control" placeholder="Course Name" name="course_name" autocomplete="off" >
            </div>


            <div class="mb-3">
                <label class="form-label">Credits:</label>
                <input type="text" class="form-control" placeholder = "Credits" name = "credits" autocomplete="off" >
                
            </div>

           
            <div class="mb-3">
                <label class="form-label">Lecture Hours:</label>
                <input type="tetx" class="form-control" placeholder = "Lecture Hours" name = "lecture_hours" autocomplete="off">
                
            </div>
            
            <button type="submit" class="btn btn-primary" name="update">Edit</button>
            <button type="submit" class="btn btn-danger">Cancel</button>
            <a href="Course-List.php" class="text-decoration-none">Back</a>
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