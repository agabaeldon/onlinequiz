<?php
include 'db.php';

$sqlYesCount = "SELECT COUNT(*) AS yesCount FROM UserAnswers WHERE status = 'Correct'";
$sqlNoCount = "SELECT COUNT(*) AS noCount FROM UserAnswers WHERE status = 'Wrong'";

$resultYesCount = $conn->query($sqlYesCount);
$resultNoCount = $conn->query($sqlNoCount);

$rowYesCount = $resultYesCount->fetch_assoc();
$rowNoCount = $resultNoCount->fetch_assoc();

$yesCountFromDB = $rowYesCount['yesCount'];
$noCountFromDB = $rowNoCount['noCount'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Home Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: darkblue;
            color: #fff;
            padding: 5px;
            text-align: center;
        }

        header button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
        }

        header button:hover {
            background-color: #c82333;
        }

        main {
            max-width: 1200px;
            margin: 5px auto;
            padding: 5px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        section {
            flex-basis: calc(50% - 10px);
            margin-bottom: 20px;
            padding: 5px;
            background-color: #fff;
            border-radius: 8px;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            
            margin-bottom: 5px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;

            text-align: center;
        }
        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
        }

        li:last-child {
            border-bottom: none;
        }
        input[type="text"],select  {
            width: calc(70% - 10px);
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        section a {
            display: block;
            text-align: center;
            margin-top: 1px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
            font-size: 20px;
        }

        section a:hover {
            color: orangered;
        }
        footer{
            text-align: center;
        }
        .bold {
        font-weight: bold;
    }

    .approved {
        color: green;
    }

    .pending {
        color: orange;
    }
    .container {
    text-align: center;
    margin-top: 5px;
  }

  .card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px;
    margin: auto; 
    width: 200px;
    height: 120px; 
    display: inline-block;
  }
  .qr-div {
            text-align: center;
            margin-top: 5px;
        }
        .qrtitle {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .qr-code {
            width: 300px; 
            height: auto; 
            max-height: 300px; 
        }

        #voteChart {
    width: 80%; /* Adjust the width as needed */
    margin: 0 auto; /* Center the chart horizontally */
}

    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Panel</h1>
        <button id="logout-btn">Logout</button>
    </header>
    <div class="container">
        <h3> votes count</h3>
    <div class="card">
        <h2>Yes</h2>
        <p>Count: <span id="yesCount"><?php echo $yesCountFromDB; ?></span></p>
    </div>

    <div class="card">
        <h2>No</h2>
        <p>Count: <span id="noCount"><?php echo $noCountFromDB; ?></span></p>
    </div>
</div>
<div style="text-align:center; margin-top:10px; padding:10px;">
<h2>Graph Information</h2>

<canvas id="voteChart" width="400" height="100"></canvas>
    </div>
    <main>
    
    <section>
    <h2>Registered Users</h2>
    <ul id="user-list">
        <?php
        include_once "db.php";

        $sql = "SELECT email, status FROM Users WHERE type='user' LIMIT 2";
        $result = execute_query($sql);

        $counter = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = ($row['status'] === 'approved') ? 'approved' : 'pending';
                echo "<li>{$counter}. <span class='bold'>{$row['email']}</span> - <span class='{$statusClass}'>{$row['status']}</span></li>";
                $counter++; 
            }
        } else {
            echo "<li>No users found.</li>";
        }
        ?>
    </ul>
    <p><a href="users.php">View More <i class="fas fa-arrow-right" style="color: green;"></i></a></p>
</section>

<section>
    <h2>Add New Question</h2>
    <form id="question-form" action="add_question.php" method="POST">
        <input type="text" id="questionText" name="questionText" placeholder="Enter question text" required><br>
        <label for="correctAnswer">Select Correct Answer:</label>
        <select id="correctAnswer" name="correctAnswer">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        <button type="submit">Add Question</button>
    </form>
</section>
<section>
    <h2>Recent Questions and Answers</h2>
    <ul id="answer-list">
        <?php
        include_once "db.php";

        $sql = "SELECT * FROM Questions ORDER BY ID DESC LIMIT 2";
        $result = $conn->query($sql);

        $questionNumber = 1;

        if ($result->num_rows > 0) {
            // Loop through each question
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $questionNumber . ". " . $row['QuestionText'] . " - " . $row['CorrectAnswer'] . "</li>";
                $questionNumber++;
            }
        } else {
            echo "<li>No questions found.</li>";
        }

        for ($i = $questionNumber; $i <= 2; $i++) {
            echo "<li>2. No question available.</li>";
        }
        ?>
    </ul>
    <p><a href="quiz.php">View More <i class="fas fa-arrow-right" style="color: green"></i></a></p>
</section>
    </main>

    <footer>
        <p>ONLINE QUIZ.</p>
    </footer>

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>   document.getElementById("logout-btn").addEventListener("click", function() {
    window.location.href = "logout.php";
  });
</script>
<script>
    // Retrieve vote counts from PHP variables
    var yesCount = <?php echo $yesCountFromDB; ?>;
    var noCount = <?php echo $noCountFromDB; ?>;
    
    // Calculate total count
    var totalCount = yesCount + noCount;

    // Calculate percentages
    var yesPercentage = (yesCount / totalCount) * 100;
    var noPercentage = (noCount / totalCount) * 100;

    // Get the canvas element
    var ctx = document.getElementById('voteChart').getContext('2d');

    // Create the chart
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Yes', 'No'],
            datasets: [{
                label: 'Vote Percentages',
                data: [yesPercentage.toFixed(2), noPercentage.toFixed(2)],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)', // Blue color for Yes
                    'rgba(255, 99, 132, 0.5)', // Red color for No
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
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
                            return value + '%'; // Add percentage sign to y-axis ticks
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
                        var voteCount = Math.round((currentValue / 100) * totalCount);
                        return voteCount + ' votes';
                    }
                }
            }
        }
    });
</script>
