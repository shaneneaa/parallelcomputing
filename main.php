<?php
// require_once('connect.php');
require_once('function.php');

$email = $_REQUEST['email'];
$password =$_REQUEST['password'];

 login($email, $password);
?>