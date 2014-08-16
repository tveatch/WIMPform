<?php 

    // First we execute our common code to connection to the database and start the session 
    require("_inc/common.php"); 
     
    // We remove the user's data from the session 
    unset($_SESSION['user']);
	unset($_SESSION['timeout']);
     
    // We redirect them to the login page 
    header("Location: login.php"); 
    die("Redirecting to: login.php");