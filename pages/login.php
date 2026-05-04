<?php

require_once '../config/database.php';

$pageTitle = 'Login';
$metaDescription = 'Login to RysuReads using email and password.';
$cssDepth = '../';
$jsDepth = '../';
$bodyClass = 'form-page';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        app_flash('error', 'Enter a valid email and password.');
        header('Location: /login');
        exit;
    }

    $stmt = $conn->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?: 'user',
        ];
        $conn->close();
        $dest = (($user['role'] ?? '') === 'admin') ? '/admin' : '/';
        header('Location: ' . $dest);
        exit;
    }

    app_flash('error', 'Incorrect email or password.');
    header('Location: /login');
    exit;
}

include '../components/page_open.php';
$error = app_flash('error');
?>

<section class="section-block">
    <div class="container">
        <div class="form-shell auth-shell">
            <h1>Login</h1>
            <p>Use the email address and password you registered with.</p>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
            <form method="POST" class="stack-form">
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" required>
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password" required>
                </div>
                <button class="btn-primary-action" type="submit">Login</button>
            </form>
            <p class="form-link-line"><a href="/forgot-password">Forgot password?</a> | <a href="/register">Register</a></p>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
