<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['answer']) && isset($_POST['question_id'])) {
        $answer = $_POST['answer'];
        $question_id = $_POST['question_id'];
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT CorrectAnswer FROM Questions WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $stmt->bind_result($correct_answer);
        $stmt->fetch();
        $stmt->close();

        $status = ($answer == $correct_answer) ? "Correct" : "Wrong";

        $sql = "INSERT INTO UserAnswers (UserID, QuestionID, UserAnswer, Status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $question_id, $answer, $status);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT * FROM Questions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $questions = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $questions = array(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <style> 
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn:active {
            background-color: #0056b3;
            transform: translateY(1px);
        }
    </style>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<p>Your answer was submitted successfully!</p>";
}
?>

<a href="userboard.php" class="btn btn-primary">Back to Home</a>

</body>
</html>
