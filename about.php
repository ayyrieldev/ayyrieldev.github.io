<?php
$pageTitle = 'Tentang Saya';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="section page-section">
    <div class="container page-hero">
        <p class="eyebrow">Profil</p>
        <h1>Mahasiswa Informatika dengan fokus pada pengembangan website.</h1>
        <p>Saya merupakan mahasiswa Informatika di Universitas Teknologi Digital Indonesia. Saya memiliki minat pada pengembangan website, khususnya pada tampilan antarmuka, interaksi pengguna, PHP, dan pengelolaan database. Saya terus mengembangkan kemampuan melalui project sederhana, praktikum, dan eksplorasi teknologi web.</p>
    </div>

    <div class="container about-grid">
        <section class="profile-summary">
            <p class="eyebrow">Tentang Saya</p>
            <h2>Ringkasan Profil</h2>
            <p>Saya berfokus pada front-end development, PHP, perancangan database, dan pengembangan antarmuka yang mudah digunakan. Saya terbuka untuk magang, freelance, dan kolaborasi project web yang relevan dengan bidang Informatika.</p>
            <div class="focus-grid">
                <article>
                    <span>01</span>
                    <h3>Fokus Belajar</h3>
                    <p>HTML, CSS, JavaScript, PHP, MySQL, desain antarmuka, dan version control.</p>
                </article>
                <article>
                    <span>02</span>
                    <h3>Arah Karier</h3>
                    <p>Terbuka untuk kesempatan magang, project freelance, dan kolaborasi pengembangan website.</p>
                </article>
            </div>
        </section>
        <aside class="bio-card">
            <img src="https://cdn.phototourl.com/free/2026-06-20-5bcd3876-7217-4482-9f97-a8e21bdccce8.png" alt="Foto profil">
            <dl>
                <div>
                    <dt>Nama</dt>
                    <dd><?= h($profile['nama']); ?></dd>
                </div>
                <div>
                    <dt>Program Studi</dt>
                    <dd><?= h($profile['prodi']); ?></dd>
                </div>
                <div>
                    <dt>Kampus</dt>
                    <dd><?= h($profile['kampus']); ?></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd><?= h($profile['email']); ?></dd>
                </div>
            </dl>
        </aside>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
