<?php
session_start();
require 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $message = 'Isi data dengan benar. Password minimal 6 karakter.';
        $messageType = 'error';
    } else {
        $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = 'Email sudah terdaftar. Silakan login.';
            $messageType = 'error';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $email, $hash);

            if ($stmt->execute()) {
                $message = 'Registrasi berhasil. Silakan login.';
                $messageType = 'success';
            } else {
                $message = 'Registrasi gagal. Coba lagi.';
                $messageType = 'error';
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-shell">
    <div class="auth-brand"><a href="../index.html">&lt;nizam.dev</a><span>PHP • MySQL • Auth</span></div>
    <div class="auth-card">
        <div class="auth-icon">+</div>
        <p class="eyebrow">CREATE ACCOUNT</p>
        <h1>Register</h1>
        <p class="sub">Buat akun untuk mencoba project login & register.</p>
        <?php if ($message): ?><div class="notice <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <form method="post" autocomplete="off">
            <label>Nama
                <input type="text" name="name" placeholder="Nama lengkap" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </label>
            <label>Email
                <input type="email" name="email" placeholder="nama@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </label>
            <label>Password
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </label>
            <button type="submit">Daftar</button>
        </form>
        <p class="switch">Sudah punya akun? <a href="index.php">Login di sini</a></p>
        <p class="school-note">Project latihan SMK kelas XI — data disimpan ke MySQL.</p>
    </div>
</div>
</body>
</html>
