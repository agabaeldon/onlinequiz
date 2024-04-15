<?php
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "quiz";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function execute_query($sql) {
    global $conn;
    $result = $conn->query($sql);
    return $result;
}

function escape_string($str) {
    global $conn;
    return $conn->real_escape_string($str);
}
?>
