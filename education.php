<?php
$pageTitle = 'Pendidikan';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$educations = fetch_all('SELECT * FROM education ORDER BY tahun_mulai DESC, id DESC');
?>

<main class="section page-section">
    <div class="container section-heading">
        <p class="eyebrow">Riwayat Akademik</p>
        <h1>Pendidikan</h1>
        <p>Riwayat pendidikan yang menjadi dasar pengembangan kompetensi informatika, pemrograman web, dan cara berpikir sistematis.</p>
    </div>
    <div class="container timeline">
        <?php if ($educations): ?>
            <?php foreach ($educations as $item): ?>
                <article class="timeline-item">
                    <span></span>
                    <div>
                        <p class="card-meta"><?= h($item['tahun_mulai']); ?> - <?= h($item['tahun_selesai']); ?></p>
                        <h2><?= h($item['nama_sekolah']); ?></h2>
                        <strong><?= h($item['jurusan']); ?></strong>
                        <p><?= h($item['deskripsi']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <strong>Belum ada data pendidikan yang ditambahkan.</strong>
                <p>Data akan tampil setelah ditambahkan melalui dashboard admin.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
