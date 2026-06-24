CREATE DATABASE IF NOT EXISTS db_portfolio
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_portfolio;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS certificates;
DROP TABLE IF EXISTS education;
DROP TABLE IF EXISTS experience;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS admin;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    prodi VARCHAR(120) DEFAULT NULL,
    kampus VARCHAR(180) DEFAULT NULL,
    deskripsi TEXT DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    whatsapp VARCHAR(50) DEFAULT NULL,
    github VARCHAR(255) DEFAULT NULL,
    linkedin VARCHAR(255) DEFAULT NULL,
    instagram VARCHAR(255) DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_skill VARCHAR(120) NOT NULL,
    kategori VARCHAR(120) NOT NULL,
    level_skill INT NOT NULL DEFAULT 0,
    deskripsi TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_project VARCHAR(180) NOT NULL,
    deskripsi TEXT NOT NULL,
    teknologi VARCHAR(255) DEFAULT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    link_github VARCHAR(255) DEFAULT NULL,
    link_demo VARCHAR(255) DEFAULT NULL,
    tanggal_project DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posisi VARCHAR(180) NOT NULL,
    instansi VARCHAR(180) NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(180) NOT NULL,
    jurusan VARCHAR(180) NOT NULL,
    tahun_mulai VARCHAR(20) DEFAULT NULL,
    tahun_selesai VARCHAR(20) DEFAULT NULL,
    deskripsi TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_certificate VARCHAR(180) NOT NULL,
    penyelenggara VARCHAR(180) NOT NULL,
    tanggal DATE NOT NULL,
    file_certificate VARCHAR(255) DEFAULT NULL,
    link_certificate VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    pesan TEXT NOT NULL,
    tanggal_kirim DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$qu2W4tAM898LiYwSdcvKrOWgxdmbB.P9chydZjt6cLgydNOLvXr9C');

INSERT INTO profile (nama, prodi, kampus, deskripsi, email, whatsapp, github, linkedin, instagram, foto) VALUES
(
    'Muhammad Rezky Ayyriel Ramadhan',
    'Informatika',
    'Universitas Teknologi Digital Indonesia',
    'Saya merupakan mahasiswa Informatika di Universitas Teknologi Digital Indonesia. Saya memiliki minat pada pengembangan website, khususnya pada tampilan antarmuka, interaksi pengguna, PHP, dan pengelolaan database. Saya terus mengembangkan kemampuan melalui project sederhana, praktikum, dan eksplorasi teknologi web.',
    'ayyriel@example.com',
    '081234567890',
    'https://github.com/',
    'https://www.linkedin.com/',
    'https://www.instagram.com/',
    ''
);

INSERT INTO skills (nama_skill, kategori, level_skill, deskripsi) VALUES
('HTML', 'Front End', 88, 'Mampu membuat struktur halaman website yang rapi, semantik, dan mudah dikembangkan.'),
('CSS', 'Front End', 84, 'Mampu membuat tampilan website yang modern, responsive, dan nyaman digunakan di berbagai perangkat.'),
('JavaScript', 'Front End', 76, 'Mampu menambahkan interaksi pada website, seperti validasi form, manipulasi DOM, dark mode, dan fitur dinamis sederhana.'),
('PHP', 'Back End', 78, 'Mampu mengolah data dari form, membuat session login, dan membangun fitur CRUD sederhana.'),
('MySQL', 'Database', 75, 'Mampu membuat database, tabel, field, serta mengelola data untuk kebutuhan aplikasi web.'),
('UI Design', 'Design', 72, 'Mampu merancang tampilan antarmuka yang rapi, konsisten, dan mudah digunakan.'),
('GitHub', 'Tools', 70, 'Mampu menggunakan GitHub untuk menyimpan project, mengelola repository, dan dokumentasi kode.');

INSERT INTO projects (nama_project, deskripsi, teknologi, gambar, link_github, link_demo, tanggal_project) VALUES
('Website Portfolio Management System', 'Website Portfolio Management System adalah aplikasi web berbasis PHP Native dan MySQL yang digunakan untuk menampilkan biodata, skill, project, pengalaman, pendidikan, sertifikat, dan pesan kontak. Website ini dilengkapi dashboard admin untuk mengelola data secara dinamis.', 'HTML, CSS, JavaScript, PHP Native, MySQL', '', 'https://github.com/', '', '2026-06-01'),
('Sistem CRUD Data Mahasiswa', 'Sistem CRUD Data Mahasiswa adalah aplikasi web sederhana berbasis PHP Native dan MySQL untuk mengelola data mahasiswa. Project ini melatih proses tambah, tampil, edit, hapus data, serta integrasi form dengan database.', 'PHP, MySQL, HTML, CSS', '', 'https://github.com/', '', '2026-04-18'),
('Landing Page Responsif', 'Landing Page Responsif adalah halaman web statis yang dibuat menggunakan HTML, CSS, dan JavaScript. Project ini berfokus pada penyusunan layout, tampilan responsive, dan interaksi sederhana untuk meningkatkan pengalaman pengguna.', 'HTML, CSS, JavaScript', '', 'https://github.com/', '', '2026-03-10');

INSERT INTO education (nama_sekolah, jurusan, tahun_mulai, tahun_selesai, deskripsi) VALUES
('Universitas Teknologi Digital Indonesia', 'Informatika', '2024', 'Sekarang', 'Mempelajari dasar informatika, pemrograman web, database, UI design, dan pengembangan aplikasi berbasis web.'),
('SMA/SMK Sederajat', 'Jurusan Umum', '2021', '2024', 'Membangun dasar akademik dan minat awal pada teknologi digital serta pemrograman.');

INSERT INTO experience (posisi, instansi, deskripsi, tanggal_mulai, tanggal_selesai) VALUES
('Project Based Learning', 'Universitas Teknologi Digital Indonesia', 'Mengembangkan project web sederhana sebagai bagian dari proses belajar dan praktikum. Project ini melatih kemampuan dalam membuat tampilan website, mengelola data, membuat form, dan menerapkan konsep CRUD menggunakan PHP dan MySQL.', '2026-02-01', NULL),
('Personal Web Development Practice', 'Belajar Mandiri', 'Membangun latihan project web secara mandiri untuk memperkuat kemampuan front-end development, PHP, database, dan penggunaan GitHub dalam dokumentasi kode.', '2025-09-01', NULL),
('Academic Project', 'Universitas Teknologi Digital Indonesia', 'Mengerjakan project akademik untuk memahami alur pengembangan aplikasi web, mulai dari perancangan halaman, pembuatan form, pengolahan data, hingga penyimpanan ke database.', '2025-08-01', '2025-12-31'),
('Campus Activity', 'Universitas Teknologi Digital Indonesia', 'Mengikuti aktivitas kampus yang mendukung pengembangan komunikasi, kerja sama, dan pemahaman praktis terhadap teknologi web.', '2025-03-01', '2025-07-31');

INSERT INTO certificates (nama_certificate, penyelenggara, tanggal, file_certificate, link_certificate) VALUES
('Dasar Pemrograman Web', 'Belajar Mandiri', '2026-01-15', '', 'https://example.com/certificate'),
('Pengenalan Database MySQL', 'Belajar Mandiri', '2026-02-20', '', 'https://example.com/certificate');
