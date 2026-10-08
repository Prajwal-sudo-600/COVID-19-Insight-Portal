import re
import os

# 1. Update footers
with open('index.html', 'r') as f:
    index_html = f.read()

footer_match = re.search(r'<footer>.*?</footer>', index_html, re.DOTALL)
if footer_match:
    good_footer = footer_match.group(0)
    
    files_to_update = ['data.php', 'comments.php', 'login.php', 'signup.php', 'register.php']
    for filename in files_to_update:
        if os.path.exists(filename):
            with open(filename, 'r') as f:
                content = f.read()
            
            # Find the old footer and replace
            new_content = re.sub(r'<footer>.*?</footer>', good_footer, content, flags=re.DOTALL)
            
            if new_content != content:
                with open(filename, 'w') as f:
                    f.write(new_content)
                print(f"Updated footer in {filename}")

# 2. Update CSS for center form and shadow
with open('css/style.css', 'r') as f:
    css = f.read()

# Add margin: 0 auto and box-shadow to form
css = re.sub(r'(form\s*\{\s*background:\s*var\(--paper\);\s*border:\s*1px\s*solid\s*var\(--line\);\s*border-radius:\s*4px;\s*padding:\s*1\.75rem;\s*max-width:\s*640px;)', 
             r'\1\n    margin: 0 auto;\n    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);', 
             css)

# Add box-shadow to other boxes
boxes = ['.panel', '.chart-box', '.table-wrap', '.comment', '.snapshot', '.service-primary', '.helpline']
for box in boxes:
    css = re.sub(r'(' + re.escape(box) + r'\s*\{[^\}]+border-radius:\s*4px;)', 
                 r'\1\n    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);', 
                 css)

with open('css/style.css', 'w') as f:
    f.write(css)
print("Updated CSS")

