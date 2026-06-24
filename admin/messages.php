<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    execute_query('DELETE FROM contact_messages WHERE id = ?', 'i', [$id]);
    set_flash('success', 'Pesan masuk berhasil dihapus.');
    redirect('admin/messages.php');
}

$messages = fetch_all('SELECT * FROM contact_messages ORDER BY tanggal_kirim DESC, id DESC');

admin_header('Pesan Masuk');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Kotak Masuk</p>
            <h2>Pesan Masuk</h2>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= h($message['nama']); ?></td>
                        <td><a href="mailto:<?= h($message['email']); ?>"><?= h($message['email']); ?></a></td>
                        <td><?= nl2br(h($message['pesan'])); ?></td>
                        <td><?= h(date('d M Y H:i', strtotime($message['tanggal_kirim']))); ?></td>
                        <td class="table-actions">
                            <a class="btn btn-small btn-danger" data-confirm href="<?= h(base_url('admin/messages.php?action=delete&id=' . $message['id'])); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$messages): ?>
                    <tr><td colspan="5">Belum ada pesan masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
