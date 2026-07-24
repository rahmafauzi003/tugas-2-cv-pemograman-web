<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function defaultCvData(): array
{
    return [
        'profile' => [
            'id' => 1,
            'full_name' => 'Rahma Fauzi Nurul Islam',
            'headline' => 'Mahasiswa Teknik Informatika',
            'role' => 'Mahasiswa Aktif',
            'nim' => '301230056',
            'study_program' => 'Teknik Informatika',
            'email' => 'rahmafauzi003@gmail.com',
            'phone' => '08837468782',
            'location' => 'Bandung, Indonesia',
            'github' => 'github.com/rahmafauzi',
            'photo_path' => 'assets/img/profile.svg',
            'summary' => 'Saya adalah mahasiswa yang memiliki ketertarikan pada bidang teknologi informasi, khususnya pengembangan web, desain antarmuka, dan pemrograman internet. Saya memiliki kemampuan dasar dalam membuat halaman web menggunakan HTML, CSS, Bootstrap, AdminLTE, dan JavaScript. Saya terbiasa bekerja secara terstruktur, teliti, dan memiliki motivasi untuk terus meningkatkan kemampuan di bidang teknologi digital.',
            'updated_at' => null,
        ],
        'educations' => [
            [
                'institution' => 'Universitas Bale Bandung',
                'major_period' => 'Teknik Informatika | 2023 sampai Sekarang',
                'description' => 'Mempelajari dasar pemrograman, pengembangan web, basis data, jaringan komputer, desain antarmuka, dan aplikasi berbasis internet.',
            ],
            [
                'institution' => 'MA Al-Huda Pameungpeuk',
                'major_period' => 'MIPA | 2019 sampai 2022',
                'description' => 'Mengembangkan dasar pengetahuan akademik, kedisiplinan, komunikasi, dan kemampuan bekerja sama dalam kegiatan sekolah.',
            ],
        ],
        'experiences' => [
            [
                'title' => 'Pengembangan Website',
                'period' => 'Pemrograman Internet | 2026',
                'description' => 'Membuat halaman CV berbasis web menggunakan AdminLTE, Bootstrap, HTML, CSS, dan Font Awesome. Proyek ini menampilkan data diri, pendidikan, pengalaman, portofolio, dan kontak pribadi.',
            ],
            [
                'title' => 'Kegiatan Akademik',
                'period' => '2024 sampai Sekarang',
                'description' => 'Mengikuti kegiatan akademik dan nonakademik untuk melatih komunikasi, tanggung jawab, manajemen waktu, dan kerja sama tim.',
            ],
        ],
        'skills' => [
            ['name' => 'HTML', 'icon' => 'fab fa-html5'],
            ['name' => 'CSS', 'icon' => 'fab fa-css3-alt'],
            ['name' => 'JavaScript', 'icon' => 'fab fa-js'],
            ['name' => 'Bootstrap', 'icon' => 'fab fa-bootstrap'],
            ['name' => 'AdminLTE', 'icon' => 'fas fa-th-large'],
            ['name' => 'GitHub', 'icon' => 'fab fa-github'],
            ['name' => 'Microsoft Word', 'icon' => 'fas fa-file-word'],
            ['name' => 'Desain Web', 'icon' => 'fas fa-paint-brush'],
        ],
        'portfolio' => [
            'title' => 'Website Curriculum Vitae Online',
            'description' => 'Website CV online dengan tampilan modern, elegan, responsif, dan mudah dibaca. Website ini dibuat sebagai tugas mata kuliah Pemrograman Internet.',
        ],
        'stats' => [
            'skill_count' => '8+',
            'project_count' => '2',
            'cv_count' => '1',
        ],
    ];
}

function fetchCvData(): array
{
    $defaults = defaultCvData();

    try {
        $pdo = getPDO();

        $profile = $pdo->query('SELECT * FROM profiles WHERE id = 1')->fetch();
        if (!$profile) {
            return $defaults;
        }

        $educations = $pdo->query('SELECT institution, major_period, description FROM educations ORDER BY sort_order, id')->fetchAll();
        $experiences = $pdo->query('SELECT title, period, description FROM experiences ORDER BY sort_order, id')->fetchAll();
        $skills = $pdo->query('SELECT name, icon FROM skills ORDER BY sort_order, id')->fetchAll();
        $portfolio = $pdo->query('SELECT title, description FROM portfolios WHERE id = 1')->fetch();
        $stats = $pdo->query('SELECT skill_count, project_count, cv_count FROM statistics WHERE id = 1')->fetch();

        return [
            'profile' => $profile,
            'educations' => $educations ?: $defaults['educations'],
            'experiences' => $experiences ?: $defaults['experiences'],
            'skills' => $skills ?: $defaults['skills'],
            'portfolio' => $portfolio ?: $defaults['portfolio'],
            'stats' => $stats ?: $defaults['stats'],
        ];
    } catch (Throwable $error) {
        // Data bawaan menjaga halaman CV tetap dapat dilihat sebelum database diimpor.
        return $defaults;
    }
}

function cleanText(mixed $value, int $maxLength = 5000): string
{
    $text = trim((string) $value);
    return function_exists('mb_substr') ? mb_substr($text, 0, $maxLength) : substr($text, 0, $maxLength);
}

function redirectWithStatus(string $status): never
{
    header('Location: edit.php?status=' . urlencode($status));
    exit;
}
