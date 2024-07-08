<?php
include("../DB_connection.php");
$id = $_GET['editid'];
if(isset($_POST['update']))
{
    $id = $_POST['lecturer_id'];
    $lecturername =$_POST['name'];
    $profession =$_POST['profession'];
    $email =$_POST['email'];

    $sql = "UPDATE `lecturer` SET name='$lecturername', profession='$profession', email='$email' WHERE lecturer_id='$id'";
    $sqlUser = "UPDATE `user` SET name='$lecturername',  email='$email' WHERE user_id='$id'";

    $result = mysqli_query($conn,$sql);
    $resultUser = mysqli_query($conn,$sqlUser);

    if($result && $resultUser)
    {
        
        echo "Data Updated Successfully";
        header('location:Lecturer-List.php');

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
   include("../DB_connection.php");;
    ?>
    
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="container">
   
        <div class="d-flex justify-content-center align-items-center">
        <form class="add-lecturer" method = "post">

            <div class="text-center"> 
                <h5>Edit Lecturer Details</h5>
            </div>
   
            <div class="mb-3">
            <label class="form-label">Lecturer ID:</label>
            <input type="text" class="form-control" placeholder="" name="lecturer_id" autocomplete="off" value="<?php echo $id;?>">
            </div>

            <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" class="form-control" placeholder="Full Name" name="name" autocomplete="off" >
            </div>


            <div class="mb-3">
                <label class="form-label">Profession:</label>
                <input type="text" class="form-control" placeholder = "Profession" name = "profession" autocomplete="off" >
                
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" placeholder = "Email" name = "email" autocomplete="off">
                
            </div>
            
            <button type="submit" class="btn btn-primary" name="update">Edit</button>
            <button type="submit" class="btn btn-danger">Cancel</button>
            <a href="Lecturer-List.php" class="text-decoration-none">Back</a>
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