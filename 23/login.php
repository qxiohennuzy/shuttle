<?php
session_start(); // Start the session

// Database configuration
$host = 'localhost'; // Database host
$db = 'tmo_shuttle_services'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password (default for XAMPP/MAMP is often an empty string)

// Establish the connection using PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Initialize error message
$errorMessage = '';

// Handle login
if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $row['password'];
        $role = $row['role'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username; // Store the username

            // Redirect based on role
            if ($role === 'admin') {
                header('Location: index.php'); // Redirect to admin dashboard
                exit;
            } elseif ($role === 'staff') {
                header('Location: staff_homepage.php'); // Redirect to staff homepage
                exit;
            }
        } else {
            $errorMessage = 'Invalid password.'; // Update error message
        }
    } else {
        $errorMessage = 'No user found with this username.'; // Update error message for username
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        /* Background Styles */
        body {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            min-height: 100vh; /* Full viewport height */
            font-family: Arial, sans-serif;
            margin: 0; /* Ensure no default margin */
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%); /* Colorful gradient */
        }
        .login-container {
            max-width: 400px; /* Max width for the form */
            width: 100%; /* Full width responsive */
            padding: 25px;
            border: none;
            border-radius: 10px; /* Slightly larger border radius */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Shadow effect */
            background-color: white; /* Form background color */
            display: flex;
            flex-direction: column; /* Stack items vertically */
            align-items: center; /* Center items horizontally */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px; /* Add space below the title */
            color: #333; /* Darker text for the title */
            font-weight: 600; /* Bold font weight */
        }
        input,
        button {
            width: 100%; /* Full width of the form */
            padding: 12px; /* Increased padding */
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Padding included in width */
            font-size: 14px; /* Increased font size */
            transition: border-color 0.3s; /* Smooth transition */
        }
        input:focus {
            border-color: #007BFF; /* Change border color on focus */
            outline: none; /* Remove outline */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Subtle shadow */
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold; /* Bolder button text */
        }
        button:hover {
            background-color: #0056b3;
        }
        .register-link {
            text-align: center;
            margin: 10px 0;
            font-size: 14px; /* Font size for register link */
        }
        .register-link a {
            color: #007BFF;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Error message */
        .error {
            color: red;
            margin-bottom: 15px; /* Space below error message */
            text-align: center; /* Center align */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <form method="post" action="">
            <?php if ($errorMessage): ?>
                <div class="error"><?= $errorMessage; ?></div>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>