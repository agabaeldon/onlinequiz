<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
            background-color: darkblue;
            color: #fff;
            padding: 5px 0;
            text-align: center;
        }
        #back-home-btn {
    
    background-color: orangered;
font-style:bold;
    float:right;
    color: white;
    border: none;
    border-radius: 4px;
}

#back-home-btn:hover {
    background-color: #0056b3;
}

        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 8px 12px; /* Adjusted padding */
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: red;
        }
    </style>
</head>
<body>
<header>
    <h1>User Management</h1>
    <a href="admindashboard.php" id="back-home-btn" class="btn">Back Home</a>
</header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once "db.php";

                $sql = "SELECT * FROM Users WHERE type='user'";
                $result = execute_query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>";
                        echo '<div class="btn-container">';
                        if ($row['status'] === "pending") {
                            echo '<form action="approve_user.php" method="POST">';
                            echo '<input type="hidden" name="user_id" value="' . $row['id'] . '">';
                            echo '<button class="btn" type="submit">Approve</button>';
                            echo '</form>';
                        }
                        echo '<form action="#" method="POST">';
                        echo '<input type="hidden" name="user_id" value="' . $row['id'] . '">';
                        echo '<button class="btn delete-btn" type="submit">Delete</button>';
                        echo '</form>';
                        echo '</div>';
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
