<?php

require_once __DIR__ . '/../../function/Helpers.php';

// Data dummy mitra industri (belum ada database).
// 'logo' = nama file di assets/images/mitra/ (kosong = tampil inisial).
$mitra = [
    ['id'=>1,'nama'=>'PT. Semarsoft Technology Indonesia','status'=>'Mitra Utama','bidang'=>['Software Development','UI/UX Design'],'lokasi'=>'Malang','skema'=>['Magang MBKM','Magang Mandiri'],'logo'=>'','posisi'=>6,'magang'=>12,
     'deskripsi'=>'Perusahaan pengembang perangkat lunak yang membangun sistem internal dan aplikasi web untuk berbagai instansi.'],
    ['id'=>2,'nama'=>'PT. Cloud Kreasi','status'=>'Mitra Utama','bidang'=>['Cloud & DevOps'],'lokasi'=>'Surabaya','skema'=>['Magang MBKM'],'logo'=>'','posisi'=>4,'magang'=>8,
     'deskripsi'=>'Penyedia layanan infrastruktur cloud dan otomatisasi deployment untuk startup dan perusahaan menengah.'],
    ['id'=>3,'nama'=>'PT. Data Nusantara','status'=>'Mitra Terverifikasi','bidang'=>['Data & AI'],'lokasi'=>'Jakarta','skema'=>['Magang MBKM','Kerja Praktik'],'logo'=>'','posisi'=>3,'magang'=>5,
     'deskripsi'=>'Konsultan analitik data yang membantu bisnis mengambil keputusan lewat dashboard dan model prediksi.'],
    ['id'=>4,'nama'=>'PT. Solusi Digital Malang','status'=>'Mitra Terverifikasi','bidang'=>['Bisnis Digital','Software Development'],'lokasi'=>'Malang','skema'=>['Magang Mandiri','Kerja Praktik'],'logo'=>'','posisi'=>2,'magang'=>6,
     'deskripsi'=>'Mitra transformasi digital untuk UMKM, mulai dari analisis proses bisnis hingga implementasi sistem informasi.'],
    ['id'=>5,'nama'=>'PT. Amankan Data','status'=>'Mitra Terverifikasi','bidang'=>['Cyber Security'],'lokasi'=>'Bandung','skema'=>['Magang MBKM'],'logo'=>'','posisi'=>2,'magang'=>3,
     'deskripsi'=>'Layanan audit keamanan aplikasi dan jaringan serta pelatihan kesadaran keamanan siber.'],
    ['id'=>6,'nama'=>'Studio Rupa Digital','status'=>'Mitra Terverifikasi','bidang'=>['UI/UX Design'],'lokasi'=>'Remote','skema'=>['Magang Mandiri'],'logo'=>'','posisi'=>3,'magang'=>4,
     'deskripsi'=>'Studio desain produk digital yang merancang antarmuka aplikasi mobile dan web dengan riset pengguna.'],
    ['id'=>7,'nama'=>'PT. Cerdas Nusantara','status'=>'Mitra Utama','bidang'=>['Data & AI','Software Development'],'lokasi'=>'Jakarta','skema'=>['Magang MBKM','Magang Mandiri'],'logo'=>'','posisi'=>5,'magang'=>9,
     'deskripsi'=>'Pengembang solusi kecerdasan buatan untuk industri manufaktur dan ritel.'],
    ['id'=>8,'nama'=>'CV. Lintas Aplikasi','status'=>'Mitra Terverifikasi','bidang'=>['Software Development'],'lokasi'=>'Malang','skema'=>['Kerja Praktik'],'logo'=>'','posisi'=>1,'magang'=>2,
     'deskripsi'=>'Pembuat aplikasi mobile lintas platform untuk klien lokal dan regional.'],
];

/*
|--------------------------------------------------------------------------
| Detail mitra (dummy) - mengikuti proposal:
| kategori, skala, wilayah, alamat, kontak/PIC, formasi magang (prodi, kuota,
| periode, batas daftar, deskripsi pekerjaan, persyaratan, tahapan seleksi).
|--------------------------------------------------------------------------
*/
$TI  = 'D4 Teknik Informatika';
$SIB = 'D4 Sistem Informasi Bisnis';

