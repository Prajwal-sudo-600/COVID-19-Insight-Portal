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

    <main id="main">
        <div class="page-head">
            <div class="wrap">
                <h1>COVID-19 Statistics</h1>
                <p>Search, sort and compare cases, deaths and vaccinations by country.</p>
            </div>
        </div>
        <div class="content">
        
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
        
        <h2 style="text-align: center;">Global Cases Chart</h2>
        <div class="chart-box" style="margin: 20px auto; max-width: 800px;">
            <canvas id="barChart" width="800" height="400"></canvas>
        </div>

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
