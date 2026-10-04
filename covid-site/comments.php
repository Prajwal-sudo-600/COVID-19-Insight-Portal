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
    <header>
        <nav>
            <a href="index.html">Home</a>
            <a href="data.php">Data</a>
            <a href="about.html">About</a>
            <a href="comments.php">Comments</a>
            <a href="register.php">Register</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h1>Discussion</h1>
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

        <h2>Recent Comments</h2>
        <?php foreach($comments as $c): ?>
            <div style="background:#fff; padding:15px; margin-bottom:10px; border-radius:5px; box-shadow:0 0 5px rgba(0,0,0,0.1);">
                <strong><?= htmlspecialchars($c['username']) ?></strong> <small>(<?= htmlspecialchars($c['created_at']) ?>)</small>
                <p><?= htmlspecialchars($c['comment_text']) ?></p>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; 2026 COVID-19 Information Website</p>
    </footer>
</body>
</html>
