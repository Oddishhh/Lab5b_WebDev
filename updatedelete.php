<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the matric number from the query parameter
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Fetch user details
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
    $stmt->close();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = $_POST['name'];
    $newAccessLevel = $_POST['accessLevel'];

    $updateSql = "UPDATE users SET name = ?, accessLevel = ? WHERE matric = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sss", $newName, $newAccessLevel, $matric);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully.');</script>";
        header("Location: users.php");
        exit();
    } else {
        echo "<script>alert('Error updating user.');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Page heading */
        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        /* Form container */
        form {
            width: 90%;
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form labels */
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        /* Input fields */
        input[type="text"], select {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Buttons */
        button, a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }

        button:hover, a:hover {
            background-color: #0056b3;
        }

        a {
            background-color: #6c757d;
        }

        a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h2>Update User</h2>
    <form method="POST" action="">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" readonly>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="accessLevel">Access Level:</label>
        <select id="accessLevel" name="accessLevel" required>
            <option value="lecturer" <?php echo ($user['accessLevel'] === 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
            <option value="student" <?php echo ($user['accessLevel'] === 'student') ? 'selected' : ''; ?>>Student</option>
        </select>

        <button type="submit">Update</button>
        <a href="users.php">Cancel</a>
    </form>
</body>
</html>
