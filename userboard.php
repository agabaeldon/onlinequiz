<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Yes or No Question</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        
        header {
            width: 100%;
            background-color: darkblue;
            color: white;
            padding: 1px 0;
            text-align: center;
        }
        header button {
            background-color: red;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            padding: 10px;
            float: right;
            margin-right: 20px;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin-top: 20px;
        }
        .container h1 {
            border-top: 1px solid #ccc;
            padding-bottom: 10px; /* Optional: Adjust padding as needed */
        }

        form {
            margin-top: 30px;
        }
        label {
            margin-right: 10px;
            font-size: 18px; /* Increased font size */
        }
        input[type="checkbox"] {
            width: 25px; /* Increased checkbox size */
            height: 25px;
        }
        .checkbox-group {
            display: flex; /* Display checkboxes in a flex container */
            align-items: center; /* Align items vertically center */
            justify-content: center; /* Center items horizontally */
        }
        button[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        
    </style>
</head>
<body>
<header>
        <h1>Welcome User</h1>
        <button id="logout-btn">Log Out</button>
    </header>
    <div class="container">
        <div> <h2> Answer the questions</h2></div>
        <?php
        include_once "db.php";

        // Fetch user's answered questions
        $user_id = $_SESSION['user_id'];
        $sql_user_answers = "SELECT QuestionID FROM UserAnswers WHERE UserID = ?";
        $stmt_user_answers = $conn->prepare($sql_user_answers);
        $stmt_user_answers->bind_param("i", $user_id);
        $stmt_user_answers->execute();
        $result_user_answers = $stmt_user_answers->get_result();
        $answered_question_ids = [];
        while ($row = $result_user_answers->fetch_assoc()) {
            $answered_question_ids[] = $row['QuestionID'];
        }
        $stmt_user_answers->close();

        // Fetch total number of questions
        $sql_total_questions = "SELECT * FROM Questions";
        $result_total_questions = $conn->query($sql_total_questions);
        if ($result_total_questions->num_rows > 0) {
            while ($question = $result_total_questions->fetch_assoc()) {
                // Check if the question has already been answered by the user
                if (in_array($question['ID'], $answered_question_ids)) {
                    continue; // Skip this question
                }

                echo '<h1>'.$question['QuestionText'].'</h1>';
                echo '<form class="answerForm" method="post" action="user_answer.php">';
                echo '<input type="hidden" name="question_id" value="'.$question['ID'].'">'; // Added hidden input for question ID
                echo '<div class="checkbox-group">'; // Added a container for checkboxes
                echo '<label for="answerYes">Yes</label>';
                echo '<input type="checkbox" id="answerYes" name="answer[]" value="yes">';
                echo '<label for="answerNo">No</label>';
                echo '<input type="checkbox" id="answerNo" name="answer[]" value="no">';
                echo '<label for="answerLeave">Abstain</label>'; // Added label for "Rather Leave" checkbox
                echo '<input type="checkbox" id="answerLeave" name="answer[]" value="leave" checked>'; // Added "Rather Leave" checkbox with default checked
                echo '</div>';
                echo '<button type="submit">Submit</button>';
                echo '</form>';
            }
        } else {
            echo "<p>No questions available.</p>";
        }

        if (count($answered_question_ids) >= $result_total_questions->num_rows) {
            echo '<p><h1><span style="color: orangered; font-size: 60px;">&#9888;</span> You have already voted for all available questions. Thank you for your participation!</h1></p>';
        }
        ?>
    </div>
    <?php include 'dialog.php'; ?>

    <script>
        document.getElementById("logout-btn").addEventListener("click", function() {
            window.location.href = "logout.php";
        });

        // Function to uncheck other checkboxes when one is checked
        function uncheckOthers(checkbox) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
        }

        // Attach event listeners to checkboxes
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    uncheckOthers(this);
                }
            });
        });
    </script>
</body>

</html>

<script>
    document.getElementById("logout-btn").addEventListener("click", function() {
        window.location.href = "logout.php";
    });

    // Function to uncheck other checkboxes when one is checked
    function uncheckOthers(checkbox) {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(cb) {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    }

    // Attach event listeners to checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                uncheckOthers(this);
            }
        });
    });
    
</script>
<script>
    document.getElementById("logout-btn").addEventListener("click", function() {
        window.location.href = "logout.php";
    });
</script>