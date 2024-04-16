<?php
include_once "db.php";

// Retrieve the latest question from the Questions table
$sql_latest_question = "SELECT ID AS QuestionID, QuestionText FROM Questions ORDER BY date_added DESC LIMIT 1";
$result_latest_question = $conn->query($sql_latest_question);

if ($result_latest_question->num_rows > 0) {
    // Fetch the latest question
    $row_latest_question = $result_latest_question->fetch_assoc();
    $latest_question_id = $row_latest_question['QuestionID'];
    $latest_question_text = $row_latest_question['QuestionText'];

    // Retrieve the count of "Yes" answers for the latest question
    $sql_yes = "SELECT COUNT(*) AS YesCount FROM UserAnswers WHERE QuestionID = $latest_question_id AND UserAnswer = 'yes'";
    $result_yes = $conn->query($sql_yes);
    $row_yes = $result_yes->fetch_assoc();
    $yes_count = $row_yes['YesCount'];

    // Retrieve the count of "No" answers for the latest question
    $sql_no = "SELECT COUNT(*) AS NoCount FROM UserAnswers WHERE QuestionID = $latest_question_id AND UserAnswer = 'no'";
    $result_no = $conn->query($sql_no);
    $row_no = $result_no->fetch_assoc();
    $no_count = $row_no['NoCount'];

    // Calculate percentages only if there are votes
    if ($yes_count + $no_count > 0) {
        $yesPercentage = ($yes_count / ($yes_count + $no_count)) * 100;
        $noPercentage = ($no_count / ($yes_count + $no_count)) * 100;
    } else {
        // If there are no votes, set percentages to 0
        $yesPercentage = 0;
        $noPercentage = 0;
    }

    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>User Answers</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/chart.js'>

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

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            margin-top: 20px;
            position: relative;
            width: 80%;
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
<header>
    <h1>User Answers</h1>
</header>
<main>
    <h2>Latest Question: $latest_question_text</h2>
    <div class='chart-container'>
        <canvas id='myChart'></canvas>
    </div>
</main>

<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
<script>
    var yesPercentage = $yesPercentage;
    var noPercentage = $noPercentage;

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Yes', 'No'],
            datasets: [{
                label: 'Percentage of Votes',
                data: [yesPercentage, noPercentage],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)', // Blue color for Yes
                    'rgba(255, 99, 132, 0.5)'  // Red color for No
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value + '%'; // Add '%' suffix to y-axis ticks
                        }
                    }
                }]
            }
        }
    });
</script>

</body>
</html>";
} else {
    echo "No questions found";
}

$conn->close();
?>
