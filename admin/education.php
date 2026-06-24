<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$edit = null;
$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    execute_query('DELETE FROM education WHERE id = ?', 'i', [$id]);
    set_flash('success', 'Data pendidikan berhasil dihapus.');
    redirect('admin/education.php');
}

if ($action === 'edit' && $id > 0) {
    $edit = fetch_one('SELECT * FROM education WHERE id = ?', 'i', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int) ($_POST['id'] ?? 0);
    $namaSekolah = trim($_POST['nama_sekolah'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $tahunMulai = trim($_POST['tahun_mulai'] ?? '');
    $tahunSelesai = trim($_POST['tahun_selesai'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($namaSekolah === '' || $jurusan === '') {
        set_flash('danger', 'Nama sekolah dan jurusan wajib diisi.');
        redirect('admin/education.php');
    }

    if ($postId > 0) {
        execute_query(
            'UPDATE education SET nama_sekolah = ?, jurusan = ?, tahun_mulai = ?, tahun_selesai = ?, deskripsi = ? WHERE id = ?',
            'sssssi',
            [$namaSekolah, $jurusan, $tahunMulai, $tahunSelesai, $deskripsi, $postId]
        );
        set_flash('success', 'Data pendidikan berhasil diperbarui.');
    } else {
        execute_query(
            'INSERT INTO education (nama_sekolah, jurusan, tahun_mulai, tahun_selesai, deskripsi) VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$namaSekolah, $jurusan, $tahunMulai, $tahunSelesai, $deskripsi]
        );
        set_flash('success', 'Data pendidikan berhasil ditambahkan.');
    }

    redirect('admin/education.php');
}

$educations = fetch_all('SELECT * FROM education ORDER BY tahun_mulai DESC, id DESC');

admin_header('Kelola Pendidikan');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow"><?= $edit ? 'Edit Data' : 'Tambah Data'; ?></p>
            <h2>Form Pendidikan</h2>
        </div>
        <?php if ($edit): ?><a class="btn btn-outline" href="<?= h(base_url('admin/education.php')); ?>">Batal</a><?php endif; ?>
    </div>
    <form class="admin-form" method="post" action="">
        <input type="hidden" name="id" value="<?= h((string) ($edit['id'] ?? 0)); ?>">
        <div class="form-grid">
            <div>
                <label for="nama_sekolah">Nama Sekolah</label>
                <input type="text" id="nama_sekolah" name="nama_sekolah" value="<?= h($edit['nama_sekolah'] ?? ''); ?>" placeholder="Nama sekolah atau kampus" required>
            </div>
            <div>
                <label for="jurusan">Jurusan</label>
                <input type="text" id="jurusan" name="jurusan" value="<?= h($edit['jurusan'] ?? ''); ?>" placeholder="Contoh: Informatika" required>
            </div>
            <div>
                <label for="tahun_mulai">Tahun Mulai</label>
                <input type="text" id="tahun_mulai" name="tahun_mulai" value="<?= h($edit['tahun_mulai'] ?? ''); ?>" placeholder="Contoh: 2024">
            </div>
            <div>
                <label for="tahun_selesai">Tahun Selesai</label>
                <input type="text" id="tahun_selesai" name="tahun_selesai" value="<?= h($edit['tahun_selesai'] ?? ''); ?>" placeholder="Contoh: Sekarang">
            </div>
        </div>
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Tulis ringkasan pembelajaran atau fokus akademik"><?= h($edit['deskripsi'] ?? ''); ?></textarea>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Data'; ?></button>
    </form>
</section>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Data Pendidikan</p>
            <h2>Daftar Pendidikan</h2>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Sekolah</th>
                    <th>Jurusan</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($educations as $item): ?>
                    <tr>
                        <td><?= h($item['nama_sekolah']); ?></td>
                        <td><?= h($item['jurusan']); ?></td>
                        <td><?= h($item['tahun_mulai']); ?> - <?= h($item['tahun_selesai']); ?></td>
                        <td class="table-actions">
                            <a class="btn btn-small btn-outline" href="<?= h(base_url('admin/education.php?action=edit&id=' . $item['id'])); ?>">Edit</a>
                            <a class="btn btn-small btn-danger" data-confirm href="<?= h(base_url('admin/education.php?action=delete&id=' . $item['id'])); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$educations): ?>
                    <tr><td colspan="4">Belum ada data pendidikan yang ditambahkan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
