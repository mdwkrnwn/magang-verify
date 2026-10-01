<?php

require_once __DIR__ . '/../../function/Helpers.php';

function icon($name, $class = 'w-5 h-5') {
    static $p = [
        'search' => 'm21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z',
        'filter' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-9.75 0h9.75',
        'arrow' => 'M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3',
        'left' => 'M15.75 19.5 8.25 12l7.5-7.5',
        'right' => 'm8.25 4.5 7.5 7.5-7.5 7.5',
        'shield' => 'M9 12.75 11.25 15 15 9.75m-3-7A12 12 0 0 1 3.6 6 12 12 0 0 0 3 9.75c0 5.6 3.8 10.3 9 11.6 5.2-1.3 9-6 9-11.6 0-1.3-.2-2.6-.6-3.75h-.15A12 12 0 0 1 12 2.7Z',
        'briefcase' => 'M3.75 7.5h16.5v11.25H3.75zM8.25 7.5V5.25h7.5V7.5M3.75 12.75h16.5',
        'award' => 'M12 3.75a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9ZM8.5 12.5l-1 7.75L12 18l4.5 2.25-1-7.75',
        'user' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0',
        'folder' => 'M3.75 6.75h6l1.5 2.25h9v9.75h-16.5z',
        'layers' => 'M12 3 3 8l9 5 9-5-9-5ZM3 12.5l9 5 9-5M3 16.5l9 5 9-5',
        'bolt' => 'm13.5 3-9 11.25h6.75L10.5 21l9-11.25h-6.75z',
        'mail' => 'M3.75 6.75h16.5v10.5H3.75zM3.75 7.5 12 13.5l8.25-6',
        'pin' => 'M12 21s-6.75-5.5-6.75-11.25a6.75 6.75 0 1 1 13.5 0C18.75 15.5 12 21 12 21ZM12 12a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z',
        'link' => 'M13.5 10.5a3.5 3.5 0 0 0-5 0l-3 3a3.5 3.5 0 0 0 5 5l1-1M10.5 13.5a3.5 3.5 0 0 0 5 0l3-3a3.5 3.5 0 0 0-5-5l-1 1',
        'external' => 'M13.5 6H18v4.5M18 6l-7.5 7.5M16.5 13.5v4.5h-11V7h4.5',
        'download' => 'M12 3.75v11.25m0 0-4.5-4.5m4.5 4.5 4.5-4.5M4.5 19.5h15',
        'globe' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM3 12h18M12 3c2.5 2.5 3.75 5.5 3.75 9S14.5 18.5 12 21c-2.5-2.5-3.75-5.5-3.75-9S9.5 5.5 12 3Z',
        'code' => 'm8.25 8.25-4.5 3.75 4.5 3.75m7.5-7.5 4.5 3.75-4.5 3.75',
        'instagram' => 'M7.5 3.75h9a3.75 3.75 0 0 1 3.75 3.75v9a3.75 3.75 0 0 1-3.75 3.75h-9a3.75 3.75 0 0 1-3.75-3.75v-9A3.75 3.75 0 0 1 7.5 3.75ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z',
        'youtube' => 'M3 8.25a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v7.5a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-7.5ZM10.5 9.5v5l4-2.5-4-2.5Z',
    ];

    return '<svg class="' . $class . '" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="' . ($p[$name] ?? '') . '"/></svg>';
}

