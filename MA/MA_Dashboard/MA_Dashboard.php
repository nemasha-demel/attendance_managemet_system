<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Dashboard_style.css">
    <link rel="icon" href="download.png">  <!--Icon of title bar -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
</head>
<body class="body-home">

    <?php
    include("../DB_connection.php");
        include "../inc/navbar.php";
    ?>

    <div class="black-fill"> <br> </br><!--If you don't want to shade remove this division-->
        <div class="container">

        

            <section id="boxes" class="mt-4">
                <div class="card">
                <div class="card-header">
                    <h5>Mark Attendance</h5>
                    </div>

                    <table class="table table1">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>Course Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <div class="card-body">
                        <tbody>
                        <?php
                            

                            // Current date to compare
                            $today = date('Y-m-d');

                            // SQL query to fetch the required data
                            $sql = "
                                SELECT s.date, s.start_time, s.course_code, c.batch, c.semester, c.lecturer as lecturer_name, c.specialization
                                FROM schedule s
                                LEFT JOIN attendance a ON s.course_code = a.course_code AND s.date = a.date
                                JOIN course c ON s.course_code = c.course_code
                                WHERE a.date IS NULL AND s.date <= '$today'
                                                        ";

                            // Execute the query
                            $result = mysqli_query($conn, $sql);

                            // Check if there are results
                            if (mysqli_num_rows($result) > 0) {
                                // Output data of each row
                                while($row = mysqli_fetch_assoc($result)) {
                                    $url_params = http_build_query([
                                        'coursecode' => $row['course_code'],
                                        'semester' => $row['semester'],
                                        'batch' => $row['batch'],
                                        'date' => $row['date'],
                                        'time' => $row['start_time'],
                                        'lecturer' => $row['lecturer_name'],
                                        'specialization' => urlencode($row['specialization'])
                                    ]);
                                    echo "<tr>";
                                    echo "<td>" . $row["date"] . "</td>";
                                    echo "<td>" . $row["start_time"] . "</td>";
                                    echo "<td>" . $row["course_code"] . "</td>";
                                    echo "<td><a href='../attendance/Take-Attendance.php?$url_params' class='btn btn-primary'>Mark</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No records found</td></tr>";
                            }

                           
                            ?>


                        </tbody>
                        </div>
                    </table>
                </div>
            </section>

          
                        
           <!--  Section for Pending Student Registration Requests -->
           <section id="register-requests" class="mt-4">
                <div class="card ">
                <div class="card-header">
                    <h5>Register Requests</h5>
                        </div>
                    <table class="table table2">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Registration Number</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <div class="card-body">
                        <tbody>
                        <?php
                            // SQL query to fetch pending students
                            $sql = "
                                SELECT fname, mname, lname, reg_no, email
                                FROM student
                                WHERE status = 'Pending'
                            ";

                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                
                                while($row = mysqli_fetch_assoc($result)) {
                                    $initials = strtoupper($row['fname'][0]) . '. ' . strtoupper($row['mname'][0]) . '. ' . $row['lname'];
                                    echo "<tr>";
                                    echo "<td>" . $initials . "</td>";
                                    echo "<td>" . $row["reg_no"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td><a href='approve_registration.php?reg_no=" . $row['reg_no'] . "' class='btn btn-primary'>Approve</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No pending registration requests</td></tr>";
                            }
                        ?>
                        </tbody>
                        </div>
                    </table>
                </div>
            </section>

            <section id="notifications" class="mt-4">          
            <div class="card">
                <div class="card-header">
                    <h5>Notifications/Alerts <span class="material-symbols-outlined">
                    notifications_active
                        </span></h5>   
                </div>
                <div class="card-body">
                    <div id="notification-list" class="list-group">
                        <!-- Notifications will be populated here -->
                        
                    </div>
                </div>
            </div>
        </section>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>   
    <script>
        $(document).ready(function() {
            // Function to fetch notifications
            function fetchNotifications() {
                $.getJSON('fetch_notifications.php', function(data) {
                    var notificationList = '';
                    $.each(data, function(index, notification) {
                        var readClass = notification.checked === 'Yes' ? 'notification-read' : '';
                        notificationList += `
                            <div class="notification-container ${readClass}">
                                <div class="notification-header">
                                    ${notification.name} (${notification.role}) - ${notification.email}
                                </div>
                                <div class="notification-message">
                                    ${notification.message}
                                </div>
                                <div class="notification-timestamp">
                                    ${notification.date_time}
                                    <span class="material-symbols-outlined notification-icon" data-email="${notification.email}" data-date_time="${notification.date_time}">
                                        mark_chat_read
                                    </span>
                                </div>
                            </div>`;
                    });
                    $('#notification-list').html(notificationList);
                });
            }

            // Fetch notifications on page load
            fetchNotifications();

            // Handle click event on "Mark as Read" icon
            $('#notification-list').on('click', '.notification-icon', function() {
                var email = $(this).data('email');
                var date_time = $(this).data('date_time');

                $.ajax({
                    url: 'update_notification.php',
                    type: 'POST',
                    data: { email: email, date_time: date_time },
                    success: function(response) {
                        alert(response);
                        fetchNotifications(); // Refresh notifications
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    }
                });
            });
        });
    </script>

</body>
</html>


