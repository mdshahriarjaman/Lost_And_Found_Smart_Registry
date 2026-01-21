<?php

$host    = "127.0.0.1";      
$username    = "root";
$pass    = "";               
$db_name = "lostandfounddb";
$port    = 3306;

function dbConnect()
{
    global $host, $username, $pass, $db_name, $port;

    mysqli_report(MYSQLI_REPORT_OFF); 

    $conn = mysqli_connect($host, $username, $pass, $db_name, $port);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
