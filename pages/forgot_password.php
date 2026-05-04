<?php

require_once '../config/database.php';

$pageTitle = 'Forgot Password';
$metaDescription = 'Request a password reset link for RysuReads.';
$cssDepth = '../';
$jsDepth = '../';
$bodyClass = 'form-page';
$resetLink = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        app_flash('error', 'Enter a valid email address.');
        header('Location: /forgot-password');
        exit;
    }

    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600);
        $update = $conn->prepare('UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE id = ?');
        $update->bind_param('ssi', $token, $expires, $user['id']);
        $update->execute();
        $update->close();
        $resetLink = 'reset_password.php?token=' . $token;
        app_flash('success', 'Password reset token created.');
    } else {
        app_flash('error', 'No account found for that email.');
    }
}

include '../components/page_open.php';
$error = app_flash('error');
$success = app_flash('success');
?>

<section class="section-block">
    <div class="container">
        <div class="form-shell auth-shell">
            <h1>Forgot password</h1>
            <p>Generate a reset token for an existing account.</p>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?php echo e($success); ?></div><?php endif; ?>
            <?php if ($resetLink): ?>
                <div class="alert alert-info">Reset link: <a href="<?php echo e($resetLink); ?>"><?php echo e($resetLink); ?></a></div>
            <?php endif; ?>
            <form method="POST" class="stack-form">
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" required>
                </div>
                <button class="btn-primary-action" type="submit">Generate reset link</button>
            </form>
    <p class="form-link-line"><a href="/login">Back to login</a></p>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
