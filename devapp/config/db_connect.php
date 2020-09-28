<?php

//info for database
$hostname = "localhost";
$username = "joe";
$password = "test1234";

//connecting to database
try
{
    $conn = new PDO("mysql:host=$hostname;dbname=register", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
}
catch(PDOException $pe)
{
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}
?>
