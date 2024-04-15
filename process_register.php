<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <style>
        .dialog-container {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 300px;
            margin: 100px auto;
        }

        .dialog-container h2 {
            margin-top: 0;
        }
        .dialog-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php
include_once "db.php";

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm-password'];

if ($password !== $confirmPassword) {
    echo "Error: Passwords do not match";
    exit;
}

$type = "user";
$status = "pending";

$sql = "INSERT INTO Users (name, email, phone, password, type, status) 
        VALUES ('$fullname', '$email', '$phone', '$password', '$type', '$status')";
if ($conn->query($sql) === TRUE) {
    echo '<div class="dialog-container">
            <h2>Your account is not yet approved.</h2>
            <p>Please try again later or contact the admin.</p>
            <button class="dialog-button" onclick="goToHomePage()">Back to Home</button>
          </div>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<script>
    function goToHomePage() {
        window.location.href = "index.html";
    }
</script>