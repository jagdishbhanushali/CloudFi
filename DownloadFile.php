<?php

if(!isset($_SESSION))
{
session_start();
}

include 'connect.php';

if($_SESSION['Login']!=true){
    echo "Plese login";
    return false;
}

ignore_user_abort(true);
set_time_limit(0); 
$userId=$_SESSION['UserId'];
$fileId=$_GET['fid'];

// $sql="select * from tblfiledistribution where fileId=".$fileId;
$sql="select  
    fd.Cluster1Id,
    fd.Cluster2Id,
    fd.FileId,
    tc.Ip as IpCluster1,
    tc2.Ip as IpCluster2,
    fd.FileLocationCluster1,
    fd.FileLocationCluster2,
    tc.Password as PasswordCluster1,
    tc2.Password as PasswordCluster2,
    tc.HostName as HostNameCluster1,
    tc2.HostName as HostNameCluster2,
    tf.FileName
from tblfiledistribution as fd INNER JOIN tblcluster as tc ON fd.Cluster1Id = tc.ClusterId 
INNER JOIN tblcluster AS tc2 on fd.Cluster2Id=tc2.ClusterId
INNER JOIN tblfile as tf ON fd.FileId=tf.FileId
where fd.fileId=".$fileId;


$result=$db->query($sql);
if($result->num_rows!=0){
   $row =mysqli_fetch_assoc($result);
    $ClusterId= $row['Cluster1Id'];
    $FileLocationCluster1= $row['FileLocationCluster1'];
    $IpCluster1= $row['IpCluster1'];
    $passwordCluster1=$row['PasswordCluster1'];
    $HostNameCluster1=$row['HostNameCluster1'];

    $FileLocationCluster2= $row['FileLocationCluster2'];
    $IpCluster2= $row['IpCluster2'];
    $passwordCluster2=$row['PasswordCluster2'];
    $HostNameCluster2=$row['HostNameCluster2'];

    $FileName=$row['FileName'];
    //connect to ec2 instatce using ip password
    $output= shell_exec("ping ".$IpCluster1." -c 1 -w 1");
    $pos= strpos($output,'received');
    $pingStatus=(int)substr($output,$pos-2,1);
    if($pingStatus==1){
        $connection = ssh2_connect($HostNameCluster1, 22);
        ssh2_auth_password($connection, 'CloudFi', $passwordCluster1);
        ssh2_sftp($connection);
        ssh2_scp_recv($connection, $FileLocationCluster1 ,'/var/www/html/temp/'.$FileName);
    }else{
        //connecting to second cluster
        $output= shell_exec("ping ".$IpCluster2." -c 1 -w 1");
        $pos= strpos($output,'received');
        $pingStatus=(int)substr($output,$pos-2,1);
        if($pingStatus==1){
            $connection = ssh2_connect($HostNameCluster2, 22);
            ssh2_auth_password($connection, 'CloudFi', $passwordCluster2);
            ssh2_sftp($connection);
            ssh2_scp_recv($connection, $FileLocationCluster2 ,'/var/www/html/temp/'.$FileName);
        }else{
            echo "Both machines are OFF";
            return;
        }
    }
    //get file from location and save it to locally 
    //$dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $_GET['download_file']); // simple file name validation
    //$dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters

    //$path = "./uploads/Capture.JPG"; //path to saved file
    $path='/var/www/html/temp/'.$FileName;
    $fullPath = $path ;//.$dl_file;
     
    if ($fd = fopen ($fullPath, "r")) {
        $fsize = filesize($fullPath);
        $path_parts = pathinfo($fullPath);
        $ext = strtolower($path_parts["extension"]);
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
        header("Content-length: $fsize");
        header("Cache-control: private");
        while(!feof($fd)) {
            $buffer = fread($fd, 2048);
            echo $buffer;
        }
    }
    fclose ($fd);
}
//header("Location:index.php");
?>