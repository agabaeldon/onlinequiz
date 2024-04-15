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
// Start a session
session_start();

include_once "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM Users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
 
    $row = $result->fetch_assoc();
    $status = $row['status'];
    $type = $row['type'];

    if ($status === "approved") {
        $_SESSION['user_id'] = $row['id'];

        if ($type === "user") {
            header("Location: userboard.php");
            exit();
        } elseif ($type === "admin") {
            header("Location: admindashboard.php");
            exit();
        }
    } elseif ($status === "pending") {
        ?>
        <div class="dialog-container">
            <h2>Your account is not yet approved.</h2>
            <p>Please try again later or contact the admin.</p>
            <button class="dialog-button" onclick="goToHomePage()">Back to Home</button>
        </div>
        <script>
            function goToHomePage() {
                window.location.href = "index.html";
            }
        </script>
        <?php
    }
} else {
    ?>
    <div class="dialog-container">
        <h2>User not found or incorrect credentials.</h2>
        <p>Please try again.</p>
        <button class="dialog-button" onclick="goToLoginPage()">Back to Login</button>
    </div>
    <script>
        function goToLoginPage() {
            window.location.href = "login.html";
        }
    </script>
    <?php
}

// Close connection
$conn->close();
?>
</body>
</html>
