<?php

require_once '../config/database.php';

$pageTitle = 'Reset Password';
$metaDescription = 'Set a new password for your RysuReads account.';
$cssDepth = '../';
$jsDepth = '../';
$bodyClass = 'form-page';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$tokenValid = false;
$errorMessage = null;

if ($token !== '') {
    $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires_at > NOW() LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $tokenValid = (bool) $user;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    if (!$tokenValid) {
        $errorMessage = 'The reset link is invalid or expired.';
    } elseif (strlen($password) < 8) {
        $errorMessage = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $errorMessage = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires_at = NULL WHERE reset_token = ?');
        $update->bind_param('ss', $hash, $token);
        $update->execute();
        $update->close();
        app_flash('success', 'Password updated. You can log in now.');
        header('Location: /login');
        exit;
    }
}

include '../components/page_open.php';
$success = app_flash('success');
?>

<section class="section-block">
    <div class="container">
        <div class="form-shell auth-shell">
            <h1>Reset password</h1>
            <p>Create a new password using the reset token link.</p>
            <?php if ($success): ?><div class="alert alert-success"><?php echo e($success); ?></div><?php endif; ?>
            <?php if ($errorMessage): ?><div class="alert alert-danger"><?php echo e($errorMessage); ?></div><?php endif; ?>
            <?php if (!$tokenValid && !$errorMessage): ?>
                <div class="alert alert-warning">Provide a valid reset link first.</div>
            <?php endif; ?>
            <form method="POST" class="stack-form">
                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                <div>
                    <label class="form-label" for="password">New password</label>
                    <input class="form-control" id="password" name="password" type="password" minlength="8" required>
                </div>
                <div>
                    <label class="form-label" for="confirm_password">Confirm password</label>
                    <input class="form-control" id="confirm_password" name="confirm_password" type="password" minlength="8" required>
                </div>
                <button class="btn-primary-action" type="submit" <?php echo $tokenValid ? '' : 'disabled'; ?>>Update password</button>
            </form>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
