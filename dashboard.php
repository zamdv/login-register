<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard-shell">
    <div class="dash-top">
        <a href="../index.html">&lt;nizam.dev</a>
        <a class="logout" href="logout.php">Logout</a>
    </div>
    <div class="dashboard-card">
        <p class="eyebrow">AUTH SUCCESS</p>
        <h1>Halo, <?= htmlspecialchars($user['name']) ?> 👋</h1>
        <p class="sub">Kamu berhasil login menggunakan data dari MySQL.</p>
        <div class="info-grid">
            <div><span>USER ID</span><strong>#<?= htmlspecialchars((string)$user['id']) ?></strong></div>
            <div><span>EMAIL</span><strong><?= htmlspecialchars($user['email']) ?></strong></div>
            <div><span>SESSION</span><strong>ACTIVE</strong></div>
            <div><span>STACK</span><strong>PHP + MySQL</strong></div>
        </div>
        <a href="../index.html#projects" class="back-link">← Kembali ke portfolio</a>
    </div>
</div>
</body>
</html>
