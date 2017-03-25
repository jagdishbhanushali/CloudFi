<?php

if(!isset($_SESSION))
{
session_start();
}

if($_SESSION['Login']!=true){
    echo "Plese login";
    return false;
}

include 'connect.php';
$fileId=$_GET['fid'];
$cb=$_GET['cb'];
$sql="delete from tblfile where FileId=".$fileId;
if($db->query($sql)){
	$sql="delete from tblfiledistribution where FileId=".$fileId;
	if($db->query($sql)){
		
	}else{
		echo "Fail to delete from distribution table";
		echo mysqli_error($db);
	}
}else{
	echo "Fail to delete from file table";
	echo mysqli_error($db);
}

if($cb=="admin")
	header("Location:Admin.php");
else{
	header("Location:index.php");
}

?>