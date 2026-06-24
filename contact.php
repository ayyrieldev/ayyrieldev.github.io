<?php
$pageTitle = 'Kontak';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if ($nama === '') {
        $errors[] = 'Nama lengkap wajib diisi.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Alamat email tidak valid.';
    }

    if ($pesan === '') {
        $errors[] = 'Pesan wajib diisi.';
    }

    if (!$errors) {
        execute_query(
            'INSERT INTO contact_messages (nama, email, pesan, tanggal_kirim) VALUES (?, ?, ?, NOW())',
            'sss',
            [$nama, $email, $pesan]
        );
        $success = true;
        $_POST = [];
    }
}
?>

<main class="section page-section">
    <div class="container contact-grid">
        <section class="contact-info-card">
            <p class="eyebrow">Kontak</p>
            <h1>Tertarik untuk berdiskusi, berkolaborasi, atau melihat project saya lebih lanjut? Silakan kirim pesan melalui form berikut.</h1>
            <div class="contact-list">
                <p><span>@</span> <?= h($profile['email']); ?></p>
                <p><span>!</span> <?= h($profile['kampus']); ?></p>
            </div>
            <div class="social-row">
                <?php if (!empty($profile['github'])): ?><a href="<?= h($profile['github']); ?>" target="_blank" rel="noopener">GitHub</a><?php endif; ?>
                <?php if (!empty($profile['linkedin'])): ?><a href="<?= h($profile['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
                <?php if (!empty($profile['instagram'])): ?><a href="<?= h($profile['instagram']); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
            </div>
        </section>
        <form class="form-card" method="post" action="">
            <?php if ($success): ?>
                <div class="alert alert-success">Pesan berhasil dikirim. Terima kasih sudah menghubungi saya.</div>
            <?php endif; ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= h($error); ?></div>
            <?php endforeach; ?>

            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" value="<?= h($_POST['nama'] ?? ''); ?>" placeholder="Nama lengkap" required>

            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" value="<?= h($_POST['email'] ?? ''); ?>" placeholder="Alamat email" required>

            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" rows="6" placeholder="Tulis pesan Anda" required><?= h($_POST['pesan'] ?? ''); ?></textarea>

            <button class="btn btn-primary" type="submit">Kirim Pesan</button>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
