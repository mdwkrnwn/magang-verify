<?php

$active = "";

$toc = [
    ['01', 'Ruang lingkup & pengguna', 'ruang-lingkup'],
    ['02', 'Jenis data & tujuan', 'jenis-data'],
    ['03', 'Akses sesuai peran', 'akses-peran'],
    ['04', 'Informasi publik & pilihan', 'informasi-publik'],
    ['05', 'Pembagian akses magang', 'pembagian-akses'],
    ['06', 'Penyimpanan & penghapusan', 'penyimpanan'],
    ['07', 'Permohonan terkait data', 'permohonan-data'],
    ['08', 'Keamanan akun & token', 'keamanan-akun'],
    ['09', 'Cookies & layanan eksternal', 'cookies'],
    ['10', 'Perubahan kebijakan', 'perubahan-kebijakan'],
    ['11', 'Kontak pengelola', 'kontak-pengelola'],
];

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../components/landingPage/Head.php"; ?>

<link rel="stylesheet" href="<?= url('/assets/css/legal.css') ?>">

<body class="bg-white font-[Poppins] text-gray-900">

<?php include __DIR__ . "/../../../components/landingPage/Navbar.php"; ?>

<!-- ============================= -->
<!-- Hero                          -->
<!-- ============================= -->
<section class="legal legal-hero pt-32 pb-12">
    <div class="legal-shell">

        <nav class="legal-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= url('/') ?>">Beranda</a>
            <span aria-hidden="true">/</span>
            <span class="font-medium text-[#3f5b7c]">Privasi</span>
        </nav>

        <p class="legal-eyebrow mt-5">
            <span><?= icon('shield', 'w-4 h-4') ?></span>
            Informasi Pengguna
        </p>

        <h1 class="legal-title">Kebijakan Privasi</h1>

        <p class="legal-lead">
            Pahami data yang digunakan dalam proses magang, batas akses setiap peran,
            serta cara menyampaikan pertanyaan dan permohonan terkait data Anda.
        </p>

        <p class="legal-draft">
            Draf untuk ditinjau pengelola — belum kebijakan final
        </p>

    </div>
</section>


<!-- ============================= -->
<!-- Intinya                       -->
<!-- ============================= -->
<section class="legal legal-summary py-8">
    <div class="legal-shell">

        <h2 class="legal-summary-title">
            <?= icon('bolt', 'w-4 h-4') ?>
            Intinya, sebelum Anda membaca lebih jauh
        </h2>

        <div class="legal-summary-grid">

            <div class="legal-summary-item">
                <h3>Data punya tujuan</h3>
                <p>
                    Profil, lamaran, dan dokumen mendukung proses magang;
                    bukan seluruhnya informasi publik.
                </p>
            </div>

            <div class="legal-summary-item">
                <h3>Akses punya batas</h3>
                <p>
                    Kebutuhan mahasiswa, mitra, dosen, koordinator, dan tendik berbeda.
                </p>
            </div>

            <div class="legal-summary-item">
                <h3>Ada jalur permohonan</h3>
                <p>
                    Pertanyaan, akses, koreksi, atau keberatan disampaikan
                    kepada pengelola melalui email.
                </p>
            </div>

        </div>

    </div>
</section>


