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
        .view-button {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.view-button:hover {
    background-color: #45a049;
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
        <th>ID</th>
        <th>Question</th>
        <th>Abstract</th>
        <th>Yes</th>
        <th>No</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    include_once "db.php";

    // Retrieve all questions from the Questions table
    $sql = "SELECT ID AS QuestionID, QuestionText FROM Questions ORDER BY date_added DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questionID = $row['QuestionID'];

            // Retrieve the count of "Yes" answers for the specific question
            $sql_yes = "SELECT COUNT(*) AS YesCount FROM UserAnswers WHERE QuestionID = $questionID AND UserAnswer = 'yes'";
            $result_yes = $conn->query($sql_yes);
            $row_yes = $result_yes->fetch_assoc();
            $yes_count = $row_yes['YesCount'];

            // Retrieve the count of "No" answers for the specific question
            $sql_no = "SELECT COUNT(*) AS NoCount FROM UserAnswers WHERE QuestionID = $questionID AND UserAnswer = 'no'";
            $result_no = $conn->query($sql_no);
            $row_no = $result_no->fetch_assoc();
            $no_count = $row_no['NoCount'];

            // Retrieve the count of "rejected" answers for the specific question
            $sql_abs = "SELECT COUNT(*) AS AbsCount FROM UserAnswers WHERE QuestionID = $questionID AND UserAnswer = 'rejected'";
            $result_abs = $conn->query($sql_abs);
            $row_abs = $result_abs->fetch_assoc();
            $abs_count = $row_abs['AbsCount'];

            // If no votes found, set the counts to 0
            if ($yes_count == 0 && $no_count == 0 && $abs_count == 0) {
                $yes_count = 0;
                $no_count = 0;
                $abs_count = 0;
            }

            echo "<tr>";
            echo "<td>" . $row['QuestionID'] . "</td>";
            echo "<td>" . $row['QuestionText'] . "</td>";
            echo "<td>$abs_count</td>";
            echo "<td>$yes_count</td>";
            echo "<td>$no_count</td>";
            echo "<td><button class='view-button' onclick=\"viewGraph($questionID)\">View Graph</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No questions found</td></tr>";
    }

    $conn->close();
    ?>
    </tbody>
</table>
>

<script>
    function viewGraph(questionID) {
        window.location.href = "graph.php?id=" + questionID;
    }
</script>

</body>
</html>