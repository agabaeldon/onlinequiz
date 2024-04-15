<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Question</title>
    <style>
        .dialog {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .dialog-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 5px;
            width: 50%;
            text-align: center;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .dialog-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php
include_once "db.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $questionText = $_POST['questionText'];
    $correctAnswer = $_POST['correctAnswer'];

    if (empty($questionText) || empty($correctAnswer)) {
        echo "Please fill in all fields.";
    } else {
        // Prepare SQL statement to insert the new question
        $sql = "INSERT INTO Questions (QuestionText, CorrectAnswer) VALUES (?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ss", $questionText, $correctAnswer);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo '<div id="dialog" class="dialog">';
            echo '<div class="dialog-content">';
            echo '<span class="close-btn" onclick="closeDialog()">&times;</span>';
            echo '<p>Question added successfully.</p>';
            echo '<button class="dialog-button" onclick="goToHomePage()">Back Home</button>';
            echo '</div>';
            echo '</div>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        // Close statement
        $stmt->close();
    }
}
?>

<script>
    function showDialog() {
        document.getElementById("dialog").style.display = "block";
    }

    function closeDialog() {
        document.getElementById("dialog").style.display = "none";
    }

    function goToHomePage() {
        window.location.href = "admindashboard.php";
    }

    showDialog();
</script>

</body>
</html>
