<?php $profile = $profile ?? get_profile(); ?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-profile">
            <span><?= h(initials($profile['nama'])); ?></span>
            <div>
                <h3><?= h($profile['nama']); ?></h3>
                <p><?= h($profile['prodi']); ?> - <?= h($profile['kampus']); ?></p>
            </div>
        </div>
        <div class="footer-links">
            <?php if (!empty($profile['github'])): ?>
                <a href="<?= h($profile['github']); ?>" target="_blank" rel="noopener">GitHub</a>
            <?php endif; ?>
            <?php if (!empty($profile['linkedin'])): ?>
                <a href="<?= h($profile['linkedin']); ?>" target="_blank" rel="noopener">LinkedIn</a>
            <?php endif; ?>
            <?php if (!empty($profile['instagram'])): ?>
                <a href="<?= h($profile['instagram']); ?>" target="_blank" rel="noopener">Instagram</a>
            <?php endif; ?>
            <?php if (!empty($profile['email'])): ?>
                <a href="mailto:<?= h($profile['email']); ?>">Email</a>
            <?php endif; ?>
        </div>
    </div>
    <p class="copyright">Copyright &copy; <?= date('Y'); ?> <?= h($profile['nama']); ?>. Seluruh hak cipta dilindungi.</p>
</footer>
<script src="<?= h(asset_url('js/script.js')); ?>"></script>
</body>
</html>
