<?php
$pageTitle = 'Skill';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$skills = fetch_all('SELECT * FROM skills ORDER BY kategori ASC, level_skill DESC');
?>

<main class="section page-section">
    <div class="container section-heading">
        <p class="eyebrow">Kompetensi Teknis</p>
        <h1>Skill</h1>
        <p>Kumpulan kemampuan teknis yang dikembangkan melalui project kuliah, praktikum, latihan mandiri, dan pengembangan portfolio digital.</p>
    </div>
    <div class="container card-grid">
        <?php if ($skills): ?>
            <?php foreach ($skills as $skill): ?>
                <article class="skill-card">
                    <div class="card-topline">
                        <span class="card-meta"><?= h($skill['kategori']); ?></span>
                        <strong><?= (int) $skill['level_skill']; ?>%</strong>
                    </div>
                    <h2><?= h($skill['nama_skill']); ?></h2>
                    <p><?= h($skill['deskripsi']); ?></p>
                    <div class="progress" aria-label="Level skill <?= (int) $skill['level_skill']; ?> persen">
                        <span style="width: <?= (int) $skill['level_skill']; ?>%"></span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <strong>Belum ada skill yang ditambahkan.</strong>
                <p>Data akan tampil setelah ditambahkan melalui dashboard admin.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
