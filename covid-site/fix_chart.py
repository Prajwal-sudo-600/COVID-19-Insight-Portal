import re

# 1. Update data.php to use .chart-box
with open('data.php', 'r') as f:
    data_content = f.read()

data_content = data_content.replace(
    '<canvas id="barChart" width="800" height="400" style="background:#fff; border:1px solid #ccc; margin-top:20px;"></canvas>',
    '<div class="chart-box" style="margin: 20px auto; max-width: 800px;">\n            <canvas id="barChart" width="800" height="400"></canvas>\n        </div>'
)

with open('data.php', 'w') as f:
    f.write(data_content)

# 2. Update css/style.css if needed, but adding inline margin auto to chart-box is enough
