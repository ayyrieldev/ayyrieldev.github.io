<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$edit = null;
$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    execute_query('DELETE FROM certificates WHERE id = ?', 'i', [$id]);
    set_flash('success', 'Data sertifikat berhasil dihapus.');
    redirect('admin/certificates.php');
}

if ($action === 'edit' && $id > 0) {
    $edit = fetch_one('SELECT * FROM certificates WHERE id = ?', 'i', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int) ($_POST['id'] ?? 0);
    $namaCertificate = trim($_POST['nama_certificate'] ?? '');
    $penyelenggara = trim($_POST['penyelenggara'] ?? '');
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $linkCertificate = trim($_POST['link_certificate'] ?? '');
    $current = $postId > 0 ? fetch_one('SELECT file_certificate FROM certificates WHERE id = ?', 'i', [$postId]) : null;
    $fileCertificate = $current['file_certificate'] ?? '';
    $uploadError = null;

    if ($namaCertificate === '' || $penyelenggara === '') {
        set_flash('danger', 'Nama sertifikat dan penyelenggara wajib diisi.');
        redirect('admin/certificates.php');
    }

    if (!empty($_FILES['file_certificate']['name'])) {
        $uploaded = upload_image_file($_FILES['file_certificate'], 'certificate', $uploadError);
        if ($uploadError) {
            set_flash('danger', $uploadError);
            redirect('admin/certificates.php' . ($postId ? '?action=edit&id=' . $postId : ''));
        }
        $fileCertificate = $uploaded ?? $fileCertificate;
    }

    if ($postId > 0) {
        execute_query(
            'UPDATE certificates SET nama_certificate = ?, penyelenggara = ?, tanggal = ?, file_certificate = ?, link_certificate = ? WHERE id = ?',
            'sssssi',
            [$namaCertificate, $penyelenggara, $tanggal, $fileCertificate, $linkCertificate, $postId]
        );
        set_flash('success', 'Data sertifikat berhasil diperbarui.');
    } else {
        execute_query(
            'INSERT INTO certificates (nama_certificate, penyelenggara, tanggal, file_certificate, link_certificate) VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$namaCertificate, $penyelenggara, $tanggal, $fileCertificate, $linkCertificate]
        );
        set_flash('success', 'Data sertifikat berhasil ditambahkan.');
    }

    redirect('admin/certificates.php');
}

$certificates = fetch_all('SELECT * FROM certificates ORDER BY tanggal DESC, id DESC');

admin_header('Kelola Sertifikat');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow"><?= $edit ? 'Edit Data' : 'Tambah Data'; ?></p>
            <h2>Form Sertifikat</h2>
        </div>
        <?php if ($edit): ?><a class="btn btn-outline" href="<?= h(base_url('admin/certificates.php')); ?>">Batal</a><?php endif; ?>
    </div>
    <form class="admin-form" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= h((string) ($edit['id'] ?? 0)); ?>">
        <div class="form-grid">
            <div>
                <label for="nama_certificate">Nama Sertifikat</label>
                <input type="text" id="nama_certificate" name="nama_certificate" value="<?= h($edit['nama_certificate'] ?? ''); ?>" placeholder="Nama sertifikat" required>
            </div>
            <div>
                <label for="penyelenggara">Penyelenggara</label>
                <input type="text" id="penyelenggara" name="penyelenggara" value="<?= h($edit['penyelenggara'] ?? ''); ?>" placeholder="Nama penyelenggara" required>
            </div>
            <div>
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="<?= h($edit['tanggal'] ?? date('Y-m-d')); ?>" required>
            </div>
            <div>
                <label for="link_certificate">Link Sertifikat</label>
                <input type="url" id="link_certificate" name="link_certificate" value="<?= h($edit['link_certificate'] ?? ''); ?>" placeholder="https://link-sertifikat.com">
            </div>
            <div>
                <label for="file_certificate">Gambar Sertifikat</label>
                <input type="file" id="file_certificate" name="file_certificate" accept=".jpg,.jpeg,.png,.webp">
            </div>
        </div>
        <?php if (!empty($edit['file_certificate'])): ?>
            <img class="admin-preview" src="<?= h(certificate_image_url($edit['file_certificate'])); ?>" alt="Preview sertifikat">
        <?php endif; ?>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Data'; ?></button>
    </form>
</section>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Data Sertifikat</p>
            <h2>Daftar Sertifikat</h2>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Sertifikat</th>
                    <th>Penyelenggara</th>
                    <th>Tanggal</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certificates as $certificate): ?>
                    <tr>
                        <td><?= h($certificate['nama_certificate']); ?></td>
                        <td><?= h($certificate['penyelenggara']); ?></td>
                        <td><?= h(date('d M Y', strtotime($certificate['tanggal']))); ?></td>
                        <td><img class="table-thumb" src="<?= h(certificate_image_url($certificate['file_certificate'])); ?>" alt=""></td>
                        <td class="table-actions">
                            <a class="btn btn-small btn-outline" href="<?= h(base_url('admin/certificates.php?action=edit&id=' . $certificate['id'])); ?>">Edit</a>
                            <a class="btn btn-small btn-danger" data-confirm href="<?= h(base_url('admin/certificates.php?action=delete&id=' . $certificate['id'])); ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$certificates): ?>
                    <tr><td colspan="5">Belum ada sertifikat yang ditampilkan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php admin_footer(); ?>
