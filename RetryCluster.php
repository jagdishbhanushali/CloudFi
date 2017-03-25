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

$sql="select * from tblcluster where ClusterId=".$ClusterId;
$result=$db->query($sql);
if($result->num_rows!=0)
	{
		//header("Location:index.php?log=success");
		$row=$result->fetch_array();
		$ipAddress=$row['Ip'];
		
	}else{
		echo "No Cluster Found";
		return;
	}
$output= shell_exec("ping ".$ipAddress." -c 1 -w 1");
$pos= strpos($output,'received');
$pingStatus=(int)substr($output,$pos-2,1);
if($pingStatus==1){
    $output = shell_exec("sshpass -p 'CloudFi' ssh -o StrictHostKeyChecking=no CloudFi@".$ipAddress." df /home");
    //echo "<pre>$output</pre>";
    $resultArry=explode(' ',$output);
    $availableSpace= $resultArry[24];
    echo $availableSpace;
    $sql="update tblcluster set IsActive=1,Space=".$availableSpace." where ClusterId=".$ClusterId;
    if($db->query($sql)==TRUE){
        $_SESSION['message']= "Cluster added";
        header("Location:Clusters.php");
    }else{
        $_SESSION['message']= "Fail to update cluster status";
        header("Location:Clusters.php");
    }
}else{
    $_SESSION['message']= "Fail to ping cluster";
     header("Location:Clusters.php");
}

?>