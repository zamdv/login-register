<?php
session_start();
require 'config.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $message = 'Email dan password wajib diisi.';
        $messageType = 'error';
    } else {
        $stmt = $conn->prepare('SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            header('Location: dashboard.php');
            exit;
        }

        $message = 'Email atau password salah.';
        $messageType = 'error';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-shell">
    <div class="auth-brand"><a href="../index.html">&lt;nizam.dev</a><span>PHP • MySQL • Auth</span></div>
    <div class="auth-card">
        <div class="auth-icon">&gt;_</div>
        <p class="eyebrow">WELCOME BACK</p>
        <h1>Login</h1>
        <p class="sub">Masuk untuk membuka dashboard project.</p>
        <?php if ($message): ?><div class="notice <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <form method="post" autocomplete="off">
            <label>Email
                <input type="email" name="email" placeholder="nama@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </label>
            <label>Password
                <input type="password" name="password" placeholder="Masukkan password" required>
            </label>
            <button type="submit">Login</button>
        </form>
        <p class="switch">Belum punya akun? <a href="register.php">Register di sini</a></p>
        <p class="school-note">Demo backend untuk portofolio siswa SMK kelas XI.</p>
    </div>
</div>
</body>
</html>
