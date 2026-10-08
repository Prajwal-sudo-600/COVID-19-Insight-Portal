<?php
/**
 * Profile widget partial — include AFTER session_start() and db.php
 * Outputs the profile avatar button + dropdown when a user is logged in.
 */
if (!isset($_SESSION['user_id'])) return;

// Fetch full user record
$_stmt = $pdo->prepare("SELECT full_name, username, email FROM users WHERE id = ?");
$_stmt->execute([$_SESSION['user_id']]);
$_profile_user = $_stmt->fetch();

// Fetch vaccination registration (most recent entry matching the user's email)
$_vstmt = $pdo->prepare(
    "SELECT vaccination_status FROM registrations WHERE email = ? ORDER BY created_at DESC LIMIT 1"
);
$_vstmt->execute([$_profile_user['email']]);
$_vacc = $_vstmt->fetch();
$_vacc_status = $_vacc ? $_vacc['vaccination_status'] : 'Not Registered';

// Build initials avatar
$_initials = strtoupper(substr($_profile_user['full_name'] ?? $_profile_user['username'], 0, 1));
$_second = explode(' ', trim($_profile_user['full_name'] ?? ''));
if (count($_second) > 1) $_initials .= strtoupper(substr(end($_second), 0, 1));

// Badge colour
$_badge_class = match($_vacc_status) {
    'Full'    => 'vacc-full',
    'Partial' => 'vacc-partial',
    default   => 'vacc-none',
};
?>
<div class="profile-widget" id="profileWidget">
    <button class="profile-avatar" id="profileBtn" aria-haspopup="true" aria-expanded="false" aria-label="User profile">
        <?= htmlspecialchars($_initials) ?>
    </button>
    <div class="profile-dropdown" id="profileDropdown" role="dialog" aria-label="User profile panel">
        <div class="profile-dropdown-header">
            <div class="profile-avatar-large"><?= htmlspecialchars($_initials) ?></div>
            <div>
                <div class="profile-fullname"><?= htmlspecialchars($_profile_user['full_name']) ?></div>
                <div class="profile-username">@<?= htmlspecialchars($_profile_user['username']) ?></div>
            </div>
        </div>
        <div class="profile-dropdown-body">
            <div class="profile-row">
                <span class="profile-label">Email</span>
                <span class="profile-value"><?= htmlspecialchars($_profile_user['email']) ?></span>
            </div>
            <div class="profile-row">
                <span class="profile-label">Vaccination</span>
                <span class="vacc-badge <?= $_badge_class ?>"><?= htmlspecialchars($_vacc_status) ?></span>
            </div>
        </div>
        <div class="profile-dropdown-footer">
            <a href="logout.php" class="profile-logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </a>
        </div>
    </div>
</div>
<script>
(function(){
    const btn = document.getElementById('profileBtn');
    const dropdown = document.getElementById('profileDropdown');
    if (!btn || !dropdown) return;
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        btn.setAttribute('aria-expanded', isOpen);
    });
    document.addEventListener('click', (e) => {
        if (!document.getElementById('profileWidget').contains(e.target)) {
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>
