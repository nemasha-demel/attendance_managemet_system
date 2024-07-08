<?php
session_start();

if (isset($_POST['uemail']) && isset($_POST['pass']) && isset($_POST['role'])) {
    include "../DB_connection.php";

    $uemail = $_POST['uemail'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

   

    if (empty($uemail)) {
        $em = "Email is required";
        header("Location:../login.php?error=$em");
        exit;
    } elseif (empty($pass)) {
        $em = "Password is required";
        header("Location:../login.php?error=$em");
        exit;
    } elseif (empty($role)) {
        $em = "Role is required";
        header("Location:../login.php?error=$em");
        exit;
    } else {
        if ($role == '1') {
            $sql = "SELECT * FROM user WHERE email = ? AND role='Admin' ";
            $role_name = "Admin";
            $redirect_page = "../MA/MA_Dashboard/MA_Dashboard.php";
        } elseif ($role == '2') {
            $sql = "SELECT * FROM user WHERE email = ? AND role='Lecturer'";
            $role_name = "Lecturer";
            $redirect_page = "../Lecturer/index.php";
        } else {
            $sql = "SELECT * FROM user WHERE email = ? AND role='Student'";
            $role_name = "Student";
            $redirect_page = "../Student/index.php";
        }

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $uemail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $email = $user['email'];
                $password = $user['password'];
                $name = $user['name'];
                $id = $user['user_id'];
                

                if ($email == $uemail) {
                    if (password_verify($pass, $password)) {
                        $_SESSION['id'] = $id;
                        $_SESSION['name'] = $name;
                        $_SESSION['role'] = $role_name;
                        $_SESSION['user_id'] = $user['id'];  // Add user_id to session
                        header("Location: $redirect_page");
                        exit;
                    } else {
                        $em = "Incorrect email or password";
                        header("Location:../login.php?error=$em");
                        exit;
                    }
                }
            } else {
                $em = "Incorrect email or password";
                header("Location:../login.php?error=$em");
                exit;
            }
        } else {
            $em = "Database error: Unable to prepare statement";
            header("Location:../login.php?error=$em");
            exit;
        }
    }
} else {
    header("Location:../login.php");
}
?>
