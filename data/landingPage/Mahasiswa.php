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
 2 => ['motto'=>'Data bercerita, kita yang menyimak',
   'tentang'=>'Mahasiswa D4 Teknik Informatika yang fokus pada analisis data dan machine learning. Terbiasa mengolah data mentah menjadi wawasan yang mudah dipahami tim.',
   'keahlian'=>['Python','Pandas','SQL','Scikit-learn','Power BI','Jupyter'],
   'pengalaman'=>[['Data Analyst (Magang)','PT. Data Nusantara','Jul 2025 – Sep 2025',['Membersihkan dan menganalisis data penjualan bulanan','Menyusun dashboard ringkasan untuk tim manajemen']]],
   'proyek'=>['PrediksiHarga','Model prediksi harga rumah berbasis regresi dengan antarmuka web sederhana.',['Python','Flask','Scikit-learn']],
   'sertifikat'=>['Data Analysis with Python','Dicoding','Apr 2025']],
 3 => ['motto'=>'Satu aplikasi, seribu kemungkinan',
   'tentang'=>'Mahasiswa yang menyukai pengembangan aplikasi mobile lintas platform dan perancangan database yang rapi.',
   'keahlian'=>['Flutter','Dart','Firebase','MySQL','REST API','Git'],
   'pengalaman'=>[['Mobile Developer (Magang)','CV. Lintas Aplikasi','Jun 2025 – Agu 2025',['Membangun fitur katalog produk pada aplikasi Flutter','Menghubungkan aplikasi dengan REST API internal']]],
   'proyek'=>['JadwalKu','Aplikasi pengingat jadwal kuliah dengan notifikasi harian.',['Flutter','Firebase']],
   'sertifikat'=>['Flutter Developer Fundamentals','Dicoding','Mar 2025']],
 4 => ['motto'=>'Bisnis dan teknologi berjalan berdampingan',
   'tentang'=>'Mahasiswa Sistem Informasi Bisnis yang tertarik menjembatani kebutuhan bisnis dengan solusi teknologi, dari analisis proses hingga manajemen proyek.',
   'keahlian'=>['Business Analysis','Scrum','Jira','Figma','Excel','Dokumentasi'],
   'pengalaman'=>[['Business Analyst (Magang)','PT. Solusi Digital Malang','Jun 2025 – Agu 2025',['Memetakan alur proses bisnis klien dalam bentuk diagram','Menyusun dokumen kebutuhan sistem bersama tim developer']]],
   'proyek'=>['SIMKoperasi','Sistem informasi simpan pinjam untuk koperasi kampus.',['Laravel','MySQL','Bootstrap']],
   'sertifikat'=>['Scrum Fundamentals','Scrum Study','Mei 2025']],
 5 => ['motto'=>'Otomatiskan yang bisa diotomatiskan',
   'tentang'=>'Mahasiswa dengan minat pada DevOps dan infrastruktur cloud, terbiasa membangun pipeline CI/CD dan mengelola container.',
   'keahlian'=>['Docker','Linux','GitHub Actions','AWS','Nginx','Bash'],
   'pengalaman'=>[['DevOps Intern','PT. Cloud Kreasi','Jun 2025 – Sep 2025',['Membuat pipeline CI/CD untuk deployment otomatis','Mengelola container aplikasi di lingkungan staging']]],
   'proyek'=>['DeployKit','Template pipeline CI/CD untuk aplikasi web berbasis Docker.',['Docker','GitHub Actions','AWS']],
   'sertifikat'=>['AWS Cloud Practitioner','AWS','Feb 2025']],
 6 => ['motto'=>'Aman itu kebiasaan',
   'tentang'=>'Mahasiswa yang tertarik pada keamanan jaringan dan pengujian penetrasi, aktif mengikuti kompetisi CTF tingkat kampus.',
   'keahlian'=>['Network Security','Wireshark','Kali Linux','Nmap','Linux','Python'],
   'pengalaman'=>[['Security Intern','PT. Amankan Data','Jul 2025 – Sep 2025',['Membantu audit keamanan aplikasi web internal','Menyusun laporan temuan kerentanan beserta rekomendasinya']]],
   'proyek'=>['NetGuard','Alat pemantau lalu lintas jaringan sederhana untuk laboratorium kampus.',['Python','Wireshark']],
   'sertifikat'=>['Network Security Essentials','Cisco','Apr 2025']],
 7 => ['motto'=>'Desain yang baik terasa mudah',
   'tentang'=>'Mahasiswa yang menyukai riset pengguna dan perancangan produk digital, dari wireframe sampai prototipe interaktif.',
   'keahlian'=>['Figma','User Research','Wireframing','Prototyping','Design System','HTML/CSS'],
   'pengalaman'=>[['UI/UX Designer (Magang)','Studio Rupa Digital','Jun 2025 – Agu 2025',['Merancang alur dan antarmuka aplikasi pemesanan','Melakukan uji kegunaan dengan 10 pengguna']]],
   'proyek'=>['Pesanin','Rancangan aplikasi pemesanan makanan kampus dengan prototipe Figma.',['Figma','Maze']],
   'sertifikat'=>['Google UX Design Certificate','Coursera','Jan 2025']],
 8 => ['motto'=>'Kecerdasan buatan untuk kebaikan',
   'tentang'=>'Mahasiswa dengan minat pada kecerdasan buatan dan data science, aktif membangun model untuk masalah nyata di sekitar kampus.',
   'keahlian'=>['Python','TensorFlow','SQL','Computer Vision','NLP','Streamlit'],
   'pengalaman'=>[['AI Engineer (Magang)','PT. Cerdas Nusantara','Jun 2025 – Sep 2025',['Melatih model klasifikasi gambar untuk kontrol kualitas','Menyajikan hasil model lewat aplikasi Streamlit']]],
   'proyek'=>['SampahLens','Aplikasi pengenal jenis sampah dari foto menggunakan model klasifikasi gambar.',['Python','TensorFlow','Streamlit']],
   'sertifikat'=>['TensorFlow Developer Certificate','Google','Mar 2025']],
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