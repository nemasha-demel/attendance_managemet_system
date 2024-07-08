<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type = "text/css" href="Style.css">
    <link rel="icon" href="img/download.png">  <!--Icon of title bar -->
</head>
<body  class="body-home">
    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-light" id="homeNav">
            
        <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="img/download.png" width=40px height=40px  alt="logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                        </li>
                        
                    </ul>

                    <ul class="navbar-nav me-right mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    </ul>
        
                </div>
                
            </div>
        </nav>
        <section class="welcome-text d-flex justify-content-center align-items-center flex-column">
            <img src="img/download.png" alt="logo">
            <h4>Welcome to University Of Jaffna</h4>
            <p>Welcome to our University Attendance Management System! 
                Our cutting-edge platform streamlines attendance tracking for both students and faculty,
                 ensuring accuracy and efficiency in recording attendance records. With user-friendly interfaces, 
                 students can easily mark their attendance, view their attendance history, 
                 and stay updated on their academic progress. Faculty members can effortlessly manage class rosters, 
                 monitor student attendance, and generate reports to analyze attendance trends. Together, 
                 we foster a culture of accountability and engagement, promoting student success and academic excellence. 
                 Join us in embracing technology to enhance the educational experience!</p>

        </section>

        <section  id= "about" class="d-flex justify-content-center align-items-center flex-column">
            
            <div class="card mb-3 card-1">
                <div class="row g-0">
                    <div class="col-md-4">
                    <img src="img/download.png" class="img-fluid rounded-start" alt="logo">
                    </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">About Us</h5>
                                <p class="card-text">The Faculty of Engineering Attendance 
                                    Management System is here to streamline the process of tracking attendance for both faculty and students. This user-friendly system empowers faculty to efficiently record attendance and access valuable insights, while students can monitor their attendance 
                                    and stay informed about their academic standing.</p>
                                <p class="card-text"><small class="text-body-secondary">Faculty Of Engineering</small></p>
                            </div>
                        </div>
                </div>
            </div>
        </section>

        <section  id = "contact" class=" d-flex justify-content-center align-items-center flex-column">
            <form>
                <h5>Contact Us</h5>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <textarea   class="form-control" rows="4"> </textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </section>
     
        
        </div>
        <footer class="text-center">
            <p> &copy; 2024 Geethma & Nemasha. All rights reserved.</p>
    </footer>

    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>