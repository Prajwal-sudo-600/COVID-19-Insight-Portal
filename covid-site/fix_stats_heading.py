import re

with open('data.php', 'r') as f:
    content = f.read()

# Replace the h1 with the page-head structure
content = re.sub(
    r'(<main>)\s*<h1>COVID-19 Statistics</h1>',
    r'<main id="main">\n        <div class="page-head">\n            <div class="wrap">\n                <h1>COVID-19 Statistics</h1>\n                <p>Search, sort and compare cases, deaths and vaccinations by country.</p>\n            </div>\n        </div>\n        <div class="content">',
    content
)

# Also close the .content div before </main>
content = re.sub(
    r'(</main>)',
    r'    </div>\n    \1',
    content
)

with open('data.php', 'w') as f:
    f.write(content)
    
