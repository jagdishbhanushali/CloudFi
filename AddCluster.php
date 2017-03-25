<?php
if(!isset($_SESSION))
{
session_start();
}

include 'connect.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
$username=$_SESSION['Username'];
$userId=$_SESSION['UserId'];
$ipAddress=$db->real_escape_string($_POST['ipAddress']);
$password="CloudFi"; //$db->real_escape_string($_POST['password']);n
$hostName=$db->real_escape_string($_POST['hostname']);
$sql="insert into tblcluster (UserId, Ip, password, IsActive,hostname) values ('$userId','$ipAddress','$password',0,'$hostName')";

if($db->query($sql)==TRUE){
	$ClusterInsertedId=$db->insert_id;
	$output= shell_exec("ping ".$ipAddress." -c 1 -w 1");
	$pos= strpos($output,'received');
	$pingStatus=(int)substr($output,$pos-2,1);
	if($pingStatus==1){
		$output = shell_exec("sshpass -p 'CloudFi' ssh -o StrictHostKeyChecking=no CloudFi@".$ipAddress." df /home");
		//echo "<pre>$output</pre>";
		$resultArry=explode(' ',$output);
		$availableSpace= $resultArry[24];
		echo $availableSpace;
		$sql="update tblcluster set IsActive=1,Space=$availableSpace where ClusterId=$ClusterInsertedId";
		if($db->query($sql)==TRUE){
			echo "Cluster added";
		}else{
			echo "Fail to update cluster status";
			echo mysqli_error($db);
		}
	}else{
		echo "Fail to ping";
	}

	//if fail to ping
	//send retry message


}else{
	echo mysqli_error($db);
}
}else{
	?>
	<!DOCTYPE html>
	<html>
		<title>Add Cluster</title>
		<body>
			<form action="AddCluster.php" method="POST">
				<input type="text" name="ipAddress" placeholder="IP Address" /> <br/>
				<input type="text" name="hostname" placeholder="HostName"><br/>
				<input type="text" name="password" placeholder="Password of Cluster" /> <br/>
				<input type="submit" name="Submit"/>
			</form>
		</body>
	</html>

	<?php
}

?>