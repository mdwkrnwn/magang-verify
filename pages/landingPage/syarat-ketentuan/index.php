<?php

$active = "";

$toc = [
    ['01', 'Ruang lingkup & peran', 'ruang-lingkup'],
    ['02', 'Akses akun & tanggung jawab', 'akses-akun'],
    ['03', 'Kebenaran data & unggahan', 'kebenaran-data'],
    ['04', 'Profil, portofolio & karya', 'profil-karya'],
    ['05', 'Lamaran & keputusan mitra', 'lamaran-mitra'],
    ['06', 'Penetapan pembimbing', 'penetapan-pembimbing'],
    ['07', 'Logbook, laporan & revisi', 'logbook-laporan'],
    ['08', 'Mitra & akses token', 'mitra-token'],
    ['09', 'Penggunaan yang dilarang', 'penggunaan-dilarang'],
    ['10', 'Kendala & penanganan akses', 'kendala-akses'],
    ['11', 'Privasi, bantuan & perubahan', 'privasi-bantuan'],
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
            <span class="font-medium text-[#3f5b7c]">Syarat dan Ketentuan</span>
        </nav>

        <p class="legal-eyebrow mt-5">
            <span><?= icon('shield', 'w-4 h-4') ?></span>
            Informasi Pengguna
        </p>

        <h1 class="legal-title">Syarat dan Ketentuan</h1>

        <p class="legal-lead">
            Panduan tanggung jawab dalam menggunakan VerifyMagang: akses yang tepat,
            data yang dapat dipertanggungjawabkan, dan proses magang sesuai peran.
        </p>

        <p class="legal-draft">
            Draf untuk ditinjau pengelola — belum ketentuan final
        </p>

    </div>
</section>


<!-- ============================= -->
<!-- Tiga hal yang perlu diingat   -->
<!-- ============================= -->
<section class="legal legal-summary py-8">
    <div class="legal-shell">

        <h2 class="legal-reminders-title">
            <?= icon('bolt', 'w-4 h-4') ?>
            Tiga hal yang perlu Anda ingat
        </h2>

        <div class="legal-reminders-grid">

            <div class="legal-reminder">
                <h3>
                    <?= icon('shield', 'w-4 h-4') ?>
                    Gunakan akses sesuai peran
                </h3>
                <p>
                    Akun dan token bukan untuk dibagikan secara umum atau dipakai
                    melampaui kewenangan.
                </p>
            </div>

            <div class="legal-reminder">
                <h3>
                    <?= icon('award', 'w-4 h-4') ?>
                    Pastikan data dan karya sah
                </h3>
                <p>
                    Unggah informasi yang benar dan karya yang berhak Anda gunakan.
                    Kepemilikan karya tetap pada pemiliknya.
                </p>
            </div>

            <div class="legal-reminder">
                <h3>
                    <?= icon('layers', 'w-4 h-4') ?>
                    Ikuti status, bukan asumsi
                </h3>
                <p>
                    Melamar bukan berarti diterima. Persetujuan laporan oleh dosen masih
                    diikuti pemeriksaan tendik.
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

                    <a class="legal-toc-more" href="#privasi-bantuan">
                        Pelajari Sistem
                        <?= icon('right', 'w-3.5 h-3.5') ?>
                    </a>

                    <p class="legal-toc-more-label">Baca juga</p>

                    <ul>
                        <li>
                            <a href="<?= url('/privasi') ?>">
                                Kebijakan Privasi
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
                    <h2><span class="num">01</span> Ruang lingkup VerifyMagang dan peran</h2>

                    <p>
                        VerifyMagang mendukung pengelolaan profil, portofolio, lowongan, pengajuan,
                        penempatan, logbook, laporan, dan penilaian magang. Naskah ini merangkum
                        ketentuan penggunaan yang diusulkan untuk ditinjau pengelola; bukan ketentuan
                        resmi institusi yang sudah disahkan.
                    </p>

                    <p>
                        Mahasiswa mengelola data dan proses magangnya. Koordinator mengelola mitra,
                        token, lowongan, serta penetapan dosen. Mitra mengelola formasi, pelamar,
                        pemantauan logbook sesuai peran, dan penilaian. Dosen mendampingi serta melakukan
                        review. Tendik mengurus administrasi akun dan tindak lanjut administratif laporan
                        setelah persetujuan dosen.
                    </p>

                    <div class="legal-callout">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Verifikasi bukan jaminan hasil
                        </p>
                        <p>
                            Pemeriksaan atau label verifikasi pada suatu proses tidak menjamin semua
                            informasi selalu benar, penerimaan magang, nilai final, ataupun hasil magang
                            tertentu. Pengguna tetap perlu memeriksa konteks dan rincian keputusan.
                        </p>
                    </div>
                </article>


                <!-- 02 -->
                <article class="legal-section" id="akses-akun">
                    <h2><span class="num">02</span> Akses akun dan tanggung jawab pengguna</h2>

                    <p>
                        Akun kampus menggunakan akses yang diberikan pengelola. Mitra mengikuti petunjuk
                        akses yang diberikan untuk perannya. Jangan mengasumsikan adanya pendaftaran
                        mandiri atau akses melalui layanan lain yang belum disediakan. Jika tidak dapat
                        masuk atau peran tidak sesuai, minta bantuan pengelola sebelum melanjutkan.
                    </p>

                    <p>
                        Pengguna bertanggung jawab menjaga rahasia akses, memastikan tindakan dilakukan
                        melalui akun yang tepat, dan tidak menggunakan akun orang lain. Jangan membagikan
                        password atau token di ruang publik. Setelah menggunakan perangkat bersama,
                        pastikan sesi tidak ditinggalkan untuk digunakan pihak lain.
                    </p>

                    <div class="legal-callout is-neutral">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Laporkan, jangan ambil alih
                        </p>
                        <p>
                            Dugaan penyalahgunaan akses perlu dilaporkan melalui kontak bantuan pada bagian
                            11; jangan mencoba mengambil alih akun untuk memeriksa masalah.
                        </p>
                    </div>
                </article>


                <!-- 03 -->
                <article class="legal-section" id="kebenaran-data">
                    <h2><span class="num">03</span> Kebenaran data dan otorisasi unggahan</h2>

                    <p>
                        Isi profil, pengalaman, sertifikat, lamaran, logbook, dan laporan secara benar
                        serta sesuai keadaan yang dapat dipertanggungjawabkan. Periksa keterbacaan berkas
                        dan kesesuaian versi sebelum mengajukan. Jika menemukan kesalahan, lakukan
                        koreksi melalui proses yang tersedia atau hubungi pengelola bila data tidak dapat
                        diperbaiki sendiri.
                    </p>

                    <p>
                        Unggah hanya dokumen yang berhak Anda gunakan dan bagikan. Jangan memakai
                        identitas, tanda tangan, sertifikat, atau karya pihak lain tanpa izin yang sesuai.
                        Pastikan dokumen tidak membuka data pribadi atau rahasia kampus dan mitra yang
                        tidak diperlukan untuk tujuan pengajuan.
                    </p>

                    <div class="legal-callout is-neutral">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Berkas tambahan bukan bahan portofolio
                        </p>
                        <p>
                            Berkas tambahan seperti KTP, KTM, KHS, pakta integritas, atau surat izin orang
                            tua mengikuti persyaratan kampus; bukan bahan portofolio publik secara
                            otomatis.
                        </p>
                    </div>
                </article>


                <!-- 04 -->
                <article class="legal-section" id="profil-karya">
                    <h2><span class="num">04</span> Profil, portofolio, dan kepemilikan karya</h2>

                    <p>
                        Profil dan portofolio membantu menjelaskan kemampuan, pengalaman, serta pencapaian
                        mahasiswa. Pengunggahan karya tidak memindahkan hak kepemilikan kepada
                        VerifyMagang, kampus, atau mitra. Cantumkan kontribusi Anda secara jelas, termasuk
                        bila proyek dikerjakan bersama tim atau menggunakan materi pihak lain.
                    </p>

                    <p>
                        Penayangan karya seharusnya terbatas pada tujuan yang diizinkan dan informasi yang
                        layak dibagikan. Cakupan tampilan publik serta kontrol penerbitannya perlu
                        ditetapkan pengelola. Jika ada keberatan atas karya atau tampilan data, sampaikan
                        konteks dan bagian yang dipermasalahkan melalui kontak bantuan, tanpa menyebarkan
                        ulang materi sensitif.
                    </p>
                </article>


                <!-- 05 -->
                <article class="legal-section" id="lamaran-mitra">
                    <h2><span class="num">05</span> Lamaran, lowongan, dan keputusan mitra</h2>

                    <p>
                        Mahasiswa perlu membaca posisi, persyaratan, dan dokumen yang diminta sebelum
                        mengajukan lamaran. Informasi lowongan membantu pengajuan, tetapi kebutuhan posisi
                        dan keputusan penerimaan berada pada pihak mitra serta proses kampus yang terkait.
                        Mengirim lamaran tidak sama dengan diterima atau memperoleh penempatan.
                    </p>

                    <p>
                        Mitra menggunakan aksesnya untuk mengelola formasi dan pelamar sesuai kewenangan.
                        Mahasiswa memantau pengajuan, membaca catatan, serta menindaklanjuti permintaan
                        perbaikan yang relevan. Tidak ada jaminan penerimaan atau batas waktu persetujuan
                        tertentu yang dinyatakan pada halaman ini.
                    </p>

                    <div class="legal-callout">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Data mitra dan publikasi lowongan adalah tindakan berbeda
                        </p>
                        <p>
                            Koordinator dapat menyimpan lowongan sebagai Draft dan memilih Publikasikan
                            saat siap. Menyimpan identitas mitra tidak otomatis membuat token akses atau
                            menerbitkan lowongan.
                        </p>
                    </div>
                </article>


                <!-- 06 -->
                <article class="legal-section" id="penetapan-pembimbing">
                    <h2><span class="num">06</span> Penetapan dosen pembimbing per mahasiswa</h2>

                    <p>
                        Koordinator menetapkan satu dosen pembimbing akademik untuk setiap mahasiswa yang
                        akan magang. Penetapan tidak dilakukan sebagai satu dosen untuk seluruh perusahaan
                        atau lowongan. Jika pembimbing belum tercantum atau penetapan perlu diperiksa,
                        mahasiswa menghubungi koordinator.
                    </p>

                    <p>
                        Dosen akademik mendampingi mahasiswa bimbingan, melihat data dan status, serta
                        melakukan review sesuai antrean dan kewenangannya. Pembimbing di mitra tetap
                        merupakan peran berbeda. Penetapan dosen akademik tidak mengganti pembimbing
                        mitra, tidak mengubah keputusan penerimaan, dan tidak menjadi tugas tendik.
                    </p>
                </article>


                <!-- 07 -->
                <article class="legal-section" id="logbook-laporan">
                    <h2><span class="num">07</span> Logbook, laporan, revisi, dan status versi</h2>

                    <p>
                        Logbook merupakan catatan aktivitas magang. Mahasiswa perlu mencatat kegiatan,
                        hasil, dan kendala secara konsisten. Tanda tangan mahasiswa, mitra, dan dosen
                        diperiksa pada versi logbook yang sama sesuai kesiapan versi; belum lengkapnya
                        tanda tangan tidak otomatis berarti menunggu dosen.
                    </p>

                    <p>
                        Laporan magang merupakan proses terpisah. Mahasiswa menyusun Draft, memeriksa isi
                        dan versi, lalu mengajukan kepada dosen. Draft yang hanya disimpan tidak masuk
                        antrean review dosen. Berkas yang sudah diperbaiki juga perlu diajukan ulang agar
                        versi perbaikan dapat diperiksa.
                    </p>

                    <div class="legal-table-wrap">
                        <table class="legal-table is-tinted">
                            <thead>
                                <tr>
                                    <th>Status laporan</th>
                                    <th>Pihak yang bertindak</th>
                                    <th>Tanggung jawab</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Draft</td>
                                    <td>Mahasiswa</td>
                                    <td>Susun dan periksa laporan; ajukan saat siap. Belum masuk antrean dosen.</td>
                                </tr>
                                <tr>
                                    <td>Menunggu review dosen</td>
                                    <td>Dosen pembimbing</td>
                                    <td>Periksa isi versi yang diajukan dan berikan keputusan atau arahan revisi.</td>
                                </tr>
                                <tr>
                                    <td>Perlu revisi mahasiswa</td>
                                    <td>Mahasiswa</td>
                                    <td>Perbaiki sesuai catatan spesifik dosen, lalu ajukan ulang kepada dosen.</td>
                                </tr>
                                <tr>
                                    <td>Diteruskan ke tendik / Menunggu tindak lanjut tendik</td>
                                    <td>Tendik</td>
                                    <td>Setelah dosen menyetujui, lakukan pemeriksaan administratif dan berikan tindak lanjut yang relevan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="legal-callout">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Persetujuan dosen belum menyelesaikan seluruh proses
                        </p>
                        <p>
                            Setelah dosen setuju, laporan masih memerlukan tindak lanjut administratif
                            tendik. Koordinator bukan tahap persetujuan laporan tambahan; mitra bukan
                            penandatangan atau approver laporan magang. Tanda tangan logbook tidak
                            menggantikan review laporan.
                        </p>
                        <p>
                            Contoh arahan revisi yang spesifik: “Tambahkan hasil uji pada Bab 3 dan
                            cocokkan periode kegiatan pada ringkasan.” Mahasiswa perlu memperbaiki bagian
                            tersebut, bukan sekadar mengunggah kembali berkas yang sama. Persetujuan
                            berlaku pada versi yang diperiksa, bukan asumsi untuk semua versi berikutnya.
                        </p>
                    </div>
                </article>


                <!-- 08 -->
                <article class="legal-section" id="mitra-token">
                    <h2><span class="num">08</span> Pengelolaan mitra dan akses token</h2>

                    <p>
                        Koordinator mengelola data mitra dan membuat token akses mitra melalui proses
                        tersendiri. Mitra mendapatkan token dari koordinator, menggunakannya sesuai
                        petunjuk akses, dan menghubungi koordinator bila token tidak berlaku atau tidak
                        dapat digunakan. Masa berlaku mengikuti petunjuk pengelola, bukan durasi tetap
                        yang ditetapkan dalam naskah ini.
                    </p>

                    <p>
                        Bagikan token hanya secara privat kepada pihak mitra yang berwenang. Jangan
                        menampilkan nilainya pada portofolio, unggahan publik, tangkapan layar, atau pesan
                        kelompok terbuka. Jika token diduga tersebar, laporkan konteks ke koordinator atau
                        pengelola tanpa menyalin nilai token pada email awal.
                    </p>

                    <div class="legal-callout is-neutral">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Rancangan akses tidak sama dengan bukti aktivasi
                        </p>
                        <p>
                            Adanya token pada rancangan tidak membuktikan integrasi login aktif atau akun
                            berhasil terbentuk. Ikuti petunjuk akses terbaru; jangan mencampurkan token
                            dengan mekanisme password lama pada administrasi akun.
                        </p>
                    </div>
                </article>


                <!-- 09 -->
                <article class="legal-section" id="penggunaan-dilarang">
                    <h2><span class="num">09</span> Penggunaan yang tidak diperbolehkan</h2>

                    <p>
                        Batas berikut melindungi keandalan proses akademik, karya, dan data pribadi.
                        Temuan perlu dilaporkan agar dapat diperiksa oleh pengelola.
                    </p>

                    <div class="legal-banned">
                        <p class="legal-banned-head">Penggunaan yang tidak diperbolehkan</p>

                        <div class="legal-banned-body">
                        <div class="legal-banned-item">
                            <h3>
                                <?= icon('shield', 'w-4 h-4') ?>
                                Pemalsuan identitas atau bukti
                            </h3>
                            <p>
                                Jangan mengatasnamakan pihak lain, memalsukan sertifikat, tanda tangan,
                                status magang, aktivitas, atau penilaian.
                            </p>
                        </div>

                        <div class="legal-banned-item">
                            <h3>
                                <?= icon('shield', 'w-4 h-4') ?>
                                Plagiarisme dan unggahan tanpa hak
                            </h3>
                            <p>
                                Jangan mengajukan karya orang lain sebagai milik sendiri atau mengunggah
                                dokumen yang tidak berhak Anda gunakan.
                            </p>
                        </div>

                        <div class="legal-banned-item">
                            <h3>
                                <?= icon('shield', 'w-4 h-4') ?>
                                Akses melampaui peran
                            </h3>
                            <p>
                                Jangan memakai akun atau token pihak lain, mencoba membuka data di luar
                                kewenangan, atau memanipulasi keputusan dan status proses.
                            </p>
                        </div>

                        <div class="legal-banned-item">
                            <h3>
                                <?= icon('shield', 'w-4 h-4') ?>
                                Penyebaran data pribadi
                            </h3>
                            <p>
                                Jangan mempublikasikan identitas sensitif, kontak darurat, laporan, atau
                                data pribadi pihak lain tanpa dasar dan izin yang sesuai.
                            </p>
                        </div>

                        </div>
                    </div>
                </article>


                <!-- 10 -->
                <article class="legal-section" id="kendala-akses">
                    <h2><span class="num">10</span> Kesalahan layanan, koreksi, dan penanganan akses</h2>

                    <p>
                        Jika tampilan status tidak sesuai, berkas gagal diproses, atau akses bermasalah,
                        catat konteks halaman dan tindakan yang dilakukan. Jangan mengubah data pihak lain
                        atau membuat kesimpulan bahwa pengajuan sudah diterima hanya karena suatu tampilan
                        muncul. Sampaikan kendala melalui kontak bantuan untuk diperiksa.
                    </p>

                    <p>
                        Pengelola perlu menetapkan prosedur pemeriksaan kesalahan, koreksi data, dan
                        penanganan dugaan pelanggaran. Bila pembatasan atau suspensi akses diperlukan,
                        dasar, kewenangan, pemberitahuan, serta jalur klarifikasi pengguna perlu
                        dikonfirmasi sesuai prosedur institusi. Naskah ini tidak memperkenalkan
                        pemblokiran otomatis baru.
                    </p>

                    <p>
                        Pengguna dapat meminta penjelasan atau menyampaikan koreksi atas temuan dengan
                        konteks yang relevan. Hasil penanganan mengikuti pemeriksaan pengelola; tidak ada
                        jaminan layanan tanpa kesalahan, SLA tanggapan, denda, atau mekanisme keputusan
                        sengketa final yang ditetapkan pada halaman ini.
                    </p>
                </article>


                <!-- 11 -->
                <article class="legal-section" id="privasi-bantuan">
                    <h2><span class="num">11</span> Privasi, bantuan, dan perubahan ketentuan</h2>

                    <p>
                        Penggunaan dan akses data perlu dibaca bersama Kebijakan Privasi, terutama
                        pemisahan informasi publik dari berkas pribadi serta jalur permohonan akses,
                        koreksi, keberatan, dan penghapusan. Kebutuhan operasional yang belum ditetapkan
                        perlu ditinjau pengelola sebelum menjadi ketentuan resmi.
                    </p>

                    <a class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-[#0669d8] hover:underline"
                       href="<?= url('/privasi') ?>">
                        <?= icon('shield', 'w-4 h-4') ?>
                        Baca Kebijakan Privasi
                        <?= icon('right', 'w-3.5 h-3.5') ?>
                    </a>

                    <div class="legal-callout mt-6">
                        <p class="legal-callout-title">
                            <?= icon('shield', 'w-4 h-4') ?>
                            Apa yang disampaikan saat meminta bantuan?
                        </p>

                        <ul class="mt-2 space-y-1.5 text-[13px] leading-relaxed text-[#3f5b7c]">
                            <li><span class="font-semibold text-[#071b3a]">Nama dan peran:</span> identitas Anda dalam proses magang.</li>
                            <li><span class="font-semibold text-[#071b3a]">Masalah:</span> halaman, status atau versi terkait, serta tindakan yang dilakukan.</li>
                            <li><span class="font-semibold text-[#071b3a]">Kebutuhan:</span> penjelasan, koreksi, atau pemeriksaan yang Anda harapkan.</li>
                        </ul>

                        <p>
                            Gunakan deskripsi singkat dan bukti yang disamarkan. Jangan kirim password,
                            nilai token, atau salinan KTP terbuka melalui email awal.
                        </p>
                    </div>

                    <p class="mt-6">
                        Ketentuan perlu ditinjau ketika alur layanan atau tanggung jawab berubah. Pengelola
                        perlu menentukan cara pemberitahuan, isi perubahan, serta tanggal berlaku setelah
                        peninjauan. Membuka halaman bacaan publik ini bukan tindakan persetujuan pengguna;
                        tidak ada penerimaan ketentuan yang diasumsikan dari kunjungan ini.
                    </p>
                </article>


                <!-- Contact card -->
                <div class="legal-contact">
                    <h3>Butuh bantuan atau ingin mengajukan pengaduan?</h3>

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

                    <a href="<?= url('/privasi') ?>">
                        Bacaan terkait Kebijakan Privasi
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
