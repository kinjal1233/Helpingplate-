<?php
require_once __DIR__ . '/includes/bootstrap.php';
ensure_password_resets_table();
$error = '';
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $error = 'Enter a valid email address.';
    } else {
        $pdo = db();
        // Return the same message whether or not the address belongs to an account.
        $notice = 'If an active HelpingPlate account uses that email address, a password reset link will be sent. The link expires in 60 minutes.';
        $ip = substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
        $rate = $pdo->prepare('SELECT COUNT(*) FROM password_resets WHERE requested_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) AND (request_ip = ? OR user_id IN (SELECT id FROM users WHERE email = ?))');
        $rate->execute([$ip, $email]);
        if ((int)$rate->fetchColumn() < 5) {
            $userStmt = $pdo->prepare("SELECT id, email, full_name FROM users WHERE email = ? AND status = 'active' LIMIT 1");
            $userStmt->execute([$email]);
            $user = $userStmt->fetch();
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $pdo->beginTransaction();
                $pdo->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL')->execute([(int)$user['id']]);
                $insert = $pdo->prepare('INSERT INTO password_resets(user_id, token_hash, request_ip, expires_at) VALUES(?,?,?,DATE_ADD(NOW(), INTERVAL 60 MINUTE))');
                $insert->execute([(int)$user['id'], $tokenHash, $ip !== '' ? $ip : null]);
                $pdo->commit();

                $host = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
                if (!preg_match('/^[a-z0-9.-]+(?::[0-9]{1,5})?$/i', $host)) $host = 'localhost';
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $resetUrl = $scheme . '://' . $host . url('reset-password.php?token=' . rawurlencode($token));
                $from = app_setting('support_email', defined('HP_MAIL_FROM') ? HP_MAIL_FROM : 'no-reply@helpingplate.local');
                if (!filter_var($from, FILTER_VALIDATE_EMAIL)) $from = defined('HP_MAIL_FROM') ? HP_MAIL_FROM : 'no-reply@helpingplate.local';
                $fromName = defined('HP_MAIL_FROM_NAME') ? HP_MAIL_FROM_NAME : 'HelpingPlate';
                $subject = 'Reset your HelpingPlate password';
                $body = "Hello {$user['full_name']},\n\nWe received a request to reset your HelpingPlate password. Open the secure link below within 60 minutes:\n\n{$resetUrl}\n\nIf you did not request this change, you can ignore this email. Your current password will remain unchanged.\n\nHelpingPlate Support";
                $headers = [
                    'From: ' . $fromName . ' <' . $from . '>',
                    'Reply-To: ' . $from,
                    'Content-Type: text/plain; charset=UTF-8',
                    'X-Mailer: PHP/' . phpversion()
                ];
                $sent = @mail($user['email'], $subject, $body, implode("\r\n", $headers));
                if (!$sent) {
                    error_log('HelpingPlate password reset email could not be sent. Configure PHP mail/SMTP. User ID: ' . (int)$user['id']);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | HelpingPlate</title>
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
    <nav id="hpNavLinks" class="hp-nav-links"><a href="index.php">Home</a><a href="about.php">About</a><a href="how-it-works.php">How It Works</a><a href="volunteer.php">Volunteer</a><a href="feedback.php">Feedback</a><a href="login.php">Login</a><a class="hp-nav-btn" href="get-started.php">Get Started</a></nav>
    <button type="button" class="hp-menu-btn" id="menuBtn" aria-controls="hpNavLinks" aria-expanded="false" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
</div></header>
<main class="auth-page"><div class="auth-container">
    <section class="auth-intro"><span class="auth-badge"><i class="fa-solid fa-shield-halved"></i> Account recovery</span><h1>Get back to <span>HelpingPlate.</span></h1><p>Enter the email address linked to your account. We will send a one-time password reset link that expires after 60 minutes.</p></section>
    <section class="auth-card"><div class="auth-card-header"><h2>Forgot password?</h2><p>We'll email you a secure reset link.</p></div>
        <?php if ($error): ?><div class="auth-error"><?=e($error)?></div><?php endif; ?>
        <?php if ($notice): ?><div class="hp-flash success"><?=e($notice)?></div><?php endif; ?>
        <form method="post" class="auth-form"><?=csrf_field()?>
            <div class="auth-field"><label for="resetEmail">Email address <span>*</span></label><div class="auth-input-wrapper"><i class="fa-regular fa-envelope"></i><input id="resetEmail" type="email" name="email" maxlength="150" autocomplete="email" placeholder="Enter your registered email" required></div></div>
            <button type="submit" class="auth-submit-btn">Send reset link <i class="fa-solid fa-paper-plane"></i></button>
        </form>
        <div class="auth-register"><p>Remembered your password? <a href="login.php">Back to login</a></p></div>
    </section>
</div></main>
<script src="js/script.js"></script>
</body>
</html>
