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
    <nav class="pill-nav">
        <div class="pill-nav-inner">
            <a href="index.html" class="pill-logo" aria-label="Home">
                <svg viewBox="0 0 48 48" aria-hidden="true">
                    <rect width="48" height="48" rx="6" fill="#0d5c6e" />
                    <circle cx="24" cy="24" r="9" fill="none" stroke="#fff" stroke-width="3" />
                    <path d="M24 7v8M24 33v8M7 24h8M33 24h8" stroke="#e9a82f" stroke-width="3" stroke-linecap="round" />
                </svg>
            </a>
            <div class="pill-links" id="pill-links">
                <div class="pill-slider" id="pill-slider"></div>
                <a href="index.html" class="nav-item">Home</a>
                <a href="data.php" class="nav-item">Statistics</a>
                <a href="about.html" class="nav-item">About</a>
                <a href="comments.php" class="nav-item">Discussion</a>
                <a href="register.php" class="nav-item">Vaccination</a>
                <a href="login.php" class="nav-item">Login / Signup</a>
            </div>
            <?php require_once 'php/profile_widget.php'; ?>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('.nav-item');
            const slider = document.getElementById('pill-slider');
            const container = document.getElementById('pill-links');
            
            let activeLink = null;
            
            // Highlight active page
            const currentPath = window.location.pathname.split('/').pop() || 'index.html';
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath) {
                    link.classList.add('active');
                    activeLink = link;
                }
            });
            
            function moveSlider(el) {
                if (!el) return;
                const rect = el.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();
                
                slider.style.width = `${rect.width}px`;
                slider.style.height = `${rect.height}px`;
                slider.style.left = `${rect.left - containerRect.left}px`;
                slider.style.top = `${rect.top - containerRect.top}px`;
                slider.style.opacity = '1';
            }
            
            if (activeLink) {
                setTimeout(() => moveSlider(activeLink), 100);
            }
            
            links.forEach(link => {
                link.addEventListener('mouseenter', (e) => {
                    moveSlider(e.target);
                    // Make sure text color changes to contrast with slider
                    links.forEach(l => l.style.color = 'var(--paper)');
                    e.target.style.color = 'var(--teal-deep)';
                });
            });
            
            container.addEventListener('mouseleave', () => {
                if (activeLink) {
                    moveSlider(activeLink);
                    links.forEach(l => l.style.color = 'var(--paper)');
                    activeLink.style.color = 'var(--teal-deep)';
                } else {
                    slider.style.opacity = '0';
                    links.forEach(l => l.style.color = 'var(--paper)');
                }
            });

            // Initial color set
            links.forEach(l => l.style.color = 'var(--paper)');
            if(activeLink) activeLink.style.color = 'var(--teal-deep)';
        });
    </script>

    <main>
        <div class="form-header">
            <h1>Sign Up for an Account</h1>
        <?= $message ?>
        </div>
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
        <div class="wrap">
            <div>
                <h3>COVID-19 Insight Portal</h3>
                <p>Figures, prevention guidance and vaccination registration in one place. Figures come from the portal
                    database and are not a substitute for medical advice.</p>
            </div>
            <div>
                <h3>Pages</h3>
                <ul>
                    <li><a href="data.php">Statistics</a></li>
                    <li><a href="about.html">Symptoms and prevention</a></li>
                    <li><a href="comments.php">Discussion</a></li>
                    <li><a href="register.php">Vaccination registration</a></li>
                </ul>
            </div>
            <div>
                <h3>Get help</h3>
                <ul>
                    <li>National helpline: <strong>1075</strong></li>
                    <li>Emergency: <strong>112</strong></li>
                </ul>
            </div>
        </div>
        <div class="wrap legal">&copy; 2026 COVID-19 Insight Portal</div>
    </footer>
</body>
</html>
