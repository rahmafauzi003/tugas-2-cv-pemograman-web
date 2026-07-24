<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/includes/functions.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$data = fetchCvData();
$profile = $data['profile'];
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit CV - <?= e($profile['full_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="editor-page">
    <header class="editor-header">
        <div>
            <h1><i class="fas fa-user-edit mr-2"></i>Edit Data Curriculum Vitae</h1>
            <p>Perbarui data di bawah ini, kemudian tekan tombol Simpan Perubahan.</p>
        </div>
        <a href="index.php" class="btn btn-light"><i class="fas fa-arrow-left mr-1"></i> Kembali ke CV</a>
    </header>

    <?php if ($status === 'success'): ?>
        <div class="status-message status-success"><i class="fas fa-check-circle mr-2"></i>Data CV berhasil diperbarui dan disimpan ke database.</div>
    <?php elseif ($status === 'error'): ?>
        <div class="status-message status-error"><i class="fas fa-exclamation-circle mr-2"></i>Data belum berhasil disimpan. Pastikan database sudah diimpor dan konfigurasi koneksi benar.</div>
    <?php elseif ($status === 'invalid'): ?>
        <div class="status-message status-error"><i class="fas fa-exclamation-circle mr-2"></i>Data wajib belum lengkap atau sesi formulir tidak valid.</div>
    <?php endif; ?>

    <form action="process_update.php" method="post" class="editor-form">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <section class="editor-card">
            <h2><i class="fas fa-id-card"></i>Identitas dan Kontak</h2>
            <div class="form-grid">
                <div class="form-group"><label>Nama Lengkap *</label><input class="form-control" name="full_name" required maxlength="150" value="<?= e($profile['full_name']) ?>"></div>
                <div class="form-group"><label>Headline *</label><input class="form-control" name="headline" required maxlength="150" value="<?= e($profile['headline']) ?>"></div>
                <div class="form-group"><label>Status/Peran</label><input class="form-control" name="role" maxlength="100" value="<?= e($profile['role']) ?>"></div>
                <div class="form-group"><label>NIM *</label><input class="form-control" name="nim" required maxlength="30" value="<?= e($profile['nim']) ?>"></div>
                <div class="form-group"><label>Program Studi</label><input class="form-control" name="study_program" maxlength="100" value="<?= e($profile['study_program']) ?>"></div>
                <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" maxlength="150" value="<?= e($profile['email']) ?>"></div>
                <div class="form-group"><label>Nomor Telepon</label><input class="form-control" name="phone" maxlength="50" value="<?= e($profile['phone']) ?>"></div>
                <div class="form-group"><label>Lokasi</label><input class="form-control" name="location" maxlength="150" value="<?= e($profile['location']) ?>"></div>
                <div class="form-group"><label>GitHub</label><input class="form-control" name="github" maxlength="200" value="<?= e($profile['github']) ?>"></div>
                <div class="form-group"><label>Path/URL Foto</label><input class="form-control" name="photo_path" maxlength="255" value="<?= e($profile['photo_path']) ?>"><div class="help-text">Contoh: assets/img/profile.svg atau URL gambar.</div></div>
                <div class="form-group full"><label>Profil Singkat *</label><textarea class="form-control" name="summary" required rows="5" maxlength="3000"><?= e($profile['summary']) ?></textarea></div>
            </div>
        </section>

        <section class="editor-card">
            <h2><i class="fas fa-graduation-cap"></i>Pendidikan</h2>
            <div id="education-list" class="repeat-list" data-repeat-container data-group="educations">
                <?php foreach ($data['educations'] as $index => $education): ?>
                    <div class="repeat-item">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-remove-item><i class="fas fa-trash"></i></button>
                        <div class="form-grid">
                            <div class="form-group"><label>Institusi *</label><input class="form-control" data-field="institution" name="educations[<?= $index ?>][institution]" required value="<?= e($education['institution']) ?>"></div>
                            <div class="form-group"><label>Jurusan dan Periode</label><input class="form-control" data-field="major_period" name="educations[<?= $index ?>][major_period]" value="<?= e($education['major_period']) ?>"></div>
                            <div class="form-group full"><label>Deskripsi</label><textarea class="form-control" data-field="description" name="educations[<?= $index ?>][description]" rows="3"><?= e($education['description']) ?></textarea></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-outline-primary mt-3" data-add-target="#education-list" data-template="#education-template"><i class="fas fa-plus mr-1"></i>Tambah Pendidikan</button>
        </section>

        <section class="editor-card">
            <h2><i class="fas fa-briefcase"></i>Pengalaman</h2>
            <div id="experience-list" class="repeat-list" data-repeat-container data-group="experiences">
                <?php foreach ($data['experiences'] as $index => $experience): ?>
                    <div class="repeat-item">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-remove-item><i class="fas fa-trash"></i></button>
                        <div class="form-grid">
                            <div class="form-group"><label>Judul Pengalaman *</label><input class="form-control" data-field="title" name="experiences[<?= $index ?>][title]" required value="<?= e($experience['title']) ?>"></div>
                            <div class="form-group"><label>Periode</label><input class="form-control" data-field="period" name="experiences[<?= $index ?>][period]" value="<?= e($experience['period']) ?>"></div>
                            <div class="form-group full"><label>Deskripsi</label><textarea class="form-control" data-field="description" name="experiences[<?= $index ?>][description]" rows="3"><?= e($experience['description']) ?></textarea></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-outline-primary mt-3" data-add-target="#experience-list" data-template="#experience-template"><i class="fas fa-plus mr-1"></i>Tambah Pengalaman</button>
        </section>

        <section class="editor-card">
            <h2><i class="fas fa-code"></i>Keahlian</h2>
            <div id="skill-list" class="repeat-list" data-repeat-container data-group="skills">
                <?php foreach ($data['skills'] as $index => $skill): ?>
                    <div class="repeat-item">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-remove-item><i class="fas fa-trash"></i></button>
                        <div class="form-grid">
                            <div class="form-group"><label>Nama Keahlian *</label><input class="form-control" data-field="name" name="skills[<?= $index ?>][name]" required value="<?= e($skill['name']) ?>"></div>
                            <div class="form-group"><label>Kelas Ikon Font Awesome</label><input class="form-control" data-field="icon" name="skills[<?= $index ?>][icon]" value="<?= e($skill['icon']) ?>"><div class="help-text">Contoh: fab fa-html5 atau fas fa-code</div></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-outline-primary mt-3" data-add-target="#skill-list" data-template="#skill-template"><i class="fas fa-plus mr-1"></i>Tambah Keahlian</button>
        </section>

        <section class="editor-card">
            <h2><i class="fas fa-folder-open"></i>Portofolio dan Statistik</h2>
            <div class="form-grid">
                <div class="form-group full"><label>Judul Portofolio</label><input class="form-control" name="portfolio_title" value="<?= e($data['portfolio']['title']) ?>"></div>
                <div class="form-group full"><label>Deskripsi Portofolio</label><textarea class="form-control" name="portfolio_description" rows="4"><?= e($data['portfolio']['description']) ?></textarea></div>
                <div class="form-group"><label>Jumlah Keahlian</label><input class="form-control" name="skill_count" value="<?= e($data['stats']['skill_count']) ?>"></div>
                <div class="form-group"><label>Jumlah Proyek</label><input class="form-control" name="project_count" value="<?= e($data['stats']['project_count']) ?>"></div>
                <div class="form-group"><label>Jumlah CV</label><input class="form-control" name="cv_count" value="<?= e($data['stats']['cv_count']) ?>"></div>
            </div>
        </section>

        <div class="editor-actions">
            <a href="index.php" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>

<template id="education-template">
    <div class="repeat-item">
        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-remove-item><i class="fas fa-trash"></i></button>
        <div class="form-grid">
            <div class="form-group"><label>Institusi *</label><input class="form-control" data-field="institution" required></div>
            <div class="form-group"><label>Jurusan dan Periode</label><input class="form-control" data-field="major_period"></div>
            <div class="form-group full"><label>Deskripsi</label><textarea class="form-control" data-field="description" rows="3"></textarea></div>
        </div>
    </div>
</template>
<template id="experience-template">
    <div class="repeat-item">
        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-remove-item><i class="fas fa-trash"></i></button>
        <div class="form-grid">
            <div class="form-group"><label>Judul Pengalaman *</label><input class="form-control" data-field="title" required></div>
            <div class="form-group"><label>Periode</label><input class="form-control" data-field="period"></div>
            <div class="form-group full"><label>Deskripsi</label><textarea class="form-control" data-field="description" rows="3"></textarea></div>
        </div>
    </div>
</template>
<template id="skill-template">
    <div class="repeat-item">
        <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-remove-item><i class="fas fa-trash"></i></button>
        <div class="form-grid">
            <div class="form-group"><label>Nama Keahlian *</label><input class="form-control" data-field="name" required></div>
            <div class="form-group"><label>Kelas Ikon Font Awesome</label><input class="form-control" data-field="icon" value="fas fa-code"></div>
        </div>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="assets/js/edit.js"></script>
</body>
</html>
