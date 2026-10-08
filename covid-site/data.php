<?php
require_once 'php/db.php';

session_start();

// Fetch COVID data
$stmt = $pdo->query("SELECT * FROM covid_data ORDER BY date DESC, total_cases DESC");
$data = $stmt->fetchAll();

// Get unique countries for filter
$countries = array_unique(array_column($data, 'country'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>COVID-19 Data</title>
    <link rel="stylesheet" href="css/style.css">
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
        <h1>COVID-19 Statistics</h1>
        
        <div style="margin-bottom: 20px;">
            <input type="text" id="tableSearch" placeholder="Search table..." style="width:300px; display:inline-block;">
        </div>

        <table id="dataTable">
            <thead>
                <tr>
                    <th>Country</th>
                    <th>Date</th>
                    <th>New Cases</th>
                    <th>Total Cases</th>
                    <th>New Deaths</th>
                    <th>Total Deaths</th>
                    <th>Vaccinations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= number_format($row['new_cases']) ?></td>
                    <td><?= number_format($row['total_cases']) ?></td>
                    <td><?= number_format($row['new_deaths']) ?></td>
                    <td><?= number_format($row['total_deaths']) ?></td>
                    <td><?= number_format($row['total_vaccinations']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Global Cases Chart</h2>
        <canvas id="barChart" width="800" height="400" style="background:#fff; border:1px solid #ccc; margin-top:20px;"></canvas>

        <script>
            // Simple bar chart using HTML5 Canvas
            document.addEventListener('DOMContentLoaded', () => {
                const canvas = document.getElementById('barChart');
                if(!canvas) return;
                const ctx = canvas.getContext('2d');
                
                const data = <?= json_encode($data) ?>;
                if(data.length === 0) return;

                // Group by country and get max total_cases
                const countryTotals = {};
                data.forEach(row => {
                    if(!countryTotals[row.country] || row.total_cases > countryTotals[row.country]) {
                        countryTotals[row.country] = parseInt(row.total_cases, 10);
                    }
                });

                const labels = Object.keys(countryTotals);
                const values = Object.values(countryTotals);
                const maxVal = Math.max(...values);

                const width = canvas.width;
                const height = canvas.height;
                const padding = 50;
                
                const barWidth = (width - padding * 2) / labels.length - 10;
                
                ctx.fillStyle = '#333';
                ctx.font = '14px Arial';
                
                labels.forEach((label, i) => {
                    const val = values[i];
                    const barHeight = (val / maxVal) * (height - padding * 2);
                    const x = padding + i * (barWidth + 10);
                    const y = height - padding - barHeight;
                    
                    // Draw bar
                    ctx.fillStyle = '#0056b3';
                    ctx.fillRect(x, y, barWidth, barHeight);
                    
                    // Draw label
                    ctx.fillStyle = '#333';
                    ctx.fillText(label, x, height - padding + 20);
                    
                    // Draw value
                    ctx.fillText((val / 1000000).toFixed(1) + 'M', x, y - 5);
                });
            });
        </script>
    </main>

    <footer>
        <p>&copy; 2026 COVID-19 Information Website</p>
    </footer>
</body>
</html>