<!-- ============================= -->
<!-- Body                          -->
<!-- ============================= -->
<section class="legal py-12">
    <div class="legal-shell">
        <div class="legal-body">

            <!-- TOC -->
            <aside class="legal-toc">
                <p class="legal-toc-label">Dalam halaman ini</p>

                <nav class="legal-toc-list" id="legal-toc">
                    <?php foreach ($toc as [$num, $label, $anchor]): ?>
                        <a href="#<?= $anchor ?>" data-toc="<?= $anchor ?>">
                            <span class="num"><?= $num ?></span>
                            <span><?= $label ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <div class="legal-toc-aside">
                    <h3>Perlu penjelasan?</h3>

                    <p>
                        Sampaikan pertanyaan atau permohonan Anda melalui kontak
                        pengelola di bagian akhir halaman.
                    </p>

                    <a class="legal-toc-more" href="#kontak-pengelola">
                        Pelajari Sistem
                        <?= icon('right', 'w-3.5 h-3.5') ?>
                    </a>

                    <p class="legal-toc-more-label">Baca juga</p>

                    <ul>
                        <li>
                            <a href="<?= url('/syarat-ketentuan') ?>">
                                Syarat dan Ketentuan
                                <?= icon('right', 'w-3.5 h-3.5') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('/login') ?>">
                                Masuk
                                <?= icon('right', 'w-3.5 h-3.5') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>


            <!-- Content -->
            <div class="legal-content" id="legal-content">

                <!-- 01 -->
                <article class="legal-section" id="ruang-lingkup">
                    <h2><span class="num">01</span> Ruang lingkup dan pihak pengguna</h2>

                    <p>
                        Naskah ini mengusulkan cara VerifyMagang menjelaskan pengelolaan data dalam
                        layanan profil, portofolio, lowongan, pengajuan, penempatan, logbook, laporan,
                        dan penilaian magang. Naskah ditujukan bagi mahasiswa, koordinator, dosen,
                        tenaga kependidikan (tendik), mitra industri, dan pengunjung halaman publik.
                    </p>

                    <p>
                        Pengelola perlu meninjau dasar penggunaan data, kewenangan akses, dan praktik
                        operasional sebelum menetapkan kebijakan resmi institusi. Penjelasan di halaman
                        ini tidak menyatakan bahwa seluruh kontrol teknis atau pilihan pengguna sudah
                        tersedia.
                    </p>
                </article>


                <!-- 02 -->
                <article class="legal-section" id="jenis-data">
                    <h2><span class="num">02</span> Jenis data dan tujuan penggunaannya</h2>

                    <p>
                        Gunakan pemetaan ini untuk memahami hubungan data dengan proses magang.
                        Cakupan data yang benar-benar dikumpulkan perlu dicocokkan dengan formulir
                        dan kebutuhan pengelola.
                    </p>

                    <div class="legal-table-wrap">
                        <table class="legal-table">
                            <thead>
                                <tr>
                                    <th>Kelompok data</th>
                                    <th>Contoh informasi</th>
                                    <th>Tujuan dalam proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Profil akademik</td>
                                    <td>Nama, NIM, program studi, angkatan, dan informasi akademik yang diminta.</td>
                                    <td>Mengenali mahasiswa dan mendukung administrasi serta pendampingan magang.</td>
                                </tr>
                                <tr>
                                    <td>Kontak</td>
                                    <td>Email atau kontak pengguna; kontak darurat bila dipersyaratkan kampus.</td>
                                    <td>Komunikasi terkait pengajuan, bimbingan, dan kebutuhan administratif yang relevan.</td>
                                </tr>
                                <tr>
                                    <td>Portofolio &amp; sertifikat</td>
                                    <td>CV, proyek, pengalaman, keahlian, pencapaian, serta bukti sertifikat.</td>
                                    <td>Menjelaskan kemampuan dan pengalaman untuk peninjauan profil atau lamaran.</td>
                                </tr>
                                <tr>
                                    <td>Lamaran &amp; penempatan</td>
                                    <td>Posisi yang dilamar, berkas persyaratan, keputusan, mitra, dan periode penempatan.</td>
                                    <td>Memproses pengajuan dan mencatat perkembangan penempatan magang.</td>
                                </tr>
                                <tr>
                                    <td>Logbook, laporan &amp; penilaian</td>
                                    <td>Aktivitas, dokumen laporan, versi, catatan review, tanda tangan logbook, dan penilaian.</td>
                                    <td>Pemantauan kegiatan, review akademik, tindak lanjut administratif, dan penilaian sesuai peran.</td>
                                </tr>
                                <tr>
                                    <td>Perusahaan &amp; PIC</td>
                                    <td>Identitas perusahaan, informasi kerja sama, serta nama dan kontak penanggung jawab mitra.</td>
                                    <td>Administrasi mitra, komunikasi kerja sama, dan pengelolaan formasi atau pelamar.</td>
                                </tr>
                                <tr>
                                    <td>Akses &amp; token</td>
                                    <td>Identitas akun, peran, dan informasi terkait pemberian akses atau token mitra; tanpa menampilkan nilai token.</td>
                                    <td>Mengatur akses yang diberikan dan membantu penanganan kendala akses sesuai kewenangan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="legal-callout is-neutral">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Berkas identitas tidak otomatis publik
                        </p>
                        <p>
                            Berkas identitas seperti KTP atau KTM hanya relevan bila dipersyaratkan
                            kampus. Keberadaannya dalam proses administrasi tidak berarti berkas
                            tersebut boleh ditampilkan kepada publik.
                        </p>
                    </div>
                </article>


                <!-- 03 -->
                <article class="legal-section" id="akses-peran">
                    <h2><span class="num">03</span> Siapa yang memerlukan akses?</h2>

                    <p>
                        Akses seharusnya dibatasi pada tujuan dan tanggung jawab peran, bukan diberikan
                        kepada semua pengguna. Rincian izin dan pelaksanaannya perlu ditetapkan serta
                        ditinjau pengelola.
                    </p>

                    <div class="legal-table-wrap">
                        <table class="legal-table">
                            <thead>
                                <tr>
                                    <th>Pihak</th>
                                    <th>Kebutuhan akses yang relevan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mahasiswa</td>
                                    <td>Data miliknya sendiri, pengajuan, logbook, laporan, status, dan catatan yang diperlukan untuk tindakan berikutnya.</td>
                                </tr>
                                <tr>
                                    <td>Koordinator</td>
                                    <td>Data mitra, lowongan, akses token, serta data untuk menetapkan satu dosen akademik per mahasiswa. Bukan peran review laporan.</td>
                                </tr>
                                <tr>
                                    <td>Dosen</td>
                                    <td>Data mahasiswa bimbingan, pengajuan dan logbook sesuai antrean, serta laporan dan versi yang diajukan untuk review.</td>
                                </tr>
                                <tr>
                                    <td>Mitra</td>
                                    <td>Informasi formasi dan pelamar yang relevan, mahasiswa terkait, logbook sesuai peran, dan kebutuhan penilaian. Bukan akses umum ke semua berkas kampus.</td>
                                </tr>
                                <tr>
                                    <td>Tendik</td>
                                    <td>Administrasi akun mitra / pengguna dan data laporan untuk pemeriksaan administratif sesudah dosen menyetujui. Tidak mencakup menu Logbook atau penetapan dosen.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="legal-callout">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Profil publik tidak sama dengan berkas pribadi
                        </p>
                        <p>
                            KTP, KTM, kontak darurat, dokumen lamaran sensitif, dan laporan tidak boleh
                            diasumsikan publik. Akses pada profil atau portofolio tidak dengan sendirinya
                            memberikan akses pada seluruh berkas pribadi mahasiswa.
                        </p>
                    </div>
                </article>


                <!-- 04 -->
                <article class="legal-section" id="informasi-publik">
                    <h2><span class="num">04</span> Informasi publik dan pilihan pengguna</h2>

                    <p>
                        Direktori publik dapat memuat profil mahasiswa atau informasi mitra yang memang
                        ditujukan untuk pengenalan. Pengelola perlu menetapkan bidang apa yang tampil,
                        siapa yang berwenang menerbitkannya, dan bagaimana pengguna mengetahui atau
                        meminta perubahan atas tampilan tersebut.
                    </p>

                    <p>
                        Sebagai usulan, informasi pengenalan dapat dibatasi pada nama, program studi,
                        keahlian, dan karya yang diizinkan untuk ditampilkan. Kontak pribadi, berkas
                        identitas, kontak darurat, serta isi laporan harus dinilai terpisah dan tidak
                        dianggap ikut terbuka. Pengguna perlu memastikan karya tidak memuat data rahasia
                        kampus atau mitra.
                    </p>

                    <div class="legal-callout">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Kontrol yang perlu ditetapkan pengelola
                        </p>
                        <p>
                            Pilihan bidang publik, mekanisme persetujuan tampilan, dan cara menarik
                            tampilan perlu dikonfirmasi. Halaman ini tidak menyatakan sudah ada tombol
                            pengaturan privasi; ajukan pertanyaan atau perubahan melalui email pengelola.
                        </p>
                    </div>
                </article>


                <!-- 05 -->
                <article class="legal-section" id="pembagian-akses">
                    <h2><span class="num">05</span> Pembagian akses untuk proses magang</h2>

                    <p>
                        Dalam proses lamaran, pihak mitra memerlukan informasi kandidat dan berkas yang
                        terkait dengan lowongan. Dalam pendampingan, dosen memerlukan data mahasiswa
                        bimbingan. Setelah dosen menyetujui laporan, tendik memerlukan dokumen dan status
                        terkait untuk pemeriksaan administratif.
                    </p>

                    <p>
                        Pembagian tersebut sebaiknya mengikuti keterkaitan penempatan, bimbingan, dan
                        tugas, bukan akses menyeluruh ke semua mahasiswa. Koordinator tidak menjadi tahap
                        persetujuan laporan tambahan dan mitra bukan approver laporan. Rincian data yang
                        dibagikan kepada setiap pihak perlu didokumentasikan pengelola.
                    </p>

                    <p>
                        Jika kegiatan memerlukan pengiriman data ke pihak lain di luar alur yang
                        dijelaskan, tujuan, penerima, dan kewenangan pembagiannya perlu diperiksa lebih
                        dahulu. Pengguna tidak seharusnya meneruskan dokumen pribadi pihak lain tanpa
                        dasar atau izin yang sesuai.
                    </p>
                </article>


                <!-- 06 -->
                <article class="legal-section" id="penyimpanan">
                    <h2><span class="num">06</span> Penyimpanan, pembaruan, dan penghapusan</h2>

                    <p>
                        Pengelola perlu menetapkan jangka retensi untuk profil, dokumen lamaran, logbook,
                        laporan, penilaian, serta data akses dengan mempertimbangkan tujuan akademik dan
                        administratif. Lokasi penyimpanan, penanganan salinan, serta kebutuhan arsip perlu
                        dikonfirmasi; tidak ada durasi simpan tertentu yang dinyatakan di sini.
                    </p>

                    <p>
                        Pengguna dapat meminta pembaruan atau penghapusan data melalui kontak pengelola.
                        Permohonan perlu ditelaah terhadap kebutuhan proses yang masih berjalan dan
                        kewajiban penyimpanan yang berlaku. Penghapusan tidak dijanjikan instan; pengelola
                        perlu menjelaskan hasil penelaahan, cakupan tindakan, atau alasan data masih perlu
                        disimpan. Perubahan atau penghapusan tampilan publik juga perlu dibedakan dari
                        penghapusan arsip akademik dan administratif.
                    </p>
                </article>


                <!-- 07 -->
                <article class="legal-section" id="permohonan-data">
                    <h2><span class="num">07</span> Mengajukan permohonan terkait data</h2>

                    <p>
                        Permohonan akses, koreksi, keberatan atas penggunaan, atau penghapusan data dapat
                        disampaikan ke
                        <a class="font-medium text-[#0669d8] hover:underline"
                           href="mailto:verifymagang@polinema.ac.id">verifymagang@polinema.ac.id</a>.
                    </p>

                    <div class="legal-step">
                        <h3><span class="num">1.</span> Jelaskan kebutuhan Anda</h3>
                        <p>
                            Tuliskan nama, peran, kategori permohonan, dan data atau proses yang dimaksud.
                            Contoh: koreksi program studi pada profil atau pertanyaan tentang tampilan
                            portofolio.
                        </p>
                    </div>

                    <div class="legal-step">
                        <h3><span class="num">2.</span> Lakukan identifikasi yang wajar</h3>
                        <p>
                            Pengelola dapat meminta informasi yang relevan untuk memastikan pemohon berhak
                            atas data tersebut. Jangan sertakan password, nilai token, atau salinan KTP
                            pada email awal.
                        </p>
                    </div>

                    <div class="legal-step">
                        <h3><span class="num">3.</span> Tindak lanjuti penjelasan pengelola</h3>
                        <p>
                            Pengelola perlu menilai kewenangan dan cakupan permohonan, lalu menjelaskan
                            tindak lanjut atau keterbatasannya. Cara pemeriksaan dan waktu tanggapan perlu
                            ditetapkan; tidak ada batas hari yang dijanjikan di halaman ini.
                        </p>
                    </div>
                </article>


                <!-- 08 -->
                <article class="legal-section" id="keamanan-akun">
                    <h2><span class="num">08</span> Keamanan akun, token, dan pelaporan insiden</h2>

                    <p>
                        Jaga akses akun yang diberikan kampus dan jangan gunakan akun milik orang lain.
                        Token mitra diterima dari koordinator, digunakan sesuai petunjuk akses, dan
                        dibagikan hanya kepada pihak yang berwenang. Masa berlaku mengikuti petunjuk
                        pengelola, bukan durasi tetap yang diasumsikan pengguna.
                    </p>

                    <p>
                        Jika menduga akun disalahgunakan, token tersebar, atau dokumen terlihat oleh pihak
                        yang tidak semestinya, laporkan melalui email pengelola. Jelaskan kejadian, peran,
                        dan konteks halaman; gunakan bukti yang sudah disamarkan dan jangan meneruskan
                        rahasia akses dalam laporan awal.
                    </p>

                    <div class="legal-callout">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Perlindungan perlu ditinjau, bukan dijamin mutlak
                        </p>
                        <p>
                            Pengelola perlu mengonfirmasi kontrol akses, prosedur penanganan insiden, serta
                            langkah perlindungan yang diterapkan. Halaman ini tidak mengklaim keamanan
                            100%, teknologi enkripsi tertentu, atau sertifikasi kepatuhan.
                        </p>
                    </div>
                </article>


                <!-- 09 -->
                <article class="legal-section" id="cookies">
                    <h2><span class="num">09</span> Cookies dan layanan eksternal</h2>

                    <p>
                        Penggunaan cookies, penyimpanan lokal, atau data teknis layanan perlu diperiksa
                        berdasarkan implementasi yang sebenarnya. Pengelola perlu menjelaskan jenis,
                        tujuan, kebutuhan persetujuan, dan cara pengendaliannya jika teknologi tersebut
                        digunakan. Naskah ini tidak menyatakan adanya pelacak analitik tertentu.
                    </p>

                    <p>
                        Bila ada penyedia layanan eksternal atau pemrosesan data di luar VerifyMagang,
                        pengelola perlu mengonfirmasi penerima, data yang terlibat, dan tujuan
                        penggunaannya sebelum memasukkannya ke kebijakan. Nama vendor, lokasi server,
                        maupun praktik penjualan data tidak diasumsikan di halaman ini.
                    </p>
                </article>


                <!-- 10 -->
                <article class="legal-section" id="perubahan-kebijakan">
                    <h2><span class="num">10</span> Perubahan kebijakan</h2>

                    <p>
                        Kebijakan perlu ditinjau ketika proses magang, jenis data, kebutuhan akses, atau
                        layanan pendukung berubah. Pengelola perlu menyediakan penjelasan perubahan yang
                        bermakna dan menentukan cara pemberitahuan kepada pengguna sebelum menetapkan
                        naskah final atau pembaruan berikutnya.
                    </p>

                    <p>
                        Tanggal berlaku, versi yang disahkan, serta mekanisme pemberitahuan akan mengikuti
                        keputusan pengelola. Membaca halaman draf ini tidak diartikan sebagai persetujuan
                        terhadap penggunaan data baru yang belum dijelaskan.
                    </p>
                </article>


                <!-- 11 -->
                <article class="legal-section" id="kontak-pengelola">
                    <h2><span class="num">11</span> Kontak pengelola</h2>

                    <p>
                        Pertanyaan tentang privasi dan permohonan data menggunakan kontak VerifyMagang
                        yang sama. Tidak ada alamat petugas privasi terpisah yang ditetapkan pada
                        halaman ini.
                    </p>
                </article>


                <!-- Contact card -->
                <div class="legal-contact">
                    <h3>Pertanyaan tentang data Anda?</h3>

                    <p>
                        Untuk pertanyaan, koreksi data, atau pengaduan, gunakan alamat kontak yang sama.
                        Pengelola dapat meminta informasi tambahan yang relevan untuk menindaklanjuti
                        permohonan.
                    </p>

                    <a class="legal-contact-btn" href="mailto:verifymagang@polinema.ac.id">
                        <?= icon('mail', 'w-4 h-4') ?>
                        verifymagang@polinema.ac.id
                    </a>

                    <p class="legal-contact-note">
                        Cantumkan nama, peran, dan deskripsi masalah. Jangan kirim password, nilai token,
                        atau salinan KTP pada email awal. Bila perlu, gunakan tangkapan layar yang sudah
                        disamarkan.
                    </p>
                </div>


                <div class="legal-meta">
                    <span>
                        Politeknik Negeri Malang &middot; Jl. Soekarno Hatta No. 9, Malang, Jawa Timur
                    </span>

                    <a href="<?= url('/syarat-ketentuan') ?>">
                        Bacaan terkait Syarat dan Ketentuan
                        <?= icon('right', 'w-3.5 h-3.5') ?>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>


<?php include __DIR__ . "/../../../components/landingPage/Footer.php"; ?>

<?php include __DIR__ . "/../../../components/landingPage/Script.php"; ?>

<script>
    /*
     * Sorot entri daftar isi yang sedang terbaca.
     * IntersectionObserver dipilih supaya tidak membebani scroll.
     */
    (function () {
        const links = Array.from(document.querySelectorAll('#legal-toc a[data-toc]'));
        if (!links.length) return;

        const map = new Map(
            links.map((link) => [link.dataset.toc, link])
        );

        const sections = Array.from(
            document.querySelectorAll('#legal-content .legal-section')
        );

        if (!sections.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;

                    links.forEach((link) => link.classList.remove('is-active'));

                    const active = map.get(entry.target.id);
                    if (active) active.classList.add('is-active');
                });
            },
            { rootMargin: '-96px 0px -70% 0px', threshold: 0 }
        );

        sections.forEach((section) => observer.observe(section));
    })();
</script>

</body>
</html>
