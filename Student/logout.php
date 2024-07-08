<?php
session_start();
session_destroy();

header("Location:../req/login.php");
exit;


?>