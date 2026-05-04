<?php

require_once '../config/database.php';

$pageTitle = 'Register';
$metaDescription = 'Create a RysuReads account with email and password validation.';
$cssDepth = '../';
$jsDepth = '../';
$bodyClass = 'form-page';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        app_flash('error', 'Enter a name, valid email, and password with at least 8 characters.');
        header('Location: register.php');
        exit;
    }

    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($exists) {
        app_flash('error', 'That email is already registered.');
        header('Location: register.php');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $email, $hash, $role);

    if ($stmt->execute()) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $conn->insert_id, 'name' => $name, 'email' => $email, 'role' => $role];
        $stmt->close();
        $conn->close();
        header('Location: /');
        exit;
    }

    $stmt->close();
    app_flash('error', 'Registration failed. Please try again.');
    header('Location: register.php');
    exit;
}

include '../components/page_open.php';
$error = app_flash('error');
?>

<section class="section-block">
    <div class="container">
        <div class="form-shell auth-shell">
            <h1>Create account</h1>
            <p>Use your email address and a password with at least eight characters.</p>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
            <form method="POST" class="stack-form">
                <div>
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" id="name" name="name" required>
                </div>
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" required>
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password" minlength="8" required>
                </div>
                <button class="btn-primary-action" type="submit">Register</button>
            </form>
            <p class="form-link-line">Already have an account? <a href="/login">Login</a></p>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
