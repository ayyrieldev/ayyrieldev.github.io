<?php
$pageTitle = 'Project';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$projects = fetch_all('SELECT * FROM projects ORDER BY tanggal_project DESC, id DESC');
?>

<main class="section page-section">
    <div class="container section-heading">
        <p class="eyebrow">Project Portofolio</p>
        <h1>Project</h1>
        <p>Kumpulan project pengembangan web yang menampilkan kemampuan front-end, PHP, database, dan penyusunan antarmuka responsif.</p>
    </div>
    <div class="container project-grid">
        <?php if ($projects): ?>
            <?php foreach ($projects as $project): ?>
                <article class="project-card">
                    <img src="<?= h(project_image_url($project['gambar'])); ?>" alt="<?= h($project['nama_project']); ?>">
                    <div>
                        <span class="card-meta"><?= h(date('d M Y', strtotime($project['tanggal_project']))); ?></span>
                        <h2><?= h($project['nama_project']); ?></h2>
                        <p><?= h($project['deskripsi']); ?></p>
                        <div class="tag-row"><?php render_badges($project['teknologi']); ?></div>
                        <div class="card-actions">
                            <?php if (!empty($project['link_github'])): ?>
                                <a class="btn btn-outline" href="<?= h($project['link_github']); ?>" target="_blank" rel="noopener">GitHub</a>
                            <?php endif; ?>
                            <?php if (!empty($project['link_demo'])): ?>
                                <a class="btn btn-primary" href="<?= h($project['link_demo']); ?>" target="_blank" rel="noopener">Lihat Demo</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <strong>Belum ada project yang ditampilkan.</strong>
                <p>Data akan tampil setelah ditambahkan melalui dashboard admin.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
