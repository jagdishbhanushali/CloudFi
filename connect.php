<?php
$user="root";
$pass="";
$db="frookey";
$server="localhost";
$db = mysqli_connect($server, $user, $pass, $db);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>