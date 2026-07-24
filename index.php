<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
$data = fetchCvData();
$profile = $data['profile'];
$githubUrl = preg_match('~^https?://~i', $profile['github']) ? $profile['github'] : 'https://' . $profile['github'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV Modern - <?= e($profile['full_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="main-wrapper">
    <section class="hero">
        <div class="hero-top">
            <h4>Curriculum Vitae</h4>
            <div class="action-group">
                <span class="badge-task"><i class="fas fa-database mr-1"></i> Data tersimpan di database</span>
                <a class="btn-edit-cv" href="edit.php"><i class="fas fa-edit mr-1"></i> Edit CV</a>
            </div>
        </div>
        <h1><?= e($profile['full_name']) ?></h1>
        <h2><?= e($profile['headline']) ?></h2>
        <p>CV berbasis web yang dapat diperbarui melalui halaman editor. Setiap perubahan disimpan ke basis data dan langsung ditampilkan kembali pada halaman utama.</p>
    </section>

    <section class="profile-card">
        <img src="<?= e($profile['photo_path']) ?>" alt="Foto Profil <?= e($profile['full_name']) ?>" class="profile-img" onerror="this.src='assets/img/profile.svg'">
        <div class="profile-info">
            <h3><?= e($profile['full_name']) ?></h3>
            <span class="role"><?= e($profile['role']) ?></span>
            <div class="quick-info">
                <div class="quick-item"><i class="fas fa-id-card"></i><span>NIM: <?= e($profile['nim']) ?></span></div>
                <div class="quick-item"><i class="fas fa-university"></i><span>Program Studi: <?= e($profile['study_program']) ?></span></div>
                <div class="quick-item"><i class="fas fa-envelope"></i><span><?= e($profile['email']) ?></span></div>
                <div class="quick-item"><i class="fas fa-phone"></i><span><?= e($profile['phone']) ?></span></div>
                <div class="quick-item"><i class="fas fa-map-marker-alt"></i><span><?= e($profile['location']) ?></span></div>
                <div class="quick-item"><i class="fab fa-github"></i><a href="<?= e($githubUrl) ?>" target="_blank" rel="noopener noreferrer"><?= e($profile['github']) ?></a></div>
            </div>
        </div>
    </section>

    <main class="content">
        <section class="section">
            <div class="section-title"><div class="icon"><i class="fas fa-user"></i></div><h3>Profil Singkat</h3></div>
            <div class="about-card"><p><?= e($profile['summary']) ?></p></div>
        </section>

        <section class="section">
            <div class="section-title"><div class="icon"><i class="fas fa-graduation-cap"></i></div><h3>Pendidikan</h3></div>
            <div class="two-column">
                <?php foreach ($data['educations'] as $education): ?>
                    <div class="info-card">
                        <h4><?= e($education['institution']) ?></h4>
                        <span class="meta"><?= e($education['major_period']) ?></span>
                        <p><?= e($education['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section">
            <div class="section-title"><div class="icon"><i class="fas fa-briefcase"></i></div><h3>Pengalaman</h3></div>
            <div class="two-column">
                <?php foreach ($data['experiences'] as $experience): ?>
                    <div class="info-card">
                        <h4><?= e($experience['title']) ?></h4>
                        <span class="meta"><?= e($experience['period']) ?></span>
                        <p><?= e($experience['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section">
            <div class="section-title"><div class="icon"><i class="fas fa-code"></i></div><h3>Keahlian</h3></div>
            <div class="skill-grid">
                <?php foreach ($data['skills'] as $skill): ?>
                    <div class="skill-box"><i class="<?= e($skill['icon']) ?>"></i><span><?= e($skill['name']) ?></span></div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section">
            <div class="section-title"><div class="icon"><i class="fas fa-folder-open"></i></div><h3>Portofolio</h3></div>
            <div class="portfolio-card">
                <div><h4><?= e($data['portfolio']['title']) ?></h4><p><?= e($data['portfolio']['description']) ?></p></div>
                <div class="portfolio-icon"><i class="fas fa-laptop-code"></i></div>
            </div>
            <div class="stats">
                <div class="stat-box"><h3><?= e($data['stats']['skill_count']) ?></h3><p>Keahlian Dasar</p></div>
                <div class="stat-box"><h3><?= e($data['stats']['project_count']) ?></h3><p>Proyek Web</p></div>
                <div class="stat-box"><h3><?= e($data['stats']['cv_count']) ?></h3><p>CV Online</p></div>
            </div>
        </section>
    </main>

    <footer class="footer">© 2026 <?= e($profile['full_name']) ?>. Dibuat menggunakan PHP, MySQL, Bootstrap, dan AdminLTE untuk Tugas Pemrograman Internet.</footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
