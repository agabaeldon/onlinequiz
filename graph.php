<?php
include_once "db.php";

// Retrieve the question ID from the query parameter
$questionID = $_GET['id'];

// Retrieve the question text for displaying in the graph description
$sql_question = "SELECT QuestionText FROM Questions WHERE ID = $questionID";
$result_question = $conn->query($sql_question);
$row_question = $result_question->fetch_assoc();
$questionText = $row_question['QuestionText'];

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

// Calculate percentages
$total_votes = $yes_count + $no_count;
$yes_percentage = ($total_votes > 0) ? ($yes_count / $total_votes) * 100 : 0;
$no_percentage = ($total_votes > 0) ? ($no_count / $total_votes) * 100 : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
                canvas {
            display: block;
            margin: 0 auto;
            width: 80vw; /* Set canvas width to 80% of the viewport width */
            height: auto; /* Automatically adjust height based on aspect ratio */
        width: 800px;
        height: 600px;
        }

        .graph-description {
            text-align: center;
            margin-top: 20px;
            bottom: 1px solid #ccc; /* Added bottom border */

        }
        .graph-description h1 {
    margin-bottom: 10px;
    font-weight: bold; /* Corrected font-weight property */
    color: #333;
    border-bottom: 1px solid #ccc; /* Added bottom border */
}
.graph-description p {
            margin-bottom: 8px;
            font-size: 16px;
            color: #555;
        }
        .go-back-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: orangered;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<button class="go-back-btn" onclick="window.location.href='quiz.php'">Go back home</button>

<body>

<div style="text-align:center; margin-top:10px; padding:10px;">
<h2>Question  statistics</h2>

<canvas id="myChart" width="400" height="100"></canvas>
    </div>

    <!-- Graph description -->
    <div class="graph-description">
        <h1>Question Statistics</h1>
        <p><strong>Question:</strong> <?= $questionText ?></p>
        <p><strong>Total Votes:</strong> <?= $total_votes ?></p>
        <p><strong>Yes Votes:</strong> <?= $yes_count ?> (<?= number_format($yes_percentage, 2) ?>%)</p>
        <p><strong>No Votes:</strong> <?= $no_count ?> (<?= number_format($no_percentage, 2) ?>%)</p>
    </div>

    <script>
        // JavaScript code to generate the graph using Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Yes', 'No'],
                datasets: [{
                    label: 'Percentage Votes',
                    data: [<?= $yes_percentage ?>, <?= $no_percentage ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)'
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
                                return value + '%';
                            }
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
