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

        // Convert array answer to string
        $answer = implode(",", $answer);

        // Update the answer and status to "rejected" if "Rather Leave" is checked
        if (in_array("leave", $_POST['answer'])) {
            $status = "Rejected";
            $answer = "rejected";
        } else {
            // Retrieve the correct answer from the database
            $sql_correct_answer = "SELECT CorrectAnswer FROM Questions WHERE ID = ?";
            $stmt_correct_answer = $conn->prepare($sql_correct_answer);
            $stmt_correct_answer->bind_param("i", $question_id);
            $stmt_correct_answer->execute();
            $stmt_correct_answer->bind_result($correct_answer);
            $stmt_correct_answer->fetch();
            $stmt_correct_answer->close();

            // Check if the answer is correct or wrong
            $status = ($answer == $correct_answer) ? "Correct" : "Wrong";
        }

        // Insert the user's answer into the database
        $sql_insert_answer = "INSERT INTO UserAnswers (UserID, QuestionID, UserAnswer, Status) VALUES (?, ?, ?, ?)";
        $stmt_insert_answer = $conn->prepare($sql_insert_answer);
        $stmt_insert_answer->bind_param("iiss", $user_id, $question_id, $answer, $status);
        $stmt_insert_answer->execute();
        $stmt_insert_answer->close();

        // Redirect to userboard.php and show success message
        header("Location: userboard.php?success=1");
        exit();
    } else {
        // Redirect to userboard.php with failure parameter if answer or question_id is not set
        header("Location: userboard.php?failure=1");
        exit();
    }
} else {
    // Redirect to userboard.php with invalid request parameter if not a POST request
    header("Location: userboard.php?invalid_request=1");
    exit();
}
?>
