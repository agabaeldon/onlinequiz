<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.html");
            exit();
        }

        include_once "db.php";

        $sql = "SELECT * FROM Questions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $questions = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($questions as $question) {
                echo '<h1>'.$question['QuestionText'].'</h1>';
                echo '<form class="answerForm" method="post" action="user_answer.php">';
                echo '<input type="hidden" name="question_id" value="'.$question['ID'].'">'; // Added hidden input for question ID
                echo '<label for="answerYes">Yes</label>';
                echo '<input type="radio" id="answerYes" name="answer" value="yes">';
                echo '<label for="answerNo">No</label>';
                echo '<input type="radio" id="answerNo" name="answer" value="no">';
                echo '<button type="submit">Submit</button>';
                echo '</form>';
            }
        } else {
            echo "<p>No questions available.</p>";
        }
        ?>
    </div>
</body>
</html>

<script>   document.getElementById("logout-btn").addEventListener("click", function() {
    window.location.href = "logout.php";
  });
</script>