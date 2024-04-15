<?php
include_once "db.php";

if(isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    $sql = "DELETE FROM Users WHERE id='$user_id'";
    if(mysqli_query($conn, $sql)) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: users.php");
    exit();
}
?>
