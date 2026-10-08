<?php
require_once 'php/db.php';

session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: comments.php");
        exit;
    } else {
        $message = "<p style='color:red'>Invalid username or password.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
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
            <h1>Login</h1>
        <?= $message ?>
        </div>
        <form action="login.php" method="POST">
            <div>
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
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
