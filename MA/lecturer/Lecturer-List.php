<?php
 include("../DB_connection.php");
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer List - Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>
<body  class="body-home">

<?php
    include "../inc/navbar.php";
    ?>
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="container">
    

        <div class="content">

        <div class="tablename">
            <h5 style="text-align:center;">Lecturers</h5>
        </div>
        
            
            <table class="table table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Profession</th>
                        <th>Email</th>
                        <th>Action</th>    
                    </tr>
                </thead>

                <?php
                    $sql= "Select name,email,profession,lecturer_id From `lecturer`";
                    $result = mysqli_query($conn,$sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['lecturer_id'];
                            $lecturername =$row['name'];
                            $profession =$row['profession'];
                            $email =$row['email'];
                            echo '<tr>
                            <td>' .$lecturername. '</td>
                            <td>'.$profession.'</td>
                            <td>'.$email.'</td>
                            <td>
                            <button class="btn btn-primary "><a href="Edit-Lecturer.php? editid='.$id.'" class="text-light">Edit</a></button>
                            <button class="btn btn-danger "><a href="Delete-Lecturer.php? deleteid='.$email.'" class="text-light">Delete</a></button>

                            </td>
                            
                            </tr>
                            ';
                        }
                        
                    }

                    ?>
            </table>
            </div>

            <div class="add">
                <button><a href="Add-Lecturer.php">+</a></button>
            </div>
            <footer class="text-center">
                <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
            </footer>
        </div>
        
</div>    

    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>