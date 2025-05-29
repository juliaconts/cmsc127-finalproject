<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cmsc127_finalproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $connection->connect_error);
}

echo " ";
?>