<?php
var_dump(extension_loaded('ssh2'));
$ipAddress='52.32.6.1';
$output= shell_exec("ping ".$ipAddress." -c 1 -w 1");
	$pos= strpos($output,'received');
	$pingStatus=(int)substr($output,$pos-2,1);
	if($pingStatus==1){
		$output = shell_exec("sshpass -p 'CloudFi' ssh -o StrictHostKeyChecking=no CloudFi@".$ipAddress." df /home");
		echo "<pre>$output</pre>";
		$resultArry=explode(' ',$output);
		echo $resultArry[24];
		//print_r(array_values($resultArry));
		

	$output=shell_exec("sshpass -p 'CloudFi' scp /var/www/html/index.html CloudFi@".$ipAddress.":/home/CloudFi/index.html");

	echo "<pre>$output</pre>";

	$connection = ssh2_connect('ec2-52-32-6-1.us-west-2.compute.amazonaws.com', 22);
	ssh2_auth_password($connection, 'CloudFi', 'CloudFi');
	ssh2_scp_send($connection, '/var/www/html/index.html', '/home/CloudFi/index.html', 0777);
	// $stream = ssh2_exec($connection, 'df -h');
	// echo stream_get_contents($stream);
	//echo json_encode($stream);


	$connection = ssh2_connect('ec2-52-32-6-1.us-west-2.compute.amazonaws.com', 22);
	ssh2_auth_password($connection, 'CloudFi', 'CloudFi');
	ssh2_sftp($connection);
	ssh2_scp_recv($connection, '/home/CloudFi/index.html','/var/www/html/index23456.html');
	

}
//echo "<pre>$output</pre>";

?>