function foto($m)
{
    $slug = strtolower(
        str_replace(' ', '', $m['nama'])
    );

    // Jika ada URL foto yang diberikan langsung
    if (!empty($m['foto'])) {
        return $m['foto'];
    }

    // Jika ada file foto lokal berdasarkan nama mahasiswa
    $fotoPath = __DIR__ .
        "/../assets/images/mahasiswa/$slug.jpg";

    if (is_file($fotoPath)) {
        return url(
            "/assets/images/mahasiswa/$slug.jpg"
        );
    }

    // Tidak ada foto
    return '';
}
$mahasiswa = [
    ['id'=>1,'nama'=>'Ahmad Rizki','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
     'skills'=>['Web Development','UI/UX','Cloud Computing','Problem Solving'],'proyek'=>5,'sertifikat'=>2,
     'bio'=>'Seorang mahasiswa yang tertarik pada pengembangan web dan teknologi cloud. Bersemangat untuk terus belajar dan berkontribusi dalam proyek yang berdampak.',
     'tentang'=>'Saya adalah mahasiswa D4 Teknik Informatika di Politeknik Negeri Malang yang memiliki minat besar pada pengembangan web, desain antarmuka, dan teknologi cloud. Saya terbiasa bekerja dalam tim, cepat belajar hal baru, dan senang memecahkan masalah melalui teknologi.',
     'domisili'=>'Malang, Jawa Timur','motto'=>'Terus belajar, terus bertumbuh',
     'keahlian'=>['HTML','CSS','JavaScript','React','Node.js','UI/UX Design','Git','Tailwind CSS'],
     'kontak'=>['github'=>'github.com/ahmadrizki','linkedin'=>'linkedin.com/in/ahmadrizki','website'=>'ahmadrizki.dev','email'=>'ahmadrizki@email.com'],
     'pengalaman'=>[['posisi'=>'Frontend Developer (Magang)','instansi'=>'PT. Semarsoft Technology Indonesia','periode'=>'Jun 2025 – Agu 2025',
        'tugas'=>['Mengembangkan fitur antarmuka web menggunakan React.js','Berkolaborasi dengan tim dalam pengembangan sistem internal']]],
     'daftar_proyek'=>[['nama'=>'Bakool','deskripsi'=>'Platform pencarian UMKM berbasis lokasi dengan fitur peta, ulasan, dan rekomendasi.','tech'=>['Next.js','Supabase','Leaflet'],'url'=>'#','gambar'=>'']],
     'daftar_sertifikat'=>[['nama'=>'Web Design Competency Certification – Level IV','penerbit'=>'LSP TIK','tanggal'=>'Mei 2025','verified'=>true]]],
    ['id'=>2,'nama'=>'Salsabila Putri','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2025,'skills'=>['Data Analysis','Machine Learning','Python'],'proyek'=>6,'sertifikat'=>3],
    ['id'=>3,'nama'=>'Farhan Maulana','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2024,'skills'=>['Mobile Development','Database','Flutter'],'proyek'=>4,'sertifikat'=>1],
    ['id'=>4,'nama'=>'Putri Ananda','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2024,'skills'=>['Business Analysis','Management','Scrum'],'proyek'=>5,'sertifikat'=>2],
    ['id'=>5,'nama'=>'Rafael Pratama','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2023,'skills'=>['DevOps','Cloud Computing','Docker','Linux'],'proyek'=>7,'sertifikat'=>4],
    ['id'=>6,'nama'=>'Nabila Rahma','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2023,'skills'=>['Cyber Security','Network','Linux'],'proyek'=>3,'sertifikat'=>2],
    ['id'=>7,'nama'=>'Dimas Saputra','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2025,'skills'=>['UI/UX','Product Design','Figma'],'proyek'=>4,'sertifikat'=>1],
    ['id'=>8,'nama'=>'Aisyah Fitri','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2024,'skills'=>['Artificial Intelligence','Data Science','Python','SQL'],'proyek'=>8,'sertifikat'=>5],
    ['id'=>9,'nama'=>'Raka Aditya','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
    'skills'=>['Web Development','Laravel','PHP','MySQL'],'proyek'=>4,'sertifikat'=>2],

   ['id'=>10,'nama'=>'Nadia Permata','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
    'skills'=>['UI/UX','Figma','JavaScript','Frontend Development'],'proyek'=>5,'sertifikat'=>3],

   ['id'=>11,'nama'=>'Bagas Prakoso','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2024,
    'skills'=>['Backend Development','Node.js','Express','PostgreSQL'],'proyek'=>6,'sertifikat'=>2],

   ['id'=>12,'nama'=>'Citra Maharani','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2024,
    'skills'=>['Data Science','Python','Machine Learning','Pandas'],'proyek'=>7,'sertifikat'=>4],

   ['id'=>13,'nama'=>'Fajar Ramadhan','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2023,
    'skills'=>['DevOps','Docker','Linux','CI/CD'],'proyek'=>6,'sertifikat'=>3],

   ['id'=>14,'nama'=>'Intan Lestari','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2023,
    'skills'=>['Mobile Development','Flutter','Dart','Firebase'],'proyek'=>5,'sertifikat'=>2],

   ['id'=>15,'nama'=>'Yoga Pratama','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2022,
    'skills'=>['Cyber Security','Network Security','Linux','Python'],'proyek'=>9,'sertifikat'=>5],

   ['id'=>16,'nama'=>'Maya Salsabila','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2022,
    'skills'=>['Cloud Computing','AWS','Docker','Kubernetes'],'proyek'=>8,'sertifikat'=>4],

   ['id'=>17,'nama'=>'Daffa Kurniawan','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
    'skills'=>['Mobile Development','Kotlin','Android','Firebase'],'proyek'=>3,'sertifikat'=>1],

   ['id'=>18,'nama'=>'Naufal Hakim','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2024,
    'skills'=>['Artificial Intelligence','Computer Vision','Python','TensorFlow'],'proyek'=>7,'sertifikat'=>3],

   ['id'=>19,'nama'=>'Siti Aulia','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2023,
    'skills'=>['Software Testing','Quality Assurance','API Testing','Postman'],'proyek'=>5,'sertifikat'=>2],

   ['id'=>20,'nama'=>'Reza Firmansyah','prodi'=>'D4 Teknik Informatika','jurusan'=>'Teknologi Informasi','angkatan'=>2022,
    'skills'=>['Web Development','React','Next.js','TypeScript'],'proyek'=>8,'sertifikat'=>4],


   // =========================================================
   // D4 SISTEM INFORMASI BISNIS
   // =========================================================

   ['id'=>21,'nama'=>'Aldi Saputra','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
    'skills'=>['Business Analysis','Microsoft Excel','SQL','Documentation'],'proyek'=>4,'sertifikat'=>2],

   ['id'=>22,'nama'=>'Bella Maharani','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
    'skills'=>['UI/UX','Figma','Product Design','User Research'],'proyek'=>5,'sertifikat'=>3],

   ['id'=>23,'nama'=>'Kevin Pratama','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2024,
    'skills'=>['Business Intelligence','Power BI','SQL','Data Analysis'],'proyek'=>7,'sertifikat'=>4],

   ['id'=>24,'nama'=>'Nisa Rahmawati','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2024,
    'skills'=>['Project Management','Scrum','Jira','Business Analysis'],'proyek'=>6,'sertifikat'=>3],

   ['id'=>25,'nama'=>'Rizky Maulana','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2023,
    'skills'=>['ERP','SAP','Business Process','Database'],'proyek'=>8,'sertifikat'=>4],

   ['id'=>26,'nama'=>'Anisa Putri','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2023,
    'skills'=>['Digital Marketing','SEO','Analytics','Content Strategy'],'proyek'=>5,'sertifikat'=>3],

   ['id'=>27,'nama'=>'Galih Ramadhan','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2022,
    'skills'=>['Business Intelligence','Tableau','Python','Data Visualization'],'proyek'=>9,'sertifikat'=>5],

   ['id'=>28,'nama'=>'Vina Oktaviani','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2022,
    'skills'=>['Product Management','Figma','Agile','Market Research'],'proyek'=>7,'sertifikat'=>4],

   ['id'=>29,'nama'=>'Arif Hidayat','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2025,
    'skills'=>['Database','SQL','Business Analysis','Power BI'],'proyek'=>4,'sertifikat'=>2],

   ['id'=>30,'nama'=>'Salsa Amelia','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2024,
    'skills'=>['UI/UX','Figma','Business Process','Prototyping'],'proyek'=>6,'sertifikat'=>3],

   ['id'=>31,'nama'=>'Fikri Akbar','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2023,
    'skills'=>['ERP','Oracle','SQL','Business Process'],'proyek'=>8,'sertifikat'=>4],

   ['id'=>32,'nama'=>'Dinda Maharani','prodi'=>'D4 Sistem Informasi Bisnis','jurusan'=>'Teknologi Informasi','angkatan'=>2022,
    'skills'=>['Data Analysis','Python','Excel','Power BI'],'proyek'=>7,'sertifikat'=>5],
];

$extra = [

    // =========================================================
    // D4 TEKNIK INFORMATIKA
    // =========================================================

    2 => [
        'motto' => 'Data bercerita, kita yang menyimak',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang fokus pada analisis data dan machine learning. Terbiasa mengolah data mentah menjadi wawasan yang mudah dipahami tim.',
        'keahlian' => ['Python','Pandas','SQL','Scikit-learn','Power BI','Jupyter'],
        'pengalaman' => [
            ['Data Analyst (Magang)','PT. Data Nusantara','Jul 2025 – Sep 2025',[
                'Membersihkan dan menganalisis data penjualan bulanan',
                'Menyusun dashboard ringkasan untuk tim manajemen'
            ]]
        ],
        'proyek' => [
            'PrediksiHarga',
            'Model prediksi harga rumah berbasis regresi dengan antarmuka web sederhana.',
            ['Python','Flask','Scikit-learn']
        ],
        'sertifikat' => [
            'Data Analysis with Python',
            'Dicoding',
            'Apr 2025'
        ]
    ],

    3 => [
        'motto' => 'Satu aplikasi, seribu kemungkinan',
        'tentang' => 'Mahasiswa yang menyukai pengembangan aplikasi mobile lintas platform dan perancangan database yang rapi.',
        'keahlian' => ['Flutter','Dart','Firebase','MySQL','REST API','Git'],
        'pengalaman' => [
            ['Mobile Developer (Magang)','CV. Lintas Aplikasi','Jun 2025 – Agu 2025',[
                'Membangun fitur katalog produk pada aplikasi Flutter',
                'Menghubungkan aplikasi dengan REST API internal'
            ]]
        ],
        'proyek' => [
            'JadwalKu',
            'Aplikasi pengingat jadwal kuliah dengan notifikasi harian.',
            ['Flutter','Firebase']
        ],
        'sertifikat' => [
            'Flutter Developer Fundamentals',
            'Dicoding',
            'Mar 2025'
        ]
    ],

    4 => [
        'motto' => 'Bisnis dan teknologi berjalan berdampingan',
        'tentang' => 'Mahasiswa Sistem Informasi Bisnis yang tertarik menjembatani kebutuhan bisnis dengan solusi teknologi, dari analisis proses hingga manajemen proyek.',
        'keahlian' => ['Business Analysis','Scrum','Jira','Figma','Excel','Dokumentasi'],
        'pengalaman' => [
            ['Business Analyst (Magang)','PT. Solusi Digital Malang','Jun 2025 – Agu 2025',[
                'Memetakan alur proses bisnis klien dalam bentuk diagram',
                'Menyusun dokumen kebutuhan sistem bersama tim developer'
            ]]
        ],
        'proyek' => [
            'SIMKoperasi',
            'Sistem informasi simpan pinjam untuk koperasi kampus.',
            ['Laravel','MySQL','Bootstrap']
        ],
        'sertifikat' => [
            'Scrum Fundamentals',
            'Scrum Study',
            'Mei 2025'
        ]
    ],

    5 => [
        'motto' => 'Otomatiskan yang bisa diotomatiskan',
        'tentang' => 'Mahasiswa dengan minat pada DevOps dan infrastruktur cloud, terbiasa membangun pipeline CI/CD dan mengelola container.',
        'keahlian' => ['Docker','Linux','GitHub Actions','AWS','Nginx','Bash'],
        'pengalaman' => [
            ['DevOps Intern','PT. Cloud Kreasi','Jun 2025 – Sep 2025',[
                'Membuat pipeline CI/CD untuk deployment otomatis',
                'Mengelola container aplikasi di lingkungan staging'
            ]]
        ],
        'proyek' => [
            'DeployKit',
            'Template pipeline CI/CD untuk aplikasi web berbasis Docker.',
            ['Docker','GitHub Actions','AWS']
        ],
        'sertifikat' => [
            'AWS Cloud Practitioner',
            'AWS',
            'Feb 2025'
        ]
    ],

    6 => [
        'motto' => 'Aman itu kebiasaan',
        'tentang' => 'Mahasiswa yang tertarik pada keamanan jaringan dan pengujian penetrasi, aktif mengikuti kompetisi CTF tingkat kampus.',
        'keahlian' => ['Network Security','Wireshark','Kali Linux','Nmap','Linux','Python'],
        'pengalaman' => [
            ['Security Intern','PT. Amankan Data','Jul 2025 – Sep 2025',[
                'Membantu audit keamanan aplikasi web internal',
                'Menyusun laporan temuan kerentanan beserta rekomendasinya'
            ]]
        ],
        'proyek' => [
            'NetGuard',
            'Alat pemantau lalu lintas jaringan sederhana untuk laboratorium kampus.',
            ['Python','Wireshark']
        ],
        'sertifikat' => [
            'Network Security Essentials',
            'Cisco',
            'Apr 2025'
        ]
    ],

    7 => [
        'motto' => 'Desain yang baik terasa mudah',
        'tentang' => 'Mahasiswa yang menyukai riset pengguna dan perancangan produk digital, dari wireframe sampai prototipe interaktif.',
        'keahlian' => ['Figma','User Research','Wireframing','Prototyping','Design System','HTML/CSS'],
        'pengalaman' => [
            ['UI/UX Designer (Magang)','Studio Rupa Digital','Jun 2025 – Agu 2025',[
                'Merancang alur dan antarmuka aplikasi pemesanan',
                'Melakukan uji kegunaan dengan 10 pengguna'
            ]]
        ],
        'proyek' => [
            'Pesanin',
            'Rancangan aplikasi pemesanan makanan kampus dengan prototipe Figma.',
            ['Figma','Maze']
        ],
        'sertifikat' => [
            'Google UX Design Certificate',
            'Coursera',
            'Jan 2025'
        ]
    ],

    8 => [
        'motto' => 'Kecerdasan buatan untuk kebaikan',
        'tentang' => 'Mahasiswa dengan minat pada kecerdasan buatan dan data science, aktif membangun model untuk masalah nyata di sekitar kampus.',
        'keahlian' => ['Python','TensorFlow','SQL','Computer Vision','NLP','Streamlit'],
        'pengalaman' => [
            ['AI Engineer (Magang)','PT. Cerdas Nusantara','Jun 2025 – Sep 2025',[
                'Melatih model klasifikasi gambar untuk kontrol kualitas',
                'Menyajikan hasil model lewat aplikasi Streamlit'
            ]]
        ],
        'proyek' => [
            'SampahLens',
            'Aplikasi pengenal jenis sampah dari foto menggunakan model klasifikasi gambar.',
            ['Python','TensorFlow','Streamlit']
        ],
        'sertifikat' => [
            'TensorFlow Developer Certificate',
            'Google',
            'Mar 2025'
        ]
    ],

    9 => [
        'motto' => 'Bangun web yang sederhana dan bermanfaat',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang memiliki minat pada pengembangan aplikasi web menggunakan PHP, Laravel, dan teknologi basis data.',
        'keahlian' => ['PHP','Laravel','MySQL','JavaScript','HTML','CSS','Git'],
        'pengalaman' => [
            ['Web Developer (Magang)','PT. Digital Karya Nusantara','Jun 2025 – Agu 2025',[
                'Mengembangkan fitur aplikasi web menggunakan Laravel',
                'Membuat dan mengelola struktur database MySQL'
            ]]
        ],
        'proyek' => [
            'MagangHub',
            'Aplikasi pengelolaan lowongan dan pengajuan magang mahasiswa.',
            ['Laravel','MySQL','Tailwind CSS']
        ],
        'sertifikat' => [
            'Belajar Dasar Pemrograman Web',
            'Dicoding',
            'Mei 2025'
        ]
    ],

    10 => [
        'motto' => 'Desain dimulai dari memahami pengguna',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang tertarik pada UI/UX dan pengembangan antarmuka web modern. Senang mengubah kebutuhan pengguna menjadi desain yang sederhana dan mudah digunakan.',
        'keahlian' => ['UI/UX','Figma','JavaScript','HTML','CSS','Prototyping','User Research'],
        'pengalaman' => [
            ['UI/UX Designer (Magang)','PT. Kreasi Digital Indonesia','Jun 2025 – Agu 2025',[
                'Membuat wireframe dan prototype aplikasi web',
                'Melakukan evaluasi desain berdasarkan masukan pengguna'
            ]]
        ],
        'proyek' => [
            'CampusSpace',
            'Desain platform digital untuk membantu mahasiswa menemukan fasilitas kampus.',
            ['Figma','JavaScript','Tailwind CSS']
        ],
        'sertifikat' => [
            'UI/UX Design Fundamentals',
            'MySkill',
            'Apr 2025'
        ]
    ],

    11 => [
        'motto' => 'Backend yang baik membuat sistem dapat diandalkan',
        'tentang' => 'Mahasiswa D4 Teknik Informatika dengan fokus pada pengembangan backend dan perancangan API. Memiliki ketertarikan pada Node.js, Express, dan database PostgreSQL.',
        'keahlian' => ['Node.js','Express','PostgreSQL','REST API','JavaScript','Git','Docker'],
        'pengalaman' => [
            ['Backend Developer (Magang)','PT. Solusi Teknologi Bersama','Jun 2025 – Sep 2025',[
                'Mengembangkan REST API untuk aplikasi internal',
                'Mendesain tabel dan query database PostgreSQL'
            ]]
        ],
        'proyek' => [
            'TaskFlow API',
            'REST API untuk pengelolaan tugas dan kolaborasi tim.',
            ['Node.js','Express','PostgreSQL']
        ],
        'sertifikat' => [
            'Backend Development with Node.js',
            'Dicoding',
            'Jun 2025'
        ]
    ],

    12 => [
        'motto' => 'Data yang baik menghasilkan keputusan yang lebih baik',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang tertarik pada data science dan machine learning. Memiliki pengalaman mengolah data menggunakan Python dan berbagai library analisis data.',
        'keahlian' => ['Python','Pandas','NumPy','Scikit-learn','SQL','Machine Learning','Data Visualization'],
        'pengalaman' => [
            ['Data Science Intern','PT. Analitika Nusantara','Jul 2025 – Sep 2025',[
                'Melakukan preprocessing dan eksplorasi dataset',
                'Membangun model machine learning sederhana untuk prediksi'
            ]]
        ],
        'proyek' => [
            'CustomerInsight',
            'Dashboard analisis pelanggan untuk menemukan pola transaksi dan segmentasi pengguna.',
            ['Python','Pandas','Scikit-learn','Streamlit']
        ],
        'sertifikat' => [
            'Data Science Fundamentals',
            'Dicoding',
            'Mei 2025'
        ]
    ],

    13 => [
        'motto' => 'Otomasi membuat proses lebih efisien',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang memiliki minat pada DevOps, otomatisasi deployment, dan pengelolaan lingkungan server berbasis Linux.',
        'keahlian' => ['Docker','Linux','CI/CD','GitHub Actions','Bash','Nginx','Git'],
        'pengalaman' => [
            ['DevOps Intern','PT. Infrastruktur Digital','Jun 2025 – Sep 2025',[
                'Membangun workflow CI/CD untuk aplikasi web',
                'Membantu konfigurasi server Linux untuk deployment'
            ]]
        ],
        'proyek' => [
            'AutoDeploy',
            'Pipeline deployment otomatis untuk aplikasi web menggunakan container.',
            ['Docker','GitHub Actions','Linux']
        ],
        'sertifikat' => [
            'Introduction to DevOps',
            'Dicoding',
            'Apr 2025'
        ]
    ],

    14 => [
        'motto' => 'Teknologi mobile dekat dengan kehidupan sehari-hari',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang tertarik pada pengembangan aplikasi mobile menggunakan Flutter dan Firebase.',
        'keahlian' => ['Flutter','Dart','Firebase','Android','REST API','Git'],
        'pengalaman' => [
            ['Mobile Developer (Magang)','PT. Aplikasi Nusantara','Jun 2025 – Agu 2025',[
                'Mengembangkan antarmuka aplikasi mobile menggunakan Flutter',
                'Mengintegrasikan aplikasi dengan Firebase'
            ]]
        ],
        'proyek' => [
            'KampusMobile',
            'Aplikasi mobile untuk melihat jadwal kuliah dan informasi kegiatan kampus.',
            ['Flutter','Dart','Firebase']
        ],
        'sertifikat' => [
            'Flutter Development',
            'Dicoding',
            'Mar 2025'
        ]
    ],

    15 => [
        'motto' => 'Keamanan dimulai dari kesadaran',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang tertarik pada keamanan siber, keamanan jaringan, dan pengujian keamanan aplikasi.',
        'keahlian' => ['Cyber Security','Network Security','Linux','Python','Nmap','Wireshark','Penetration Testing'],
        'pengalaman' => [
            ['Cyber Security Intern','PT. Secure Teknologi','Jul 2025 – Sep 2025',[
                'Membantu melakukan pengujian keamanan jaringan',
                'Mendokumentasikan temuan keamanan dan rekomendasi perbaikan'
            ]]
        ],
        'proyek' => [
            'SecureScan',
            'Prototype alat pemindaian keamanan jaringan untuk lingkungan laboratorium.',
            ['Python','Nmap','Linux']
        ],
        'sertifikat' => [
            'Cyber Security Fundamentals',
            'Cisco',
            'Mei 2025'
        ]
    ],

    16 => [
        'motto' => 'Cloud membuat teknologi semakin fleksibel',
        'tentang' => 'Mahasiswa D4 Teknik Informatika dengan minat pada cloud computing dan container orchestration. Tertarik mempelajari bagaimana aplikasi dapat berjalan secara scalable.',
        'keahlian' => ['AWS','Docker','Kubernetes','Linux','Cloud Computing','Git','CI/CD'],
        'pengalaman' => [
            ['Cloud Intern','PT. Cloud Teknologi Indonesia','Jun 2025 – Sep 2025',[
                'Membantu melakukan deployment aplikasi pada cloud',
                'Membuat konfigurasi container untuk lingkungan pengembangan'
            ]]
        ],
        'proyek' => [
            'CloudCampus',
            'Prototype deployment aplikasi kampus menggunakan layanan cloud dan container.',
            ['AWS','Docker','Kubernetes']
        ],
        'sertifikat' => [
            'AWS Cloud Practitioner',
            'AWS',
            'Feb 2025'
        ]
    ],

    17 => [
        'motto' => 'Aplikasi mobile harus nyaman digunakan',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang berfokus pada pengembangan aplikasi Android menggunakan Kotlin dan integrasi Firebase.',
        'keahlian' => ['Kotlin','Android','Firebase','Java','REST API','Git'],
        'pengalaman' => [
            ['Android Developer (Magang)','PT. Mobile Kreatif Indonesia','Jun 2025 – Agu 2025',[
                'Mengembangkan fitur aplikasi Android menggunakan Kotlin',
                'Mengintegrasikan autentikasi dan database Firebase'
            ]]
        ],
        'proyek' => [
            'StudyMate',
            'Aplikasi Android untuk membantu mahasiswa mengatur jadwal dan tugas kuliah.',
            ['Kotlin','Android','Firebase']
        ],
        'sertifikat' => [
            'Android Development with Kotlin',
            'Dicoding',
            'Apr 2025'
        ]
    ],

    18 => [
        'motto' => 'AI membantu manusia mengambil keputusan lebih baik',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang tertarik pada artificial intelligence dan computer vision. Senang bereksperimen dengan model machine learning untuk menyelesaikan masalah praktis.',
        'keahlian' => ['Python','TensorFlow','Computer Vision','Machine Learning','OpenCV','SQL'],
        'pengalaman' => [
            ['AI Intern','PT. Inovasi Cerdas Indonesia','Jun 2025 – Sep 2025',[
                'Membantu menyiapkan dataset untuk model computer vision',
                'Melakukan eksperimen dan evaluasi model klasifikasi gambar'
            ]]
        ],
        'proyek' => [
            'ObjectDetect',
            'Prototype sistem deteksi objek sederhana menggunakan computer vision.',
            ['Python','OpenCV','TensorFlow']
        ],
        'sertifikat' => [
            'Machine Learning Fundamentals',
            'Dicoding',
            'Jun 2025'
        ]
    ],

    19 => [
        'motto' => 'Kualitas adalah bagian dari proses',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang tertarik pada software testing dan quality assurance. Memahami pentingnya pengujian untuk menjaga kualitas aplikasi.',
        'keahlian' => ['Software Testing','Quality Assurance','API Testing','Postman','Test Case','Bug Tracking'],
        'pengalaman' => [
            ['QA Intern','PT. Software Nusantara','Jul 2025 – Sep 2025',[
                'Menyusun test case untuk fitur aplikasi web',
                'Melakukan pengujian API menggunakan Postman'
            ]]
        ],
        'proyek' => [
            'TestFlow',
            'Dokumentasi dan prototype workflow pengujian aplikasi web secara terstruktur.',
            ['Postman','JavaScript','API Testing']
        ],
        'sertifikat' => [
            'Software Testing Fundamentals',
            'Dicoding',
            'Mei 2025'
        ]
    ],

    20 => [
        'motto' => 'Bangun pengalaman web yang cepat dan intuitif',
        'tentang' => 'Mahasiswa D4 Teknik Informatika yang fokus pada pengembangan frontend modern menggunakan React, Next.js, dan TypeScript.',
        'keahlian' => ['React','Next.js','TypeScript','JavaScript','Tailwind CSS','Git'],
        'pengalaman' => [
            ['Frontend Developer (Magang)','PT. Web Kreasi Indonesia','Jun 2025 – Sep 2025',[
                'Membangun komponen antarmuka menggunakan React',
                'Mengembangkan halaman web menggunakan Next.js dan TypeScript'
            ]]
        ],
        'proyek' => [
            'EventSpace',
            'Platform informasi dan pendaftaran acara kampus berbasis web.',
            ['Next.js','React','TypeScript']
        ],
        'sertifikat' => [
            'Frontend Web Development',
            'Dicoding',
            'Apr 2025'
        ]
    ],


    // =========================================================
    // D4 SISTEM INFORMASI BISNIS
    // =========================================================

    21 => [
        'motto' => 'Data membantu bisnis bergerak lebih terarah',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada analisis bisnis, pengolahan data, dan dokumentasi kebutuhan sistem.',
        'keahlian' => ['Business Analysis','Microsoft Excel','SQL','Documentation','Process Modeling','PowerPoint'],
        'pengalaman' => [
            ['Business Analyst Intern','PT. Solusi Bisnis Indonesia','Jun 2025 – Agu 2025',[
                'Membantu menganalisis kebutuhan pengguna',
                'Menyusun dokumentasi proses bisnis dan kebutuhan sistem'
            ]]
        ],
        'proyek' => [
            'BusinessFlow',
            'Pemetaan proses bisnis sederhana untuk membantu identifikasi kebutuhan sistem.',
            ['Microsoft Excel','Draw.io','SQL']
        ],
        'sertifikat' => [
            'Business Analysis Fundamentals',
            'MySkill',
            'Mei 2025'
        ]
    ],

    22 => [
        'motto' => 'Produk yang baik dimulai dari pengguna',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada product design, UI/UX, dan user research untuk menghasilkan solusi digital yang relevan.',
        'keahlian' => ['UI/UX','Figma','Product Design','User Research','Wireframing','Prototyping'],
        'pengalaman' => [
            ['Product Design Intern','PT. Produk Digital Nusantara','Jun 2025 – Agu 2025',[
                'Membuat wireframe dan prototype produk digital',
                'Membantu melakukan riset kebutuhan pengguna'
            ]]
        ],
        'proyek' => [
            'KampusConnect',
            'Prototype aplikasi yang membantu mahasiswa menemukan layanan dan informasi kampus.',
            ['Figma','User Research','Prototyping']
        ],
        'sertifikat' => [
            'UI/UX Design Fundamentals',
            'MySkill',
            'Apr 2025'
        ]
    ],

    23 => [
        'motto' => 'Ubah data menjadi keputusan',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada business intelligence dan visualisasi data untuk membantu pengambilan keputusan.',
        'keahlian' => ['Power BI','SQL','Data Analysis','Business Intelligence','Excel','Data Visualization'],
        'pengalaman' => [
            ['Business Intelligence Intern','PT. Data Bisnis Indonesia','Jun 2025 – Sep 2025',[
                'Membantu menyiapkan data untuk dashboard bisnis',
                'Membuat visualisasi dan ringkasan data menggunakan Power BI'
            ]]
        ],
        'proyek' => [
            'SalesDashboard',
            'Dashboard penjualan untuk memantau performa produk dan tren transaksi.',
            ['Power BI','SQL','Excel']
        ],
        'sertifikat' => [
            'Power BI Data Analyst',
            'Microsoft',
            'Jun 2025'
        ]
    ],

    24 => [
        'motto' => 'Proyek yang baik dimulai dari perencanaan',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang memiliki minat pada project management, business analysis, dan metode pengembangan Agile.',
        'keahlian' => ['Project Management','Scrum','Jira','Business Analysis','Agile','Documentation'],
        'pengalaman' => [
            ['Project Management Intern','PT. Manajemen Digital','Jun 2025 – Sep 2025',[
                'Membantu memantau progres pekerjaan tim',
                'Menyusun dokumentasi dan backlog proyek menggunakan Jira'
            ]]
        ],
        'proyek' => [
            'ProjectTrack',
            'Prototype dashboard untuk memantau tugas, progres, dan timeline proyek.',
            ['Jira','Scrum','Figma']
        ],
        'sertifikat' => [
            'Scrum Fundamentals Certified',
            'Scrum Study',
            'Mei 2025'
        ]
    ],

    25 => [
        'motto' => 'Proses bisnis yang baik membuat organisasi lebih efisien',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada ERP, SAP, database, dan analisis proses bisnis perusahaan.',
        'keahlian' => ['ERP','SAP','SQL','Business Process','Database','Excel'],
        'pengalaman' => [
            ['ERP Intern','PT. Integrasi Bisnis Nusantara','Jun 2025 – Sep 2025',[
                'Membantu mendokumentasikan proses bisnis perusahaan',
                'Mempelajari alur data dan proses pada sistem ERP'
            ]]
        ],
        'proyek' => [
            'ERP Campus',
            'Prototype sistem pengelolaan proses administrasi kampus berbasis konsep ERP.',
            ['SAP','SQL','Business Process']
        ],
        'sertifikat' => [
            'SAP Fundamentals',
            'OpenSAP',
            'Apr 2025'
        ]
    ],

    26 => [
        'motto' => 'Strategi digital dimulai dari memahami audiens',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada digital marketing, SEO, analytics, dan strategi konten digital.',
        'keahlian' => ['Digital Marketing','SEO','Google Analytics','Content Strategy','Social Media','Copywriting'],
        'pengalaman' => [
            ['Digital Marketing Intern','PT. Media Digital Malang','Jun 2025 – Agu 2025',[
                'Membantu menyusun konten untuk kanal digital',
                'Menganalisis performa konten menggunakan analytics'
            ]]
        ],
        'proyek' => [
            'CampusMarket',
            'Strategi pemasaran digital untuk mempromosikan produk UMKM mahasiswa.',
            ['SEO','Analytics','Content Strategy']
        ],
        'sertifikat' => [
            'Digital Marketing Fundamentals',
            'Google',
            'Mar 2025'
        ]
    ],

    27 => [
        'motto' => 'Visualisasi membantu melihat cerita di balik data',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang fokus pada business intelligence, data visualization, dan analisis data untuk mendukung keputusan bisnis.',
        'keahlian' => ['Business Intelligence','Tableau','Python','Data Visualization','SQL','Excel'],
        'pengalaman' => [
            ['BI Analyst Intern','PT. Insight Data Indonesia','Jun 2025 – Sep 2025',[
                'Membantu membuat dashboard visualisasi data',
                'Melakukan analisis tren menggunakan dataset bisnis'
            ]]
        ],
        'proyek' => [
            'MarketInsight',
            'Dashboard analisis pasar menggunakan visualisasi interaktif.',
            ['Tableau','Python','SQL']
        ],
        'sertifikat' => [
            'Tableau Fundamentals',
            'Tableau',
            'Feb 2025'
        ]
    ],

    28 => [
        'motto' => 'Produk yang relevan lahir dari riset yang baik',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada product management, Agile, Figma, dan riset pasar.',
        'keahlian' => ['Product Management','Figma','Agile','Market Research','Product Strategy','User Research'],
        'pengalaman' => [
            ['Product Management Intern','PT. Produk Nusantara','Jun 2025 – Sep 2025',[
                'Membantu menyusun prioritas fitur produk',
                'Melakukan riset sederhana terhadap kebutuhan pengguna'
            ]]
        ],
        'proyek' => [
            'MarketMate',
            'Konsep aplikasi rekomendasi produk berdasarkan kebutuhan pengguna.',
            ['Figma','Market Research','Agile']
        ],
        'sertifikat' => [
            'Product Management Fundamentals',
            'Product School',
            'Apr 2025'
        ]
    ],

    29 => [
        'motto' => 'Data yang terstruktur membantu bisnis bekerja lebih cepat',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang memiliki minat pada database, SQL, business analysis, dan business intelligence.',
        'keahlian' => ['Database','SQL','Business Analysis','Power BI','Excel','Data Modeling'],
        'pengalaman' => [
            ['Business Data Intern','PT. Data Solusi Indonesia','Jun 2025 – Agu 2025',[
                'Membantu membersihkan dan menyusun data bisnis',
                'Membuat laporan analisis sederhana menggunakan Power BI'
            ]]
        ],
        'proyek' => [
            'BusinessReport',
            'Dashboard laporan bisnis untuk membantu memantau indikator operasional.',
            ['SQL','Power BI','Excel']
        ],
        'sertifikat' => [
            'SQL for Data Analysis',
            'Dicoding',
            'Mei 2025'
        ]
    ],

    30 => [
        'motto' => 'Sederhana, jelas, dan mudah digunakan',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada UI/UX, business process, dan prototyping. Senang menerjemahkan kebutuhan bisnis menjadi rancangan produk digital.',
        'keahlian' => ['UI/UX','Figma','Business Process','Prototyping','User Flow','Wireframing'],
        'pengalaman' => [
            ['UI/UX Intern','PT. Kreasi Produk Digital','Jun 2025 – Agu 2025',[
                'Membuat user flow dan prototype aplikasi',
                'Menerjemahkan kebutuhan bisnis ke dalam rancangan antarmuka'
            ]]
        ],
        'proyek' => [
            'BizOrder',
            'Prototype sistem pemesanan internal untuk kebutuhan operasional bisnis.',
            ['Figma','Prototyping','Business Process']
        ],
        'sertifikat' => [
            'Google UX Design Certificate',
            'Coursera',
            'Jan 2025'
        ]
    ],

    31 => [
        'motto' => 'Sistem terintegrasi membuat proses bisnis lebih efisien',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang tertarik pada ERP, Oracle, SQL, dan pemodelan proses bisnis.',
        'keahlian' => ['ERP','Oracle','SQL','Business Process','Database','System Analysis'],
        'pengalaman' => [
            ['ERP Analyst Intern','PT. Sistem Bisnis Indonesia','Jun 2025 – Sep 2025',[
                'Membantu menganalisis proses bisnis pada sistem ERP',
                'Menyusun dokumentasi kebutuhan dan alur data'
            ]]
        ],
        'proyek' => [
            'ERPFlow',
            'Prototype sistem integrasi data untuk mendukung proses administrasi bisnis.',
            ['Oracle','SQL','Business Process']
        ],
        'sertifikat' => [
            'Oracle Database Foundations',
            'Oracle',
            'Mar 2025'
        ]
    ],

    32 => [
        'motto' => 'Analisis yang baik menghasilkan keputusan yang tepat',
        'tentang' => 'Mahasiswa D4 Sistem Informasi Bisnis yang memiliki minat pada data analysis, business intelligence, dan pengolahan data menggunakan Python dan Power BI.',
        'keahlian' => ['Data Analysis','Python','Excel','Power BI','SQL','Data Visualization'],
        'pengalaman' => [
            ['Data Analyst Intern','PT. Analisis Bisnis Nusantara','Jun 2025 – Sep 2025',[
                'Mengolah dataset bisnis untuk kebutuhan analisis',
                'Membuat dashboard dan visualisasi menggunakan Power BI'
            ]]
        ],
        'proyek' => [
            'CampusAnalytics',
            'Dashboard analitik untuk melihat pola dan performa aktivitas mahasiswa.',
            ['Python','Power BI','Excel']
        ],
        'sertifikat' => [
            'Data Analysis Fundamentals',
            'Dicoding',
            'Apr 2025'
        ]
    ],
];

foreach ($mahasiswa as &$m) {
    $d = $extra[$m['id']] ?? null;
    if (!$d) continue;
    $slug = strtolower(str_replace(' ', '', $m['nama']));
    $m += [
        'bio' => $d['tentang'], 'tentang' => $d['tentang'], 'motto' => $d['motto'],
        'domisili' => 'Malang, Jawa Timur', 'keahlian' => $d['keahlian'],
        'kontak' => ['github'=>"github.com/$slug", 'linkedin'=>"linkedin.com/in/$slug", 'website'=>"$slug.dev", 'email'=>"$slug@email.com"],
        'pengalaman' => array_map(fn($p) => ['posisi'=>$p[0],'instansi'=>$p[1],'periode'=>$p[2],'tugas'=>$p[3]], $d['pengalaman']),
        'daftar_proyek' => [['nama'=>$d['proyek'][0],'deskripsi'=>$d['proyek'][1],'tech'=>$d['proyek'][2],'url'=>'#','gambar'=>'']],
        'daftar_sertifikat' => [['nama'=>$d['sertifikat'][0],'penerbit'=>$d['sertifikat'][1],'tanggal'=>$d['sertifikat'][2],'verified'=>true]],
    ];
}

foreach ($mahasiswa as &$m) {
    $m['slug'] = slugify($m['nama']);
}

unset($m);