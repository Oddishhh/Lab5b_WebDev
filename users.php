<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete'])) {
    $matric = $_GET['delete'];
    $deleteSql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("s", $matric);
    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting user.');</script>";
    }
    $stmt->close();
    header("Location: users.php"); // Redirect back to the page
    exit();
}

// Fetch data from the `users` table
$sql = "SELECT matric, name, accessLevel FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Page heading */
        h2 {
            color: #007BFF;
            margin: 20px 0;
        }

        /* Table styling */
        table {
            width: 90%;
            max-width: 800px;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Action links */
        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        /* No data row */
        td[colspan] {
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Level</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Display each row in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['matric']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['accessLevel']) . "</td>";
                    echo "<td>";
                    echo "<a href='update.php?matric=" . urlencode($row['matric']) . "'>Update</a> | ";
                    echo "<a href='users.php?delete=" . urlencode($row['matric']) . "' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
