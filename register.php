<?php
if(!isset($_SESSION))
{
session_start();
}
include 'connect.php';

if($_SERVER['REQUEST_METHOD']=='POST')
{
	 

$FirstName=$db->real_escape_string($_POST['firstname']);
$LastName=$db->real_escape_string($_POST['lastname']);
$UserName=$db->real_escape_string($_POST['username']);
$EmailId=$db->real_escape_string($_POST['email']);
$password=$db->real_escape_string($_POST['password']);

$sql="select * from tbluser where EmailId='".$EmailId."'";
$r=$db->query($sql);
if($r->num_rows==0)
{
	$sql="insert into tbluser (EmailId,Password,FirstName,LastName,UserName) values('".$EmailId."','".$password."','".$FirstName."','".$LastName."','".$UserName."')";
		//echo $sql;
	if($db->query($sql)==TRUE){
		$_SESSION['message']="Registration success";
		//echo $row['EmailId'];
		 
	}else{
		echo mysqli_error($db);
	}
	//echo mysql_error();
	
		//header( 'Location: index.php?reg=success' );
		
	
}else
	{
		//header( 'Location: index.php?reg=fail' );
		$_SESSION['message']="Registration Fail";
	}	
}

header("Location:index.php");

?>