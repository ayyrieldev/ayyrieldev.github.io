<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

function ensure_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function db(): mysqli
{
    global $conn;
    return $conn;
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function excerpt(?string $value, int $limit = 80): string
{
    $text = trim((string) $value);
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $limit, '...');
    }

    return strlen($text) > $limit ? substr($text, 0, $limit - 3) . '...' : $text;
}

function initials(?string $name): string
{
    $parts = preg_split('/\s+/', trim((string) $name));
    $letters = '';

    foreach ($parts as $part) {
        if ($part !== '') {
            $letters .= strtoupper(substr($part, 0, 1));
        }

        if (strlen($letters) >= 2) {
            break;
        }
    }

    return $letters ?: 'PF';
}

function render_badges(?string $items): void
{
    $parts = array_filter(array_map('trim', explode(',', (string) $items)));

    foreach ($parts as $part) {
        echo '<span class="tag">' . h($part) . '</span>';
    }
}

function base_url(string $path = ''): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $base = preg_replace('#/admin$#', '', $scriptDir);
    $base = $base === '/' ? '' : rtrim($base, '/');
    if ($base !== '') {
        $segments = array_filter(explode('/', $base), static fn($segment) => $segment !== '');
        $base = '/' . implode('/', array_map(static fn($segment) => rawurlencode(rawurldecode($segment)), $segments));
    }

    return $base . '/' . ltrim($path, '/');
}

function asset_url(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function fetch_all(string $sql, string $types = '', array $params = []): array
{
    $stmt = db()->prepare($sql);
    if (!$stmt) {
        die('Query gagal: ' . db()->error);
    }

    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();

    return $rows;
}

function fetch_one(string $sql, string $types = '', array $params = []): ?array
{
    $rows = fetch_all($sql, $types, $params);
    return $rows[0] ?? null;
}

function execute_query(string $sql, string $types = '', array $params = []): bool
{
    $stmt = db()->prepare($sql);
    if (!$stmt) {
        die('Query gagal: ' . db()->error);
    }

    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

function get_profile(): array
{
    return fetch_one('SELECT * FROM profile ORDER BY id ASC LIMIT 1') ?? [
        'nama' => 'Portofolio',
        'prodi' => '',
        'kampus' => '',
        'deskripsi' => '',
        'email' => '',
        'whatsapp' => '',
        'github' => '',
        'linkedin' => '',
        'instagram' => '',
        'foto' => '',
    ];
}

function table_count(string $table): int
{
    $allowed = ['skills', 'projects', 'experience', 'education', 'certificates', 'contact_messages'];
    if (!in_array($table, $allowed, true)) {
        return 0;
    }

    $row = fetch_one("SELECT COUNT(*) AS total FROM {$table}");
    return (int) ($row['total'] ?? 0);
}

function image_url(?string $filename, string $fallback): string
{
    $filename = trim((string) $filename);
    if ($filename !== '') {
        if (is_file(APP_ROOT . '/assets/uploads/' . $filename)) {
            return asset_url('uploads/' . rawurlencode($filename));
        }

        if (is_file(APP_ROOT . '/assets/img/' . $filename)) {
            return asset_url('img/' . rawurlencode($filename));
        }
    }

    return asset_url('img/' . $fallback);
}

function profile_photo_url(?string $filename): string
{
    return image_url($filename, 'profile-placeholder.svg');
}

function project_image_url(?string $filename): string
{
    return image_url($filename, 'project-placeholder.svg');
}

function certificate_image_url(?string $filename): string
{
    return image_url($filename, 'certificate-placeholder.svg');
}

function active_class(string $page): string
{
    $current = basename($_SERVER['PHP_SELF'] ?? '');
    return $current === $page ? 'active' : '';
}

function admin_active(string $page): string
{
    $current = basename($_SERVER['PHP_SELF'] ?? '');
    return $current === $page ? 'active' : '';
}

function require_admin(): void
{
    ensure_session();
    if (empty($_SESSION['admin_id'])) {
        redirect('admin/login.php');
    }
}

function set_flash(string $type, string $message): void
{
    ensure_session();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    ensure_session();
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function upload_image_file(array $file, string $prefix, ?string &$error = null): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        $error = 'Upload gagal. Coba pilih file lain.';
        return null;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        $error = 'Format gambar harus jpg, jpeg, png, atau webp.';
        return null;
    }

    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $allowedMimes, true)) {
        $error = 'File yang diupload bukan gambar valid.';
        return null;
    }

    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        $error = 'Ukuran gambar maksimal 2 MB.';
        return null;
    }

    $uploadDir = APP_ROOT . '/assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $filename = $prefix . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $error = 'Gagal menyimpan file upload.';
        return null;
    }

    return $filename;
}

function admin_header(string $title): void
{
    $flash = get_flash();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= h($title); ?> - Admin Portofolio</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= h(asset_url('css/style.css')); ?>">
    </head>
    <body class="admin-body">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="<?= h(base_url('admin/dashboard.php')); ?>">
                <span>PA</span>
                <strong>Admin Portofolio</strong>
            </a>
            <nav class="admin-nav">
                <a class="<?= admin_active('dashboard.php'); ?>" href="<?= h(base_url('admin/dashboard.php')); ?>"><span>01</span> Dashboard</a>
                <a class="<?= admin_active('profile.php'); ?>" href="<?= h(base_url('admin/profile.php')); ?>"><span>02</span> Kelola Profil</a>
                <a class="<?= admin_active('skills.php'); ?>" href="<?= h(base_url('admin/skills.php')); ?>"><span>03</span> Kelola Skill</a>
                <a class="<?= admin_active('projects.php'); ?>" href="<?= h(base_url('admin/projects.php')); ?>"><span>04</span> Kelola Project</a>
                <a class="<?= admin_active('experience.php'); ?>" href="<?= h(base_url('admin/experience.php')); ?>"><span>05</span> Kelola Pengalaman</a>
                <a class="<?= admin_active('education.php'); ?>" href="<?= h(base_url('admin/education.php')); ?>"><span>06</span> Kelola Pendidikan</a>
                <a class="<?= admin_active('certificates.php'); ?>" href="<?= h(base_url('admin/certificates.php')); ?>"><span>07</span> Kelola Sertifikat</a>
                <a class="<?= admin_active('messages.php'); ?>" href="<?= h(base_url('admin/messages.php')); ?>"><span>08</span> Pesan Masuk</a>
            </nav>
            <div class="admin-sidebar-footer">
                <a href="<?= h(base_url('index.php')); ?>" target="_blank">Lihat Website</a>
                <a href="<?= h(base_url('admin/logout.php')); ?>">Logout</a>
            </div>
        </aside>
        <main class="admin-main">
            <div class="admin-topbar">
                <div>
                    <p class="eyebrow">Dashboard Admin</p>
                    <h1><?= h($title); ?></h1>
                </div>
                <div class="admin-topbar-actions">
                    <span class="admin-user"><?= h($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                    <a class="btn btn-outline" href="<?= h(base_url('index.php')); ?>" target="_blank">Lihat Website</a>
                    <button class="theme-toggle" type="button" data-theme-toggle>Mode Gelap</button>
                </div>
            </div>
            <?php if ($flash): ?>
                <div class="alert alert-<?= h($flash['type']); ?>"><?= h($flash['message']); ?></div>
            <?php endif; ?>
    <?php
}

function admin_footer(): void
{
    ?>
        </main>
        <script src="<?= h(asset_url('js/script.js')); ?>"></script>
    </body>
    </html>
    <?php
}
