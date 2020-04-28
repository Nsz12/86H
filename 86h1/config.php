<?php

$servername = "localhost";
$username = "id13230360_admin";
$password = "86Hadmin@kfupm";
$database = "id13230360_86h";
$connect=mysqli_connect($servername, $username,$password,$database);



if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());

    }

echo "Connected successfully";

 ?>
