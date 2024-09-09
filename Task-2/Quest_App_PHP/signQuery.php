<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: register.php'); 
    exit;
  }
  
  error_reporting(0);
  ini_set('display_errors', 0);
  
  
include "functions/functions.php"; 

if (isset($_POST['username']) && isset($_POST['password'])) {
    $nickname = $_POST['username'];
    $passwd = $_POST['password'];
    
    Login($nickname, $passwd);
    exit;
}
