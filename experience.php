<?php
$pageTitle = 'Pengalaman';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$experiences = fetch_all('SELECT * FROM experience ORDER BY tanggal_mulai DESC, id DESC');
?>

<main class="section page-section">
    <div class="container section-heading">
        <p class="eyebrow">Pengalaman Belajar</p>
        <h1>Pengalaman</h1>
        <p>Ringkasan project based learning, academic project, personal web development practice, dan campus activity yang mendukung pengembangan kemampuan teknis.</p>
    </div>
    <div class="container timeline">
        <?php if ($experiences): ?>
            <?php foreach ($experiences as $item): ?>
                <article class="timeline-item">
                    <span></span>
                    <div>
                        <p class="card-meta">
                            <?= h(date('M Y', strtotime($item['tanggal_mulai']))); ?> -
                            <?= $item['tanggal_selesai'] ? h(date('M Y', strtotime($item['tanggal_selesai']))) : 'Sekarang'; ?>
                        </p>
                        <h2><?= h($item['posisi']); ?></h2>
                        <strong><?= h($item['instansi']); ?></strong>
                        <p><?= h($item['deskripsi']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <strong>Belum ada pengalaman yang ditambahkan.</strong>
                <p>Data akan tampil setelah ditambahkan melalui dashboard admin.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
