<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type = "text/css" href="Style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
</head>
<body  class="body-login">
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="d-flex justify-content-center align-items-center">

        <form class="login" method ="post" action="req/login.php">
            <div class="text-center">
                <img src="img/download.png" alt="logo">
            </div>
            <h5>LOGIN</h5>
            <?php 
            if (isset($_GET['error'])) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_GET['error'] ?>
                </div>
            <?php
            }
            ?>
            

            <div class="mb-3">
                <label class="form-label">Login As</label>
                <select id="loginAs" class="form-control" name="role">
                    <option value="1">Admin</option>
                    <option value="2">Lecturer</option>
                    <option value="3">Student</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="uemail" >
            </div>
            <div class="mb-3">
                <label  class="form-label">Password</label>
                <input type="password" class="form-control" name="pass">
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php" class="text-decoration-none">Home</a>
            <p id="registerLink" style="display: none;"><a href="register.php">Don't have an account. Register</a></p>
        </form>

        
    </div>
    <footer class="text-center">
        <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
    </footer>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.getElementById('loginAs').addEventListener('change', function() {
        var role = this.value;
        var registerLink = document.getElementById('registerLink');
        if (role === '3') {
            registerLink.style.display = 'block';
        } else {
            registerLink.style.display = 'none';
        }
    });
</script>
</body>
</html>
