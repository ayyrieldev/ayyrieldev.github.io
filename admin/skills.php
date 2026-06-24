<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$edit = null;
$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    execute_query('DELETE FROM skills WHERE id = ?', 'i', [$id]);
    set_flash('success', 'Data skill berhasil dihapus.');
    redirect('admin/skills.php');
}

if ($action === 'edit' && $id > 0) {
    $edit = fetch_one('SELECT * FROM skills WHERE id = ?', 'i', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int) ($_POST['id'] ?? 0);
    $namaSkill = trim($_POST['nama_skill'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $levelSkill = max(0, min(100, (int) ($_POST['level_skill'] ?? 0)));
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($namaSkill === '' || $kategori === '') {
        set_flash('danger', 'Nama skill dan kategori wajib diisi.');
        redirect('admin/skills.php');
    }

    if ($postId > 0) {
        execute_query(
            'UPDATE skills SET nama_skill = ?, kategori = ?, level_skill = ?, deskripsi = ? WHERE id = ?',
            'ssisi',
            [$namaSkill, $kategori, $levelSkill, $deskripsi, $postId]
        );
        set_flash('success', 'Data skill berhasil diperbarui.');
    } else {
        execute_query(
            'INSERT INTO skills (nama_skill, kategori, level_skill, deskripsi) VALUES (?, ?, ?, ?)',
            'ssis',
            [$namaSkill, $kategori, $levelSkill, $deskripsi]
        );
        set_flash('success', 'Data skill berhasil ditambahkan.');
    }

    redirect('admin/skills.php');
}

$skills = fetch_all('SELECT * FROM skills ORDER BY kategori ASC, level_skill DESC');

admin_header('Kelola Skill');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow"><?= $edit ? 'Edit Data' : 'Tambah Data'; ?></p>
            <h2>Form Skill</h2>
        </div>
        <?php if ($edit): ?><a class="btn btn-outline" href="<?= h(base_url('admin/skills.php')); ?>">Batal</a><?php endif; ?>
    </div>
    <form class="admin-form" method="post" action="">
        <input type="hidden" name="id" value="<?= h((string) ($edit['id'] ?? 0)); ?>">
        <div class="form-grid">
            <div>
                <label for="nama_skill">Nama Skill</label>
                <input type="text" id="nama_skill" name="nama_skill" value="<?= h($edit['nama_skill'] ?? ''); ?>" placeholder="Contoh: PHP" required>
            </div>
            <div>
                <label for="kategori">Kategori</label>
                <input type="text" id="kategori" name="kategori" value="<?= h($edit['kategori'] ?? ''); ?>" placeholder="Contoh: Back End" required>
            </div>
            <div>
                <label for="level_skill">Level Skill (%)</label>
                <input type="number" id="level_skill" name="level_skill" min="0" max="100" value="<?= h((string) ($edit['level_skill'] ?? 70)); ?>" required>
            </div>
        </div>
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Tulis deskripsi singkat kemampuan ini"><?= h($edit['deskripsi'] ?? ''); ?></textarea>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Data'; ?></button>
    </form>
</section>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Data Skill</p>
            <h2>Daftar Skill</h2>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Level</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($skills as $skill): ?>
                    <tr>
                        <td><?= h($skill['nama_skill']); ?></td>
                        <td><?= h($skill['kategori']); ?></td>
                        <td><?= (int) $skill['level_skill']; ?>%</td>
                        <td><?= h(excerpt($skill['deskripsi'], 80)); ?></td>
                        <td class="table-actions">
                            <a class="btn btn-small btn-outline" href="<?= h(base_url('admin/skills.php?action=edit&id=' . $skill['id'])); ?>">Edit</a>
                            <a class="btn btn-small btn-danger" data-confirm href="<?= h(base_url('admin/skills.php?action=delete&id=' . $skill['id'])); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$skills): ?>
                    <tr><td colspan="5">Belum ada skill yang ditambahkan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
