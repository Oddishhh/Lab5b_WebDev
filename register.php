<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root"; // Replace with your DB username
    $password = ""; // Replace with your DB password
    $dbname = "lab_5b"; // Database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form inputs
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // SQL query to insert data
    $sql = "INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $matric, $name, $hashedPassword, $role);

    // Execute and check result
    if ($stmt->execute()) {
        // After successful registration, show success page
        $success = true;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Shared container styles for form and success message */
        .form-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Heading styles */
        .form-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #007BFF;
        }

        /* Paragraph for success message */
        .form-container p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Form labels */
        label {
            display: block;
            text-align: left;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        /* Form inputs */
        input[type="text"],
        input[type="password"],
        select {
            width: 90%;
            padding: 0.8rem;
            margin-bottom: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        /* Button styles */
        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #007BFF;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Footer link */
        .footer {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (isset($success) && $success): ?>
        <!-- Success message -->
        <div class="form-container">
            <h2>Registration Successful!</h2>
            <p>Your account has been created successfully. Click below to login.</p>
            <button onclick="window.location.href='login.php';">Okay</button>
        </div>
    <?php else: ?>
        <!-- Registration form -->
        <div class="form-container">
            <h2>Register</h2>
            <form method="POST" action="">
                <label for="matric">Matric:</label>
                <input type="text" id="matric" name="matric" required>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="">Please select</option>
                    <option value="lecturer">Lecturer</option>
                    <option value="student">Student</option>
                </select>

                <button type="submit">Register</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
