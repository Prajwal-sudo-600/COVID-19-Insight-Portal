import re

files = ['login.php', 'register.php', 'signup.php']

for filename in files:
    with open(filename, 'r') as f:
        content = f.read()

    # Wrap the h1 and $message in a centered div
    content = re.sub(r'(<h1>.*?</h1>\s*<\?= \$message \?>)', 
                     r'<div class="form-header">\n            \1\n        </div>', 
                     content)

    with open(filename, 'w') as f:
        f.write(content)
        print(f"Updated {filename}")

# Add CSS for .form-header
with open('css/style.css', 'a') as f:
    f.write("\n.form-header {\n    text-align: center;\n    margin-bottom: 1.5rem;\n}\n.form-header h1 {\n    font-family: var(--serif);\n    font-size: clamp(1.8rem, 4vw, 2.5rem);\n    margin: 0 0 0.5rem 0;\n}\n.form-header p {\n    margin: 0;\n}\n")
