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
}
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
            background-color: white;
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
            width: 1200px;
            margin: 5px auto;
            padding: 5px;
            display: flex;
            border:1px solid #ccc;
            flex-wrap: wrap;
            justify-content: space-between;
            background-color:white;
        }

        section {
            flex-basis: calc(50% - 10px);
            margin-bottom: 20px;
            padding: 5px;
            background-color: #fff;
            border-radius: 8px;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
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
    border:2px solid darkblue;
    width: 1200px;
    margin: 5px auto;
    background-color:white;

  }

  .card {
    border: 2px solid darkblue;
    border-radius: 5px;
    padding: 5px;
    margin: auto; 
    width: 200px;
    height: 90px; 
    display: inline-block;
    text-align:center;
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
/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px;
    text-align: left;
    border: 1px solid #dddddd;
}

th {
    background-color: #f2f2f2;
    color: #333;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f2f2f2;
}
.icon {
    color: red; /* Red color for X */
}

.tick {
    color: green; /* Blue color for tick */
}


    </style>
</head>
<body>
<header>
    <h1>Welcome to the Admin Panel</h1>
    <button id="logout-btn">Logout</button>
</header>
<div class="container">
    
<div style="text-align:center; margin-top:1px; padding:10px;">
<h2>Latest question statistics: <span style="color: black; font-size: small;">( <?php echo $latest_question_text; ?>)</span></h2>

    <h3> Latest votes count</h3>
    <div class="card">
    <h2>Yes <span class="tick">✔</span></h2>
    <p>Count: <span id="yesCount"><?php echo $yes_count; ?></span></p>
</div>

<div class="card">
    <h2>No <span class="icon">✖</span></h2>
    <p>Count: <span id="noCount"><?php echo $no_count; ?></span></p>
</div>


    <canvas id="voteChart" width="400" height="100"></canvas>
</div>
</div>
<main>
    <section>
        <h2>Registered Users</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once "db.php";

                $sql = "SELECT email, status FROM Users WHERE type='user' LIMIT 2";
                $result = execute_query($sql);

                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $statusClass = ($row['status'] === 'approved') ? 'approved' : 'pending';
                        echo "<tr>";
                        echo "<td>{$counter}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td class='{$statusClass}'>{$row['status']}</td>";
                        echo "</tr>";
                        $counter++; 
                    }
                } else {
                    echo "<tr><td colspan='3'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
        <h2>Recent Questions and Votes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once "db.php";

                // Retrieve recent questions from the Questions table based on date_created
                $sql = "SELECT ID AS QuestionID, QuestionText FROM Questions ORDER BY date_added DESC LIMIT 2";
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

                        // Calculate total votes
                        $total_votes = $yes_count + $no_count;

                        echo "<tr>";
                        echo "<td>" . $row['QuestionID'] . "</td>";
                        echo "<td>" . $row['QuestionText'] . "</td>";
                        echo "<td>$total_votes</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No questions found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <p><a href="quiz.php">View More <i class="fas fa-arrow-right" style="color: green;"></i></a></p>
    </section>
</main>

<footer>
    <p>ONLINE QUIZ.</p>
</footer>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.getElementById("logout-btn").addEventListener("click", function() {
        window.location.href = "logout.php";
    });

    // Chart.js code
    var ctx = document.getElementById('voteChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Yes', 'No'],
            datasets: [{
                label: 'Percentage of Votes',
                data: [<?php echo $yesPercentage; ?>, <?php echo $noPercentage; ?>],
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