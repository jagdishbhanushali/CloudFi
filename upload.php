<?php
if(!isset($_SESSION))
{
session_start();
}
include 'connect.php';

if($_SESSION['Login']!=true){
    $_SESSION['message']= "Plese login";
    return false;
}

$UserId=$_SESSION['UserId'];

$target_dir = "/var/www/html/uploads/";


$fileName = basename($_FILES["fileToUpload"]["name"]) ;
$fileSize = basename($_FILES["fileToUpload"]["size"]) ;
echo json_encode($_FILES);

$target_file = $target_dir . $fileName;

move_uploaded_file( $_FILES['fileToUpload']['tmp_name'], $target_file);
$sql="insert into tblfile (UserId,FileName,FileSize) values (".$UserId.",'".$fileName."',".$fileSize.")";
$Cluster1Id=0;
$Cluster2Id=0;
$FileLocationCluster1="";
$FileLocationCluster2="";

$count=0;
if($db->query($sql)==TRUE){
	$FileId=$db->insert_id;
	$sql="select * from tblcluster where Space > $fileSize and IsActive=1 and UserId<>$UserId order by Space";
	$result=$db->query($sql);
	if($result->num_rows!=0){
		// $row=$result->fetch_array();
		//Checking every Cluster for availibility
		while($row =mysqli_fetch_assoc($result)){
		    $ClusterIp= $row['Ip'];
		    $Space= $row['Space'];
		    $UserId= $row['UserId'];
		    $Password=$row['Password'];
		    $ClusterId=$row['ClusterId'];
		    $HostName=$row['HostName'];
		    //ping given IP and check for space and upload file
		    $output= shell_exec("ping ".$ClusterIp." -c 1 -w 1");
			$pos= strpos($output,'received');
			$pingStatus=(int)substr($output,$pos-2,1);
			if($pingStatus==1){
				$output = shell_exec("sshpass -p '".$Password."' ssh -o StrictHostKeyChecking=no CloudFi@".$ClusterIp." df /home");
				//echo "<pre>$output</pre>";
				$resultArry=explode(' ',$output);
				$availableSpace = $resultArry[24];
				if($availableSpace> $fileSize){
					//if success count++
					//$connection = ssh2_connect('ec2-52-32-6-1.us-west-2.compute.amazonaws.com', 22);
					$connection = ssh2_connect($HostName, 22);
					ssh2_auth_password($connection, 'CloudFi', $Password);
					ssh2_scp_send($connection, $target_file, '/home/CloudFi/'.$fileName, 0777);
				    if($count==0){
				    	$Cluster1Id=$ClusterId;
				    	$FileLocationCluster1='/home/CloudFi/'.$fileName; 	//Use same location where you put file
				    	$count++;
				    }else if($count==1){
				    	$Cluster2Id=$ClusterId;
				    	$FileLocationCluster2='/home/CloudFi/'.$fileName;
				    	$count++;
				    	break;
				    }
				}

			}

		    //limit while loop for 2 iteration only
		}//End of while loop
		if($count==1){
			$sql="insert into tblfiledistribution (FileId,Cluster1Id,Cluster2Id,FileLocationCluster1,FileLocationCluster2) values ($FileId,$Cluster1Id,$Cluster1Id,'$FileLocationCluster1','$FileLocationCluster1')";
			//echo "1";
		}else{
			$sql="insert into tblfiledistribution (FileId,Cluster1Id,Cluster2Id,FileLocationCluster1,FileLocationCluster2) values ($FileId,$Cluster1Id,$Cluster2Id,'$FileLocationCluster1','$FileLocationCluster2')";
			//echo "2";
		}
		if($db->query($sql)==TRUE){
			$_SESSION['message']= "Uploaded";
		}else{
			$_SESSION['message']= "Fail to insert distribution information<br/>". mysqli_error($db)."<br/>".$sql;
			//$_SESSION['message']= mysqli_error($db);
		}
	}else{
		$_SESSION['message']= "No enogh space at any cluster";	
	}
	//$_SESSION['message']= "<b>uploaded and saved</b>";
}else{
	$_SESSION['message']= "<b>Error</b>";
	//echo mysqli_error($db);
}
header("Location:index.php");
?>