$f = fn($posisi, $prodi, $kuota, $jobdesc, $syarat, $seleksi = 'Seleksi berkas dan wawancara') => [
    'posisi' => $posisi, 'prodi' => $prodi, 'kuota' => $kuota,
    'periode' => 'Feb – Jun 2027', 'batas' => '15 Des 2026',
    'jobdesc' => $jobdesc, 'syarat' => $syarat, 'seleksi' => $seleksi,
];

$extra = [
 1 => ['kategori'=>'Swasta','skala'=>'Menengah','alamat'=>'Jl. Ijen Nirwana Blok B-12, Klojen, Malang, Jawa Timur',
   'email'=>'hrd@semarsoft.example','website'=>'semarsoft.example','pic'=>['Rina Wulandari','HRD Manager'],
   'lanjut'=>'Setiap peserta magang didampingi seorang pembimbing lapangan dan terlibat langsung dalam proyek klien.',
   'formasi'=>[
     $f('Frontend Developer', [$TI], 3, 'Mengembangkan antarmuka web dengan React.js dan berkolaborasi dengan tim backend dalam sistem internal.', ['Menguasai HTML, CSS, dan JavaScript','Memahami Git dasar','Memiliki portofolio proyek web']),
     $f('UI/UX Designer', [$SIB, $TI], 3, 'Merancang alur dan prototipe antarmuka aplikasi, serta melakukan uji kegunaan sederhana.', ['Dapat menggunakan Figma','Memahami dasar riset pengguna'], 'Seleksi portofolio dan wawancara'),
   ]],
 2 => ['kategori'=>'Swasta','skala'=>'Besar','alamat'=>'Jl. Raya Darmo No. 45, Wonokromo, Surabaya, Jawa Timur',
   'email'=>'magang@cloudkreasi.example','website'=>'cloudkreasi.example','pic'=>['Bagus Prasetyo','Engineering Manager'],
   'lanjut'=>'Peserta magang bekerja di lingkungan staging yang sama dengan tim engineer.',
   'formasi'=>[
     $f('DevOps Engineer Intern', [$TI], 2, 'Membangun pipeline CI/CD dan mengelola container aplikasi di lingkungan staging.', ['Memahami Linux dan Git','Mengenal Docker lebih disukai']),
     $f('Cloud Support Intern', [$TI, $SIB], 2, 'Memantau layanan cloud klien dan menyusun dokumentasi penanganan insiden.', ['Memahami jaringan dasar','Mampu menulis dokumentasi teknis']),
   ]],
 3 => ['kategori'=>'Swasta','skala'=>'Menengah','alamat'=>'Jl. Jenderal Sudirman Kav. 21, Setiabudi, Jakarta Selatan',
   'email'=>'talenta@datanusantara.example','website'=>'datanusantara.example','pic'=>['Maya Anggraini','Head of Analytics'],
   'lanjut'=>'Peserta magang menangani data nyata yang telah dianonimkan.',
   'formasi'=>[ $f('Data Analyst Intern', [$TI, $SIB], 3, 'Membersihkan data, membuat dashboard Power BI, dan menyusun ringkasan temuan untuk klien.', ['Mengenal SQL dan Python atau Excel lanjutan','Memahami statistik dasar'], 'Tes studi kasus data dan wawancara') ]],
 4 => ['kategori'=>'UMKM','skala'=>'Kecil','alamat'=>'Jl. Soekarno Hatta No. 17, Lowokwaru, Malang, Jawa Timur',
   'email'=>'kerjasama@solusidigital.example','website'=>'solusidigital.example','pic'=>['Dewi Lestari','Direktur Operasional'],
   'lanjut'=>'Tim kecil membuat peserta magang terlibat langsung di seluruh tahap proyek.',
   'formasi'=>[ $f('Business Analyst Intern', [$SIB], 2, 'Memetakan proses bisnis klien UMKM dan menyusun dokumen kebutuhan sistem bersama developer.', ['Memahami UML atau BPMN dasar','Komunikasi yang baik']) ]],
 5 => ['kategori'=>'Swasta','skala'=>'Menengah','alamat'=>'Jl. Dago Asri No. 8, Coblong, Bandung, Jawa Barat',
   'email'=>'recruitment@amankandata.example','website'=>'amankandata.example','pic'=>['Hendra Kusuma','Security Lead'],
   'lanjut'=>'Kegiatan magang dilakukan di bawah pengawasan ketat dan perjanjian kerahasiaan.',
   'formasi'=>[ $f('Security Analyst Intern', [$TI], 2, 'Membantu audit keamanan aplikasi web dan menyusun laporan temuan kerentanan.', ['Memahami konsep OWASP Top 10','Menandatangani perjanjian kerahasiaan'], 'Tes teknis dan wawancara') ]],
 6 => ['kategori'=>'Swasta','skala'=>'Kecil','alamat'=>'Dikerjakan secara jarak jauh (kantor pusat di Yogyakarta)',
   'email'=>'hello@rupadigital.example','website'=>'rupadigital.example','pic'=>['Aditya Nugroho','Creative Director'],
   'lanjut'=>'Bekerja jarak jauh dengan sesi tinjau desain mingguan.',
   'formasi'=>[ $f('UI/UX Designer Intern', [$SIB, $TI], 3, 'Merancang antarmuka aplikasi mobile dan web, dari wireframe sampai prototipe interaktif.', ['Mahir Figma','Menyertakan portofolio desain'], 'Review portofolio dan wawancara') ]],
 7 => ['kategori'=>'Swasta','skala'=>'Besar','alamat'=>'Jl. HR Rasuna Said Kav. 5, Kuningan, Jakarta Selatan',
   'email'=>'internship@cerdasnusantara.example','website'=>'cerdasnusantara.example','pic'=>['Sinta Maharani','People & Culture'],
   'lanjut'=>'Peserta magang dibimbing engineer senior dan mempresentasikan hasil di akhir periode.',
   'formasi'=>[
     $f('AI Engineer Intern', [$TI], 3, 'Melatih dan mengevaluasi model klasifikasi gambar untuk kontrol kualitas manufaktur.', ['Menguasai Python','Mengenal TensorFlow atau PyTorch'], 'Tes teknis dan wawancara'),
     $f('Backend Developer Intern', [$TI], 2, 'Membangun API untuk layanan model dan mengelola basis data hasil prediksi.', ['Memahami REST API dan SQL','Mengenal Git']),
   ]],
 8 => ['kategori'=>'UMKM','skala'=>'Kecil','alamat'=>'Jl. Veteran No. 33, Lowokwaru, Malang, Jawa Timur',
   'email'=>'info@lintasaplikasi.example','website'=>'lintasaplikasi.example','pic'=>['Fajar Hidayat','Pemilik / Lead Developer'],
   'lanjut'=>'Cocok untuk kerja praktik dengan jadwal yang fleksibel.',
   'formasi'=>[ $f('Mobile Developer', [$TI], 1, 'Mengembangkan fitur aplikasi Flutter dan menghubungkannya dengan REST API.', ['Mengenal Dart atau Flutter','Memiliki satu aplikasi contoh']) ]],
];

foreach ($mitra as &$m) {
    $d = $extra[$m['id']] ?? [];
    $m = $d + $m;

    $m['formasi']   = $m['formasi'] ?? [];
    $m['posisi']    = array_sum(array_column($m['formasi'], 'kuota')) ?: $m['posisi'];
    $m['tentang']   = $m['deskripsi'] . ' ' . ($m['lanjut'] ?? '');
    $m['wilayah']   = $m['lokasi'];
    $m['tahun_akademik'] = '2026/2027';
    $m['verifikasi'] = 'Mei 2026';
    $m['slug']      = slugify($m['nama']);
}
unset($m);