CREATE DATABASE IF NOT EXISTS tugas2_cv
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tugas2_cv;

CREATE TABLE IF NOT EXISTS profiles (
    id TINYINT UNSIGNED PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    headline VARCHAR(150) NOT NULL,
    role VARCHAR(100) NOT NULL,
    nim VARCHAR(30) NOT NULL,
    study_program VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    location VARCHAR(150) NOT NULL,
    github VARCHAR(200) NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    summary TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS educations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    institution VARCHAR(180) NOT NULL,
    major_period VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS experiences (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    period VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS skills (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(100) NOT NULL DEFAULT 'fas fa-code',
    sort_order INT UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS portfolios (
    id TINYINT UNSIGNED PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS statistics (
    id TINYINT UNSIGNED PRIMARY KEY,
    skill_count VARCHAR(20) NOT NULL,
    project_count VARCHAR(20) NOT NULL,
    cv_count VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

INSERT INTO profiles (id, full_name, headline, role, nim, study_program, email, phone, location, github, photo_path, summary)
VALUES (
    1,
    'Rahma Fauzi Nurul Islam',
    'Mahasiswa Teknik Informatika',
    'Mahasiswa Aktif',
    '301230056',
    'Teknik Informatika',
    'rahmafauzi003@gmail.com',
    '08837468782',
    'Bandung, Indonesia',
    'github.com/rahmafauzi',
    'assets/img/profile.svg',
    'Saya adalah mahasiswa yang memiliki ketertarikan pada bidang teknologi informasi, khususnya pengembangan web, desain antarmuka, dan pemrograman internet. Saya memiliki kemampuan dasar dalam membuat halaman web menggunakan HTML, CSS, Bootstrap, AdminLTE, dan JavaScript. Saya terbiasa bekerja secara terstruktur, teliti, dan memiliki motivasi untuk terus meningkatkan kemampuan di bidang teknologi digital.'
)
ON DUPLICATE KEY UPDATE
    full_name = VALUES(full_name),
    headline = VALUES(headline),
    role = VALUES(role),
    nim = VALUES(nim),
    study_program = VALUES(study_program),
    email = VALUES(email),
    phone = VALUES(phone),
    location = VALUES(location),
    github = VALUES(github),
    photo_path = VALUES(photo_path),
    summary = VALUES(summary);

TRUNCATE TABLE educations;
INSERT INTO educations (institution, major_period, description, sort_order) VALUES
('Universitas Bale Bandung', 'Teknik Informatika | 2023 sampai Sekarang', 'Mempelajari dasar pemrograman, pengembangan web, basis data, jaringan komputer, desain antarmuka, dan aplikasi berbasis internet.', 1),
('MA Al-Huda Pameungpeuk', 'MIPA | 2019 sampai 2022', 'Mengembangkan dasar pengetahuan akademik, kedisiplinan, komunikasi, dan kemampuan bekerja sama dalam kegiatan sekolah.', 2);

TRUNCATE TABLE experiences;
INSERT INTO experiences (title, period, description, sort_order) VALUES
('Pengembangan Website', 'Pemrograman Internet | 2026', 'Membuat halaman CV berbasis web menggunakan AdminLTE, Bootstrap, HTML, CSS, dan Font Awesome. Proyek ini menampilkan data diri, pendidikan, pengalaman, portofolio, dan kontak pribadi.', 1),
('Kegiatan Akademik', '2024 sampai Sekarang', 'Mengikuti kegiatan akademik dan nonakademik untuk melatih komunikasi, tanggung jawab, manajemen waktu, dan kerja sama tim.', 2);

TRUNCATE TABLE skills;
INSERT INTO skills (name, icon, sort_order) VALUES
('HTML', 'fab fa-html5', 1),
('CSS', 'fab fa-css3-alt', 2),
('JavaScript', 'fab fa-js', 3),
('Bootstrap', 'fab fa-bootstrap', 4),
('AdminLTE', 'fas fa-th-large', 5),
('GitHub', 'fab fa-github', 6),
('Microsoft Word', 'fas fa-file-word', 7),
('Desain Web', 'fas fa-paint-brush', 8);

INSERT INTO portfolios (id, title, description)
VALUES (1, 'Website Curriculum Vitae Online', 'Website CV online dengan tampilan modern, elegan, responsif, dan mudah dibaca. Website ini dibuat sebagai tugas mata kuliah Pemrograman Internet.')
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description);

INSERT INTO statistics (id, skill_count, project_count, cv_count)
VALUES (1, '8+', '2', '1')
ON DUPLICATE KEY UPDATE skill_count = VALUES(skill_count), project_count = VALUES(project_count), cv_count = VALUES(cv_count);
