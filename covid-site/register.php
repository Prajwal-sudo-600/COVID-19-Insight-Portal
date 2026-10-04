<?php
require_once 'php/db.php';
require_once 'php/validate.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $vaccination_status = $_POST['vaccination_status'] ?? 'None';

    if (validateField('fullName', $full_name) && 
        validateField('email', $email) && 
        validateField('phone', $phone) && 
        validateField('age', $age) && 
        validateField('city', $city) &&
        in_array($vaccination_status, ['None', 'Partial', 'Full'])) {
        
        try {
            $stmt = $pdo->prepare("INSERT INTO registrations (full_name, email, phone, age, city, vaccination_status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$full_name, $email, $phone, $age, $city, $vaccination_status]);
            $message = "<p style='color:green'>Registration successful!</p>";
        } catch(PDOException $e) {
            $message = "<p style='color:red'>Database error occurred.</p>";
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
    <title>Vaccine Registration</title>
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
        <h1>Vaccination Registration Form</h1>
        <?= $message ?>
        <form action="register.php" method="POST">
            <div>
                <label>Full Name</label>
                <input type="text" name="fullName" id="fullName" data-validate="fullName" required>
                <span id="fullName-error" class="error">Invalid Full Name</span>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" id="email" data-validate="email" required>
                <span id="email-error" class="error">Invalid Email</span>
            </div>
            <div>
                <label>Phone Number</label>
                <input type="text" name="phone" id="phone" data-validate="phone" required>
                <span id="phone-error" class="error">Invalid Indian Phone Number (10 digits starting with 6-9)</span>
            </div>
            <div>
                <label>Age</label>
                <input type="number" name="age" id="age" data-validate="age" required>
                <span id="age-error" class="error">Invalid Age (1-120)</span>
            </div>
            <div>
                <label>City</label>
                <input type="text" name="city" id="city" data-validate="city" required>
                <span id="city-error" class="error">Invalid City</span>
            </div>
            <div>
                <label>Vaccination Status</label>
                <select name="vaccination_status" required>
                    <option value="None">None</option>
                    <option value="Partial">Partial</option>
                    <option value="Full">Full</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2026 COVID-19 Information Website</p>
    </footer>
</body>
</html>
