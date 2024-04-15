<?php
include_once "db.php";

if(isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    
    $sql = "UPDATE Users SET status='approved' WHERE id='$user_id'";
    if(mysqli_query($conn, $sql)) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    header("Location: users.php");
    exit();
}
?>
