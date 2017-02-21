<?php
//end section when user click logout
session_start();
session_unset();
session_destroy();
header('location:login.php');
?>