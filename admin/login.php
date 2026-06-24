<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

ensure_session();

if (!empty($_SESSION['admin_id'])) {
    redirect('admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $admin = fetch_one('SELECT * FROM admin WHERE username = ? LIMIT 1', 's', [$username]);

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect('admin/dashboard.php');
        }

        $error = 'Username atau password tidak sesuai.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Portofolio</title>
    <link rel="stylesheet" href="<?= h(asset_url('css/style.css')); ?>">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-intro">
            <span class="login-logo">PA</span>
            <p class="eyebrow">Manajemen Portofolio</p>
            <h1>Kelola portofolio pribadi dari satu dashboard.</h1>
            <p>Masuk untuk memperbarui profil, skill, project, pengalaman, pendidikan, sertifikat, dan pesan masuk.</p>
        </section>
        <section class="login-card">
            <p class="eyebrow">Area Admin</p>
            <h2>Masuk ke Dashboard</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= h($error); ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= h($_POST['username'] ?? ''); ?>" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button class="btn btn-primary" type="submit">Masuk</button>
            </form>
            <a class="back-link" href="<?= h(base_url('index.php')); ?>">Kembali ke Website</a>
        </section>
    </main>
    <script src="<?= h(asset_url('js/script.js')); ?>"></script>
</body>
</html>
