<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$edit = null;
$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    execute_query('DELETE FROM experience WHERE id = ?', 'i', [$id]);
    set_flash('success', 'Data pengalaman berhasil dihapus.');
    redirect('admin/experience.php');
}

if ($action === 'edit' && $id > 0) {
    $edit = fetch_one('SELECT * FROM experience WHERE id = ?', 'i', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int) ($_POST['id'] ?? 0);
    $posisi = trim($_POST['posisi'] ?? '');
    $instansi = trim($_POST['instansi'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tanggalMulai = $_POST['tanggal_mulai'] ?? date('Y-m-d');
    $tanggalSelesai = trim($_POST['tanggal_selesai'] ?? '');
    $tanggalSelesai = $tanggalSelesai === '' ? null : $tanggalSelesai;

    if ($posisi === '' || $instansi === '') {
        set_flash('danger', 'Nama kegiatan dan instansi wajib diisi.');
        redirect('admin/experience.php');
    }

    if ($postId > 0) {
        execute_query(
            'UPDATE experience SET posisi = ?, instansi = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ?',
            'sssssi',
            [$posisi, $instansi, $deskripsi, $tanggalMulai, $tanggalSelesai, $postId]
        );
        set_flash('success', 'Data pengalaman berhasil diperbarui.');
    } else {
        execute_query(
            'INSERT INTO experience (posisi, instansi, deskripsi, tanggal_mulai, tanggal_selesai) VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$posisi, $instansi, $deskripsi, $tanggalMulai, $tanggalSelesai]
        );
        set_flash('success', 'Data pengalaman berhasil ditambahkan.');
    }

    redirect('admin/experience.php');
}

$experiences = fetch_all('SELECT * FROM experience ORDER BY tanggal_mulai DESC, id DESC');

admin_header('Kelola Pengalaman');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow"><?= $edit ? 'Edit Data' : 'Tambah Data'; ?></p>
            <h2>Form Pengalaman</h2>
        </div>
        <?php if ($edit): ?><a class="btn btn-outline" href="<?= h(base_url('admin/experience.php')); ?>">Batal</a><?php endif; ?>
    </div>
    <form class="admin-form" method="post" action="">
        <input type="hidden" name="id" value="<?= h((string) ($edit['id'] ?? 0)); ?>">
        <div class="form-grid">
            <div>
                <label for="posisi">Nama Kegiatan</label>
                <input type="text" id="posisi" name="posisi" value="<?= h($edit['posisi'] ?? ''); ?>" placeholder="Contoh: Project Based Learning" required>
            </div>
            <div>
                <label for="instansi">Instansi</label>
                <input type="text" id="instansi" name="instansi" value="<?= h($edit['instansi'] ?? ''); ?>" placeholder="Nama kampus, organisasi, atau tempat kegiatan" required>
            </div>
            <div>
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= h($edit['tanggal_mulai'] ?? date('Y-m-d')); ?>" required>
            </div>
            <div>
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= h($edit['tanggal_selesai'] ?? ''); ?>">
            </div>
        </div>
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan aktivitas, teknologi, dan kemampuan yang dilatih"><?= h($edit['deskripsi'] ?? ''); ?></textarea>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Data'; ?></button>
    </form>
</section>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Data Pengalaman</p>
            <h2>Daftar Pengalaman</h2>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Instansi</th>
                    <th>Periode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($experiences as $item): ?>
                    <tr>
                        <td><?= h($item['posisi']); ?></td>
                        <td><?= h($item['instansi']); ?></td>
                        <td><?= h($item['tanggal_mulai']); ?> - <?= h($item['tanggal_selesai'] ?: 'Sekarang'); ?></td>
                        <td class="table-actions">
                            <a class="btn btn-small btn-outline" href="<?= h(base_url('admin/experience.php?action=edit&id=' . $item['id'])); ?>">Edit</a>
                            <a class="btn btn-small btn-danger" data-confirm href="<?= h(base_url('admin/experience.php?action=delete&id=' . $item['id'])); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$experiences): ?>
                    <tr><td colspan="4">Belum ada pengalaman yang ditambahkan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
