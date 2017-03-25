<?php
if(!isset($_SESSION))
{
session_start();
}
include 'connect.php';

$sql="select *  from tblfile";
$result=$db->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "FileName: " . $row["FileName"]. " - FileSize: " . $row["FileSize"]. "<br>";
    }
} else {
    echo "No File Uploaded";
}

?>