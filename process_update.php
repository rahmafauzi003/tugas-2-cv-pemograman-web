<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: edit.php');
    exit;
}

$token = (string) ($_POST['csrf_token'] ?? '');
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    redirectWithStatus('invalid');
}

$fullName = cleanText($_POST['full_name'] ?? '', 150);
$headline = cleanText($_POST['headline'] ?? '', 150);
$nim = cleanText($_POST['nim'] ?? '', 30);
$summary = cleanText($_POST['summary'] ?? '', 3000);

if ($fullName === '' || $headline === '' || $nim === '' || $summary === '') {
    redirectWithStatus('invalid');
}

$educations = is_array($_POST['educations'] ?? null) ? $_POST['educations'] : [];
$experiences = is_array($_POST['experiences'] ?? null) ? $_POST['experiences'] : [];
$skills = is_array($_POST['skills'] ?? null) ? $_POST['skills'] : [];

try {
    $pdo = getPDO();
    $pdo->beginTransaction();

    $profileStatement = $pdo->prepare(
        'UPDATE profiles SET
            full_name = :full_name,
            headline = :headline,
            role = :role,
            nim = :nim,
            study_program = :study_program,
            email = :email,
            phone = :phone,
            location = :location,
            github = :github,
            photo_path = :photo_path,
            summary = :summary
         WHERE id = 1'
    );

    $profileStatement->execute([
        'full_name' => $fullName,
        'headline' => $headline,
        'role' => cleanText($_POST['role'] ?? '', 100),
        'nim' => $nim,
        'study_program' => cleanText($_POST['study_program'] ?? '', 100),
        'email' => cleanText($_POST['email'] ?? '', 150),
        'phone' => cleanText($_POST['phone'] ?? '', 50),
        'location' => cleanText($_POST['location'] ?? '', 150),
        'github' => cleanText($_POST['github'] ?? '', 200),
        'photo_path' => cleanText($_POST['photo_path'] ?? 'assets/img/profile.svg', 255),
        'summary' => $summary,
    ]);

    $pdo->exec('DELETE FROM educations');
    $educationStatement = $pdo->prepare('INSERT INTO educations (institution, major_period, description, sort_order) VALUES (?, ?, ?, ?)');
    foreach ($educations as $index => $education) {
        $institution = cleanText($education['institution'] ?? '', 180);
        if ($institution === '') continue;
        $educationStatement->execute([
            $institution,
            cleanText($education['major_period'] ?? '', 180),
            cleanText($education['description'] ?? '', 2000),
            (int) $index + 1,
        ]);
    }

    $pdo->exec('DELETE FROM experiences');
    $experienceStatement = $pdo->prepare('INSERT INTO experiences (title, period, description, sort_order) VALUES (?, ?, ?, ?)');
    foreach ($experiences as $index => $experience) {
        $title = cleanText($experience['title'] ?? '', 180);
        if ($title === '') continue;
        $experienceStatement->execute([
            $title,
            cleanText($experience['period'] ?? '', 180),
            cleanText($experience['description'] ?? '', 2000),
            (int) $index + 1,
        ]);
    }

    $pdo->exec('DELETE FROM skills');
    $skillStatement = $pdo->prepare('INSERT INTO skills (name, icon, sort_order) VALUES (?, ?, ?)');
    foreach ($skills as $index => $skill) {
        $name = cleanText($skill['name'] ?? '', 100);
        if ($name === '') continue;
        $skillStatement->execute([
            $name,
            cleanText($skill['icon'] ?? 'fas fa-code', 100) ?: 'fas fa-code',
            (int) $index + 1,
        ]);
    }

    $portfolioStatement = $pdo->prepare(
        'INSERT INTO portfolios (id, title, description) VALUES (1, :title, :description)
         ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description)'
    );
    $portfolioStatement->execute([
        'title' => cleanText($_POST['portfolio_title'] ?? '', 180),
        'description' => cleanText($_POST['portfolio_description'] ?? '', 2000),
    ]);

    $statsStatement = $pdo->prepare(
        'INSERT INTO statistics (id, skill_count, project_count, cv_count) VALUES (1, :skill_count, :project_count, :cv_count)
         ON DUPLICATE KEY UPDATE skill_count = VALUES(skill_count), project_count = VALUES(project_count), cv_count = VALUES(cv_count)'
    );
    $statsStatement->execute([
        'skill_count' => cleanText($_POST['skill_count'] ?? '', 20),
        'project_count' => cleanText($_POST['project_count'] ?? '', 20),
        'cv_count' => cleanText($_POST['cv_count'] ?? '', 20),
    ]);

    $pdo->commit();
    redirectWithStatus('success');
} catch (Throwable $error) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    redirectWithStatus('error');
}
