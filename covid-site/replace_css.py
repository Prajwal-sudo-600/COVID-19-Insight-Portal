import re

with open('css/style.css', 'r') as f:
    css = f.read()

# Replace pill-nav block
new_css = re.sub(r'/\* ---------- Pill Navigation ---------- \*/.*', '', css, flags=re.DOTALL)

append_css = """/* ---------- Pill Navigation ---------- */
body {
    padding-top: 80px;
}

.pill-nav {
    position: fixed;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    background-color: var(--teal-deep);
    padding: 6px 6px 6px 12px;
    border-radius: 40px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    z-index: 1000;
    display: flex;
    align-items: center;
}

.pill-nav-inner {
    display: flex;
    align-items: center;
    gap: 15px;
}

.pill-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    text-decoration: none;
    transition: transform 0.2s;
}

.pill-logo:hover {
    transform: scale(1.05);
}

.pill-logo svg {
    width: 36px;
    height: 36px;
}

.pill-links {
    display: flex;
    gap: 5px;
    position: relative;
}

.pill-slider {
    position: absolute;
    background-color: var(--paper);
    border-radius: 20px;
    transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    z-index: 0;
    pointer-events: none;
    opacity: 0;
}

.pill-links a.nav-item {
    position: relative;
    z-index: 1;
    color: var(--paper);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    transition: color 0.3s;
}

@media (max-width: 820px) {
    .pill-nav {
        width: 95%;
        padding: 4px;
        overflow-x: auto;
    }
    .pill-nav-inner {
        gap: 8px;
    }
    .pill-links a.nav-item {
        padding: 5px 10px;
        font-size: 0.8rem;
    }
}
"""

with open('css/style.css', 'w') as f:
    f.write(new_css + append_css)
