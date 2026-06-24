<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$edit = null;
$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    execute_query('DELETE FROM projects WHERE id = ?', 'i', [$id]);
    set_flash('success', 'Data project berhasil dihapus.');
    redirect('admin/projects.php');
}

if ($action === 'edit' && $id > 0) {
    $edit = fetch_one('SELECT * FROM projects WHERE id = ?', 'i', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int) ($_POST['id'] ?? 0);
    $namaProject = trim($_POST['nama_project'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $teknologi = trim($_POST['teknologi'] ?? '');
    $linkGithub = trim($_POST['link_github'] ?? '');
    $linkDemo = trim($_POST['link_demo'] ?? '');
    $tanggalProject = $_POST['tanggal_project'] ?? date('Y-m-d');
    $current = $postId > 0 ? fetch_one('SELECT gambar FROM projects WHERE id = ?', 'i', [$postId]) : null;
    $gambar = $current['gambar'] ?? '';
    $uploadError = null;

    if ($namaProject === '' || $deskripsi === '') {
        set_flash('danger', 'Nama project dan deskripsi wajib diisi.');
        redirect('admin/projects.php');
    }

    if (!empty($_FILES['gambar']['name'])) {
        $uploaded = upload_image_file($_FILES['gambar'], 'project', $uploadError);
        if ($uploadError) {
            set_flash('danger', $uploadError);
            redirect('admin/projects.php' . ($postId ? '?action=edit&id=' . $postId : ''));
        }
        $gambar = $uploaded ?? $gambar;
    }

    if ($postId > 0) {
        execute_query(
            'UPDATE projects SET nama_project = ?, deskripsi = ?, teknologi = ?, gambar = ?, link_github = ?, link_demo = ?, tanggal_project = ? WHERE id = ?',
            'sssssssi',
            [$namaProject, $deskripsi, $teknologi, $gambar, $linkGithub, $linkDemo, $tanggalProject, $postId]
        );
        set_flash('success', 'Data project berhasil diperbarui.');
    } else {
        execute_query(
            'INSERT INTO projects (nama_project, deskripsi, teknologi, gambar, link_github, link_demo, tanggal_project) VALUES (?, ?, ?, ?, ?, ?, ?)',
            'sssssss',
            [$namaProject, $deskripsi, $teknologi, $gambar, $linkGithub, $linkDemo, $tanggalProject]
        );
        set_flash('success', 'Data project berhasil ditambahkan.');
    }

    redirect('admin/projects.php');
}

$projects = fetch_all('SELECT * FROM projects ORDER BY tanggal_project DESC, id DESC');

admin_header('Kelola Project');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow"><?= $edit ? 'Edit Data' : 'Tambah Data'; ?></p>
            <h2>Form Project</h2>
        </div>
        <?php if ($edit): ?><a class="btn btn-outline" href="<?= h(base_url('admin/projects.php')); ?>">Batal</a><?php endif; ?>
    </div>
    <form class="admin-form" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= h((string) ($edit['id'] ?? 0)); ?>">
        <div class="form-grid">
            <div>
                <label for="nama_project">Nama Project</label>
                <input type="text" id="nama_project" name="nama_project" value="<?= h($edit['nama_project'] ?? ''); ?>" placeholder="Nama project" required>
            </div>
            <div>
                <label for="teknologi">Teknologi</label>
                <input type="text" id="teknologi" name="teknologi" value="<?= h($edit['teknologi'] ?? ''); ?>" placeholder="Contoh: PHP, MySQL, JavaScript">
            </div>
            <div>
                <label for="link_github">Link GitHub</label>
                <input type="url" id="link_github" name="link_github" value="<?= h($edit['link_github'] ?? ''); ?>" placeholder="https://github.com/username/repository">
            </div>
            <div>
                <label for="link_demo">Link Demo</label>
                <input type="url" id="link_demo" name="link_demo" value="<?= h($edit['link_demo'] ?? ''); ?>" placeholder="https://domain-project.com">
            </div>
            <div>
                <label for="tanggal_project">Tanggal Project</label>
                <input type="date" id="tanggal_project" name="tanggal_project" value="<?= h($edit['tanggal_project'] ?? date('Y-m-d')); ?>" required>
            </div>
            <div>
                <label for="gambar">Gambar Project</label>
                <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
            </div>
        </div>
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan fungsi, teknologi, dan manfaat project" required><?= h($edit['deskripsi'] ?? ''); ?></textarea>
        <?php if (!empty($edit['gambar'])): ?>
            <img class="admin-preview" src="<?= h(project_image_url($edit['gambar'])); ?>" alt="Preview project">
        <?php endif; ?>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Data'; ?></button>
    </form>
</section>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Data Project</p>
            <h2>Daftar Project</h2>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Project</th>
                    <th>Teknologi</th>
                    <th>Tanggal</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= h($project['nama_project']); ?></td>
                        <td><?= h($project['teknologi']); ?></td>
                        <td><?= h(date('d M Y', strtotime($project['tanggal_project']))); ?></td>
                        <td><img class="table-thumb" src="<?= h(project_image_url($project['gambar'])); ?>" alt=""></td>
                        <td class="table-actions">
                            <a class="btn btn-small btn-outline" href="<?= h(base_url('admin/projects.php?action=edit&id=' . $project['id'])); ?>">Edit</a>
                            <a class="btn btn-small btn-danger" data-confirm href="<?= h(base_url('admin/projects.php?action=delete&id=' . $project['id'])); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$projects): ?>
                    <tr><td colspan="5">Belum ada project yang ditampilkan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
