<?php
require_once 'php/db.php';
require_once 'php/validate.php';

session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $comment_text = trim($_POST['comment'] ?? '');

    if (validateField('comment', $comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (user_id, comment_text) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $comment_text]);
            $message = "<p style='color:green'>Comment posted successfully!</p>";
        } catch(PDOException $e) {
            $message = "<p style='color:red'>Database error occurred.</p>";
        }
    } else {
        $message = "<p style='color:red'>Invalid comment. Ensure it is 1-500 characters and contains no HTML tags.</p>";
    }
}

// Fetch comments
$stmt = $pdo->query("SELECT comments.comment_text, comments.created_at, users.username FROM comments JOIN users ON comments.user_id = users.id ORDER BY comments.created_at DESC");
$comments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comments</title>
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
        <div class="page-head" style="text-align: center;">
            <div class="wrap">
                <h1>Discussion</h1>
            </div>
        </div>
        <?= $message ?>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <form action="comments.php" method="POST" style="margin-bottom:30px;">
                <div>
                    <label>Post a Comment</label>
                    <textarea name="comment" id="comment" data-validate="comment" rows="4" required></textarea>
                    <span id="comment-error" class="error">Comment must be 1-500 chars and have no HTML tags</span>
                </div>
                <button type="submit">Post Comment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to post a comment.</p>
        <?php endif; ?>

        <div style="max-width:560px; margin:0 auto;">
            <h2 style="text-align:center; font-family:var(--serif);">Recent Comments</h2>
            <?php foreach($comments as $c): ?>
                <div style="background:#fff; padding:10px 16px; margin-bottom:8px; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.08); font-size:0.88rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                        <strong style="font-size:0.85rem;"><?= htmlspecialchars($c['username']) ?></strong>
                        <small style="color:#888; font-size:0.75rem;"><?= htmlspecialchars($c['created_at']) ?></small>
                    </div>
                    <p style="margin:0; line-height:1.4;"><?= htmlspecialchars($c['comment_text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
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
