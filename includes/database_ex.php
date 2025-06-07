<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', true );

$connect = mysqli_connect("hostname", "username", "password", "database");

if(mysqli_connect_errno()){
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// echo "Connected successfully <br />";
