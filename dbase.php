<?php
$dbServername="localhost";
$dbUserName="root";
$dbPassword="pf147";
$dbName="e-commerce";

$conn=mysqli_connect($dbServername,$dbUserName,$dbPassword,$dbName);

if(!$conn){
    die("connection failed:".mysqli_connect_error());
}

?>  

