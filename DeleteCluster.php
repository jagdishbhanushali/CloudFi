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
$ClusterId=$_GET['cid'];
$sql="delete from tblfiledistribution where Cluster1Id=".$ClusterId." OR Cluster2Id=".$ClusterId;
if($db->query($sql)){
	$sql="delete from tblcluster where ClusterId=".$ClusterId;
	if($db->query($sql)){
		header("Location:Clusters.php");
	}else{
		echo "Fail to delete from Cluster table";
		echo mysqli_error($db);
	}
}else{
	echo "Fail to delete from distribution table";
	echo mysqli_error($db);
}

?>