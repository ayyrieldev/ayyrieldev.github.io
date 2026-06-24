<?php
$pageTitle = 'Sertifikat';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$certificates = fetch_all('SELECT * FROM certificates ORDER BY tanggal DESC, id DESC');
?>

<main class="section page-section">
    <div class="container section-heading">
        <p class="eyebrow">Pengembangan Kompetensi</p>
        <h1>Sertifikat</h1>
        <p>Sertifikat pelatihan, kelas online, dan kegiatan akademik yang relevan dengan pengembangan web, teknologi, dan peningkatan kompetensi.</p>
    </div>
    <div class="container project-grid">
        <?php if ($certificates): ?>
            <?php foreach ($certificates as $certificate): ?>
                <article class="project-card certificate-card">
                    <img src="<?= h(certificate_image_url($certificate['file_certificate'])); ?>" alt="<?= h($certificate['nama_certificate']); ?>">
                    <div>
                        <span class="card-meta"><?= h(date('d M Y', strtotime($certificate['tanggal']))); ?></span>
                        <h2><?= h($certificate['nama_certificate']); ?></h2>
                        <p><?= h($certificate['penyelenggara']); ?></p>
                        <?php if (!empty($certificate['link_certificate'])): ?>
                            <a class="btn btn-outline" href="<?= h($certificate['link_certificate']); ?>" target="_blank" rel="noopener">Lihat Sertifikat</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <strong>Belum ada sertifikat yang ditampilkan.</strong>
                <p>Data akan tampil setelah ditambahkan melalui dashboard admin.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
