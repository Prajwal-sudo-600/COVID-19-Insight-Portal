<?php
require_once 'php/db.php';
require_once 'php/validate.php';

session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullName'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (validateField('fullName', $full_name) && 
        validateField('username', $username) && 
        validateField('email', $email) && 
        validateField('password', $password)) {
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->execute([$full_name, $username, $email, $hash]);
            $message = "<p style='color:green'>Sign up successful! You can now login.</p>";
        } catch(PDOException $e) {
            $message = "<p style='color:red'>Error: Username or Email may already exist.</p>";
        }
    } else {
        $message = "<p style='color:red'>Validation failed. Please check your inputs.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/validation.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
    <header>
        <nav>
            <a href="index.html">Home</a>
            <a href="data.php">Data</a>
            <a href="about.html">About</a>
            <a href="comments.php">Comments</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <main>
        <h1>Sign Up for an Account</h1>
        <?= $message ?>
        <form action="signup.php" method="POST">
            <div>
                <label>Full Name</label>
                <input type="text" name="fullName" id="fullName" data-validate="fullName" required>
                <span id="fullName-error" class="error">Invalid Full Name</span>
            </div>
            <div>
                <label>Username</label>
                <input type="text" name="username" id="username" data-validate="username" required>
                <span id="username-error" class="error">Invalid Username (4-16 chars, letters/nums/underscores)</span>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" id="email" data-validate="email" required>
                <span id="email-error" class="error">Invalid Email</span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" id="password" data-validate="password" required>
                <span id="password-error" class="error">Password must be at least 8 chars, 1 upper, 1 lower, 1 digit, 1 special</span>
            </div>
            <button type="submit">Sign Up</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </main>

    <footer>
        <p>&copy; 2023 COVID-19 Information Website</p>
    </footer>
</body>
</html>
