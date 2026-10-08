<?php
require_once 'php/db.php';

echo "<h3>Users table</h3><pre>";
$rows = $pdo->query("SELECT id, username, email FROM users")->fetchAll();
print_r($rows);
echo "</pre>";

echo "<h3>Registrations table</h3><pre>";
$rows = $pdo->query("SELECT id, email, vaccination_status FROM registrations")->fetchAll();
print_r($rows);
echo "</pre>";

echo "<h3>Email match check</h3><pre>";
$rows = $pdo->query("
    SELECT u.username, u.email AS user_email, r.email AS reg_email, r.vaccination_status
    FROM users u
    LEFT JOIN registrations r ON LOWER(TRIM(r.email)) = LOWER(TRIM(u.email))
")->fetchAll();
print_r($rows);
echo "</pre>";
