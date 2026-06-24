<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
admin_header('Dashboard');

$stats = [
    ['label' => 'Total Skill', 'value' => table_count('skills'), 'hint' => 'Kompetensi teknis'],
    ['label' => 'Total Project', 'value' => table_count('projects'), 'hint' => 'Project portofolio'],
    ['label' => 'Total Pengalaman', 'value' => table_count('experience'), 'hint' => 'Aktivitas belajar'],
    ['label' => 'Total Sertifikat', 'value' => table_count('certificates'), 'hint' => 'Bukti kompetensi'],
    ['label' => 'Pesan Masuk', 'value' => table_count('contact_messages'), 'hint' => 'Kontak pengunjung'],
];
$messages = fetch_all('SELECT * FROM contact_messages ORDER BY tanggal_kirim DESC LIMIT 5');
?>

<section class="admin-grid">
    <?php foreach ($stats as $stat): ?>
        <article class="stat-card">
            <span><?= h($stat['label']); ?></span>
            <strong><?= (int) $stat['value']; ?></strong>
            <small><?= h($stat['hint']); ?></small>
        </article>
    <?php endforeach; ?>
</section>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Terbaru</p>
            <h2>Pesan Masuk</h2>
        </div>
        <a class="btn btn-outline" href="<?= h(base_url('admin/messages.php')); ?>">Lihat Detail</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= h($message['nama']); ?></td>
                        <td><?= h($message['email']); ?></td>
                        <td><?= h(excerpt($message['pesan'], 80)); ?></td>
                        <td><?= h(date('d M Y H:i', strtotime($message['tanggal_kirim']))); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$messages): ?>
                    <tr><td colspan="4">Belum ada pesan masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
