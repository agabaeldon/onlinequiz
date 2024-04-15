<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Answers</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: white;
            color: black;
            padding: 2px 0;
            text-align: center;
        }

        header h1 {
            margin-bottom: 2px;
        }

        .back-home {
            display: inline-block;
            padding: 5px 10px;
            background-color: orangered;
            float:right;
            top:0;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-home:hover {
            background-color: #555;
        }


        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #dddddd;
        }

        th {
            background-color: darkblue;
            color: white;
        }

        .multiple-answers {
            color: red;
        }
    </style>
</head>
<body>
<header>
        <h1>User Answers</h1>
        <a href="admindashboard.php" class="back-home">Back Home</a>
    </header>
 <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>User Email</th>
                <th>User Answer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once "db.php";
            $answer_id = 1;

            $sql = "SELECT ua.ID AS AnswerID, q.QuestionText, u.Email, ua.UserAnswer, ua.Status
                    FROM UserAnswers ua
                    INNER JOIN Users u ON ua.UserID = u.ID
                    INNER JOIN Questions q ON ua.QuestionID = q.ID
                    ORDER BY ua.ID";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $prev_question_id = null;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $answer_id++ . "</td>"; 
                    echo "<td>" . $row['QuestionText'] . "</td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td>";
                    if ($prev_question_id !== $row['AnswerID']) {
                        echo $row['UserAnswer'];
                    } else {
                        echo "<span class='multiple-answers'>(Multiple Answers)</span>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($row['Status'] == 'Correct') {
                        echo "<i class='material-icons' style='color: green;'>check</i>";
                    } else {
                        echo "<i class='material-icons' style='color: red;'>clear</i>";
                    }
                    echo "</td>";
                    echo "</tr>";
                    $prev_question_id = $row['AnswerID'];
                }
            } else {
                echo "<tr><td colspan='5'>No data found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
