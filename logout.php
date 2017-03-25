<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['Login']=false;
$_SESSION['Username']="";
$_SESSION['EmailId']="";
$_SESSION['UserId']="";

header("Location:index.php");
?>