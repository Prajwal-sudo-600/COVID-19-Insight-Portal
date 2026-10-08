<?php
require_once 'php/db.php';
require_once 'php/validate.php';

session_start();
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
            <h1>Vaccination Registration Form</h1>
        <?= $message ?>
        </div>
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
