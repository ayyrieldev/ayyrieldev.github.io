<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$profile = get_profile();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $kampus = trim($_POST['kampus'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $github = trim($_POST['github'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $foto = $profile['foto'] ?? '';
    $uploadError = null;

    if (!empty($_FILES['foto']['name'])) {
        $uploaded = upload_image_file($_FILES['foto'], 'profile', $uploadError);
        if ($uploadError) {
            set_flash('danger', $uploadError);
            redirect('admin/profile.php');
        }
        $foto = $uploaded ?? $foto;
    }

    if ($nama === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Nama lengkap dan alamat email valid wajib diisi.');
        redirect('admin/profile.php');
    }

    execute_query(
        'UPDATE profile SET nama = ?, prodi = ?, kampus = ?, deskripsi = ?, email = ?, whatsapp = ?, github = ?, linkedin = ?, instagram = ?, foto = ? WHERE id = ?',
        'ssssssssssi',
        [$nama, $prodi, $kampus, $deskripsi, $email, $whatsapp, $github, $linkedin, $instagram, $foto, $profile['id']]
    );

    set_flash('success', 'Profil berhasil diperbarui.');
    redirect('admin/profile.php');
}

admin_header('Kelola Profil');
?>

<section class="admin-panel">
    <div class="panel-header">
        <div>
            <p class="eyebrow">Profil Website</p>
            <h2>Informasi Profil</h2>
        </div>
    </div>
    <form class="admin-form" method="post" action="" enctype="multipart/form-data">
        <div class="form-grid">
            <div>
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= h($profile['nama']); ?>" placeholder="Nama lengkap" required>
            </div>
            <div>
                <label for="prodi">Program Studi</label>
                <input type="text" id="prodi" name="prodi" value="<?= h($profile['prodi']); ?>" placeholder="Contoh: Informatika">
            </div>
            <div>
                <label for="kampus">Kampus</label>
                <input type="text" id="kampus" name="kampus" value="<?= h($profile['kampus']); ?>" placeholder="Nama kampus">
            </div>
            <div>
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="<?= h($profile['email']); ?>" placeholder="nama@email.com" required>
            </div>
            <div>
                <label for="whatsapp">WhatsApp</label>
                <input type="text" id="whatsapp" name="whatsapp" value="<?= h($profile['whatsapp']); ?>" placeholder="Nomor WhatsApp">
            </div>
            <div>
                <label for="github">GitHub</label>
                <input type="url" id="github" name="github" value="<?= h($profile['github']); ?>" placeholder="https://github.com/username">
            </div>
            <div>
                <label for="linkedin">LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" value="<?= h($profile['linkedin']); ?>" placeholder="https://www.linkedin.com/in/username">
            </div>
            <div>
                <label for="instagram">Instagram</label>
                <input type="url" id="instagram" name="instagram" value="<?= h($profile['instagram']); ?>" placeholder="https://www.instagram.com/username">
            </div>
        </div>
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Tulis deskripsi singkat profil Anda" required><?= h($profile['deskripsi']); ?></textarea>

        <label for="foto">Foto Profil</label>
        <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp">
        <img class="admin-preview" src="<?= h(profile_photo_url($profile['foto'])); ?>" alt="Foto profil">

        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    </form>
</section>

<?php admin_footer(); ?>
