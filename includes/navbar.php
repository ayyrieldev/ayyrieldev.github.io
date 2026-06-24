<?php $profile = $profile ?? get_profile(); ?>
<header class="site-header">
    <nav class="navbar container">
        <a class="brand" href="<?= h(base_url('index.php')); ?>">
            <span><?= h(initials($profile['nama'])); ?></span>
            <strong><?= h($profile['nama']); ?></strong>
        </a>
        <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false" data-nav-toggle>
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="nav-menu" data-nav-menu>
            <a class="<?= active_class('index.php'); ?>" href="<?= h(base_url('index.php')); ?>">Beranda</a>
            <a class="<?= active_class('about.php'); ?>" href="<?= h(base_url('about.php')); ?>">Tentang</a>
            <a class="<?= active_class('skills.php'); ?>" href="<?= h(base_url('skills.php')); ?>">Skill</a>
            <a class="<?= active_class('projects.php'); ?>" href="<?= h(base_url('projects.php')); ?>">Project</a>
            <a class="<?= active_class('experience.php'); ?>" href="<?= h(base_url('experience.php')); ?>">Pengalaman</a>
            <a class="<?= active_class('education.php'); ?>" href="<?= h(base_url('education.php')); ?>">Pendidikan</a>
            <a class="<?= active_class('certificates.php'); ?>" href="<?= h(base_url('certificates.php')); ?>">Sertifikat</a>
            <a class="<?= active_class('contact.php'); ?>" href="<?= h(base_url('contact.php')); ?>">Kontak</a>
            <button class="theme-toggle" type="button" data-theme-toggle>
                <span class="theme-toggle-icon" aria-hidden="true" data-theme-icon></span>
                <span data-theme-label>Gelap</span>
            </button>
        </div>
    </nav>
</header>
