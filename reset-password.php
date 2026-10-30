<?php
require_once __DIR__ . '/includes/bootstrap.php';
ensure_password_resets_table();
$token = trim((string)($_GET['token'] ?? $_POST['token'] ?? ''));
$validToken = preg_match('/^[a-f0-9]{64}$/i', $token) === 1;
$error = '';
$resetRecord = null;
if ($validToken) {
    $stmt = db()->prepare('SELECT pr.id, pr.user_id FROM password_resets pr JOIN users u ON u.id = pr.user_id WHERE pr.token_hash = ? AND pr.used_at IS NULL AND pr.expires_at > NOW() AND u.status = \'active\' LIMIT 1');
    $stmt->execute([hash('sha256', $token)]);
    $resetRecord = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    if (!$validToken || !$resetRecord) {
        $error = 'This password reset link is invalid, expired or has already been used. Request a new link.';
    } elseif (strlen($password) < 8) {
        $error = 'Your new password must contain at least 8 characters.';
    } elseif (!hash_equals($password, $confirm)) {
        $error = 'The password confirmation does not match.';
    } else {
        $pdo = db();
        $pdo->beginTransaction();
        $lock = $pdo->prepare('SELECT id, user_id FROM password_resets WHERE id = ? AND used_at IS NULL AND expires_at > NOW() FOR UPDATE');
        $lock->execute([(int)$resetRecord['id']]);
        $locked = $lock->fetch();
        if (!$locked) {
            $pdo->rollBack();
            $error = 'This password reset link is invalid, expired or has already been used. Request a new link.';
        } else {
            $pdo->prepare('UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?')->execute([password_hash($password, PASSWORD_DEFAULT), (int)$locked['user_id']]);
            $pdo->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL')->execute([(int)$locked['user_id']]);
            $pdo->commit();
            log_activity((int)$locked['user_id'], 'password_reset', 'Password reset completed using a one-time email token');
            flash('Your password has been changed. You can now log in with your new password.');
            redirect('login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | HelpingPlate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/branding.css">
</head>
<body>
<header class="hp-navbar"><div class="hp-container hp-nav-container">
    <a href="index.php" class="hp-logo"><img src="images/logo.png" alt="HelpingPlate Logo"><div class="hp-logo-text"><span>Helping<span>Plate</span></span><small>Good Food • Greater Impact</small></div></a>
    <nav id="hpNavLinks" class="hp-nav-links"><a href="index.php">Home</a><a href="about.php">About</a><a href="how-it-works.php">How It Works</a><a href="volunteer.php">Volunteer</a><a href="feedback.php">Feedback</a><a href="login.php">Login</a></nav>
    <button type="button" class="hp-menu-btn" id="menuBtn" aria-controls="hpNavLinks" aria-expanded="false" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
</div></header>
<main class="auth-page"><div class="auth-container">
    <section class="auth-intro"><span class="auth-badge"><i class="fa-solid fa-lock"></i> Secure account recovery</span><h1>Create a new <span>password.</span></h1><p>Choose a strong password you have not used on HelpingPlate before.</p></section>
    <section class="auth-card"><div class="auth-card-header"><h2>Reset your password</h2><p>Use at least 8 characters.</p></div>
        <?php if ($error): ?><div class="auth-error"><?=e($error)?></div><?php endif; ?>
        <?php if (!$resetRecord): ?><div class="hp-flash error">This reset link is invalid, expired or already used. <a href="forgot-password.php">Request another link</a>.</div>
        <?php else: ?>
        <form method="post" class="auth-form"><?=csrf_field()?>
            <input type="hidden" name="token" value="<?=e($token)?>">
            <div class="auth-field"><label for="newPassword">New password <span>*</span></label><div class="auth-input-wrapper"><i class="fa-solid fa-lock"></i><input id="newPassword" type="password" name="password" minlength="8" autocomplete="new-password" required></div></div>
            <div class="auth-field"><label for="confirmPassword">Confirm new password <span>*</span></label><div class="auth-input-wrapper"><i class="fa-solid fa-lock"></i><input id="confirmPassword" type="password" name="confirm_password" minlength="8" autocomplete="new-password" required></div></div>
            <button type="submit" class="auth-submit-btn">Update password <i class="fa-solid fa-check"></i></button>
        </form>
        <?php endif; ?>
    </section>
</div></main>
<script src="js/script.js"></script>
</body>
</html>
