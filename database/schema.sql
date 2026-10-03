-- TABEL UNTUK CATATAN MIGRATION DATABASE --

CREATE TABLE schema_migrations (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- TABEL USER --

CREATE TABLE users (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    login_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    password VARCHAR(255),
    role VARCHAR(30) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT users_role_check
        CHECK (
            role IN (
                'mahasiswa',
                'dosen',
                'koordinator_magang',
                'tendik',
                'mitra'
            )
        ),

    CONSTRAINT users_password_check
        CHECK (role = 'mitra' OR password IS NOT NULL)
);

-- TABEL PROFIL MAHASISWA --

CREATE TABLE profil_mahasiswa (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id BIGINT NOT NULL UNIQUE
        REFERENCES users(id) ON DELETE CASCADE,
    nim VARCHAR(30) NOT NULL UNIQUE,
    program_studi VARCHAR(150),
    angkatan SMALLINT,
    email VARCHAR(255),
    no_telepon VARCHAR(30),
    alamat TEXT,
    deskripsi TEXT,
    foto_path VARCHAR(500),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- TABEL PROFIL DOSEN --

CREATE TABLE profil_dosen (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id BIGINT NOT NULL UNIQUE
        REFERENCES users(id) ON DELETE CASCADE,
    nidn VARCHAR(30) UNIQUE,
    email VARCHAR(255),
    no_telepon VARCHAR(30),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- DATA PERUSAHAAN / MITRA
-- ============================================

CREATE TABLE mitra (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    user_id BIGINT UNIQUE
        REFERENCES users(id) ON DELETE SET NULL,

    nama_perusahaan VARCHAR(200) NOT NULL,
    kode_perusahaan VARCHAR(50) NOT NULL UNIQUE,

    kategori VARCHAR(50) NOT NULL,
    bidang_usaha VARCHAR(150),
    deskripsi TEXT,

    email VARCHAR(255),
    no_telepon VARCHAR(30),
    website VARCHAR(255),

    provinsi VARCHAR(100),
    kota VARCHAR(100),
    alamat TEXT,

    status_verifikasi VARCHAR(30) NOT NULL
        DEFAULT 'dalam_peninjauan',

    catatan_verifikasi TEXT,
    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,
    diverifikasi_pada TIMESTAMPTZ,

    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT mitra_kategori_check
        CHECK (
            kategori IN (
                'BUMN',
                'BUMD',
                'Perusahaan Swasta',
                'Instansi Pemerintah',
                'Institusi Pendidikan',
                'UMKM',
                'Organisasi',
                'Lainnya'
            )
        ),

    CONSTRAINT mitra_status_verifikasi_check
        CHECK (
            status_verifikasi IN (
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        )
);

CREATE INDEX idx_mitra_nama
    ON mitra(nama_perusahaan);

CREATE INDEX idx_mitra_status_verifikasi
    ON mitra(status_verifikasi);

CREATE INDEX idx_mitra_kota
    ON mitra(kota);

-- ============================================
-- FORMASI / LOWONGAN MAGANG
-- ============================================

CREATE TABLE formasi_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mitra_id BIGINT NOT NULL
        REFERENCES mitra(id) ON DELETE RESTRICT,

    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT NOT NULL,
    bidang VARCHAR(150),

    jumlah_kuota SMALLINT NOT NULL,
    jumlah_diterima SMALLINT NOT NULL DEFAULT 0,

    persyaratan TEXT,
    lokasi_magang VARCHAR(255),
    sistem_kerja VARCHAR(30) NOT NULL DEFAULT 'onsite',

    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,

    tahun_akademik VARCHAR(20) NOT NULL,

    status VARCHAR(30) NOT NULL DEFAULT 'draft',

    dibuat_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT formasi_kuota_check
        CHECK (
            jumlah_kuota > 0
            AND jumlah_diterima >= 0
            AND jumlah_diterima <= jumlah_kuota
        ),

    CONSTRAINT formasi_tanggal_check
        CHECK (tanggal_selesai >= tanggal_mulai),

    CONSTRAINT formasi_sistem_kerja_check
        CHECK (
            sistem_kerja IN (
                'onsite',
                'hybrid',
                'remote'
            )
        ),

    CONSTRAINT formasi_status_check
        CHECK (
            status IN (
                'draft',
                'dibuka',
                'ditutup',
                'selesai',
                'dibatalkan'
            )
        )
);

CREATE INDEX idx_formasi_mitra
    ON formasi_magang(mitra_id);

CREATE INDEX idx_formasi_status
    ON formasi_magang(status);

CREATE INDEX idx_formasi_periode
    ON formasi_magang(tanggal_mulai, tanggal_selesai);

CREATE INDEX idx_formasi_tahun_akademik
    ON formasi_magang(tahun_akademik);

-- ============================================
-- PENDAFTARAN MAGANG MAHASISWA
-- ============================================

CREATE TABLE pendaftaran_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mahasiswa_id BIGINT NOT NULL
        REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,

    formasi_id BIGINT NOT NULL
        REFERENCES formasi_magang(id) ON DELETE RESTRICT,

    -- Status proses persetujuan dosen
    status_persetujuan_dosen VARCHAR(30) NOT NULL
        DEFAULT 'menunggu',

    dosen_penyetuju_id BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    waktu_persetujuan_dosen TIMESTAMPTZ,
    catatan_dosen TEXT,

    -- Status keputusan perusahaan
    status_respons_mitra VARCHAR(30) NOT NULL
        DEFAULT 'menunggu',

    waktu_respons_mitra TIMESTAMPTZ,
    catatan_mitra TEXT,
    jadwal_seleksi TIMESTAMPTZ,

    -- Status keseluruhan pendaftaran
    status_pendaftaran VARCHAR(30) NOT NULL
        DEFAULT 'diajukan',

    tanggal_pengajuan TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    tanggal_selesai_proses TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pendaftaran_status_dosen_check
        CHECK (
            status_persetujuan_dosen IN (
                'menunggu',
                'disetujui',
                'ditolak',
                'perlu_revisi'
            )
        ),

    CONSTRAINT pendaftaran_status_mitra_check
        CHECK (
            status_respons_mitra IN (
                'menunggu',
                'diterima',
                'ditolak',
                'seleksi'
            )
        ),

    CONSTRAINT pendaftaran_status_check
        CHECK (
            status_pendaftaran IN (
                'diajukan',
                'menunggu_persetujuan_dosen',
                'menunggu_respons_mitra',
                'seleksi',
                'diterima',
                'ditolak_dosen',
                'ditolak_mitra',
                'perlu_revisi',
                'dibatalkan'
            )
        )
);


-- ============================================
-- RIWAYAT PERUBAHAN STATUS PENDAFTARAN
-- ============================================

CREATE TABLE riwayat_pendaftaran_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    pendaftaran_id BIGINT NOT NULL
        REFERENCES pendaftaran_magang(id) ON DELETE RESTRICT,

    status_sebelumnya VARCHAR(30),
    status_baru VARCHAR(30) NOT NULL,

    catatan TEXT,

    diubah_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    waktu_perubahan TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_pendaftaran_mahasiswa
    ON pendaftaran_magang(mahasiswa_id);

CREATE INDEX idx_pendaftaran_formasi
    ON pendaftaran_magang(formasi_id);

CREATE INDEX idx_pendaftaran_status
    ON pendaftaran_magang(status_pendaftaran);

CREATE INDEX idx_pendaftaran_dosen
    ON pendaftaran_magang(dosen_penyetuju_id);

CREATE INDEX idx_riwayat_pendaftaran
    ON riwayat_pendaftaran_magang(pendaftaran_id, waktu_perubahan);


-- ============================================
-- BATASI PENDAFTARAN AKTIF MAHASISWA
-- ============================================

CREATE UNIQUE INDEX uq_pendaftaran_mahasiswa_aktif
    ON pendaftaran_magang(mahasiswa_id)
    WHERE status_pendaftaran IN (
        'diajukan',
        'menunggu_persetujuan_dosen',
        'menunggu_respons_mitra',
        'seleksi',
        'diterima',
        'perlu_revisi'
    );


-- ============================================
-- PENEMPATAN MAGANG
-- ============================================

CREATE TABLE penempatan_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    pendaftaran_id BIGINT NOT NULL UNIQUE
        REFERENCES pendaftaran_magang(id) ON DELETE RESTRICT,

    dosen_pembimbing_id BIGINT
        REFERENCES profil_dosen(id) ON DELETE RESTRICT,

    -- Periode pelaksanaan magang
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,

    -- Status pelaksanaan magang
    status VARCHAR(30) NOT NULL DEFAULT 'persiapan',

    catatan TEXT,

    ditetapkan_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    tanggal_penetapan TIMESTAMPTZ
        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT penempatan_tanggal_check
        CHECK (tanggal_selesai >= tanggal_mulai),

    CONSTRAINT penempatan_status_check
        CHECK (
            status IN (
                'persiapan',
                'berlangsung',
                'menunggu_penilaian',
                'selesai',
                'dibatalkan'
            )
        )
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_penempatan_dosen
    ON penempatan_magang(dosen_pembimbing_id);

CREATE INDEX idx_penempatan_status
    ON penempatan_magang(status);

CREATE INDEX idx_penempatan_periode
    ON penempatan_magang(tanggal_mulai, tanggal_selesai);


-- ============================================
-- LOGBOOK MINGGUAN
-- ============================================

CREATE TABLE logbook_mingguan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    minggu_ke SMALLINT NOT NULL,

    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,

    versi_terkini INTEGER NOT NULL DEFAULT 1,

    status VARCHAR(30) NOT NULL DEFAULT 'draft',

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_minggu_check
        CHECK (minggu_ke > 0),

    CONSTRAINT logbook_tanggal_check
        CHECK (tanggal_selesai >= tanggal_mulai),

    CONSTRAINT logbook_versi_check
        CHECK (versi_terkini > 0),

    CONSTRAINT logbook_status_check
        CHECK (
            status IN (
                'draft',
                'diajukan',
                'menunggu_mitra',
                'menunggu_dosen',
                'menunggu_verifikasi',
                'perlu_revisi',
                'disetujui',
                'ditolak'
            )
        ),

    CONSTRAINT logbook_minggu_unik
        UNIQUE (penempatan_id, minggu_ke)
);


-- ============================================
-- RIWAYAT / VERSI LOGBOOK
-- Setiap perubahan isi dibuat sebagai versi baru.
-- ============================================

CREATE TABLE logbook_revisi (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    logbook_id BIGINT NOT NULL
        REFERENCES logbook_mingguan(id) ON DELETE RESTRICT,

    nomor_versi INTEGER NOT NULL,

    aktivitas TEXT NOT NULL,
    hasil_pekerjaan TEXT,
    kendala TEXT,
    rencana_selanjutnya TEXT,

    catatan_revisi TEXT,

    dibuat_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    dibuat_pada TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_revisi_versi_check
        CHECK (nomor_versi > 0),

    CONSTRAINT logbook_revisi_unik
        UNIQUE (logbook_id, nomor_versi)
);


-- ============================================
-- TANDA TANGAN / PERSETUJUAN LOGBOOK
-- Tanda tangan mengacu ke versi tertentu.
-- ============================================

CREATE TABLE logbook_tanda_tangan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    revisi_id BIGINT NOT NULL
        REFERENCES logbook_revisi(id) ON DELETE RESTRICT,

    tahap VARCHAR(30) NOT NULL,

    penanda_tangan_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE RESTRICT,

    keputusan VARCHAR(20) NOT NULL,

    catatan TEXT,

    ditandatangani_pada TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_tahap_check
        CHECK (
            tahap IN (
                'mahasiswa',
                'mitra',
                'dosen',
                'koordinator',
                'tendik'
            )
        ),

    CONSTRAINT logbook_keputusan_check
        CHECK (
            keputusan IN (
                'disetujui',
                'perlu_revisi',
                'ditolak'
            )
        ),

    CONSTRAINT logbook_tanda_tangan_unik
        UNIQUE (revisi_id, tahap)
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_logbook_penempatan
    ON logbook_mingguan(penempatan_id);

CREATE INDEX idx_logbook_status
    ON logbook_mingguan(status);

CREATE INDEX idx_logbook_revisi_logbook
    ON logbook_revisi(logbook_id, nomor_versi);

CREATE INDEX idx_logbook_ttd_revisi
    ON logbook_tanda_tangan(revisi_id);

CREATE INDEX idx_logbook_ttd_penanda_tangan
    ON logbook_tanda_tangan(penanda_tangan_id);


-- ============================================
-- LAPORAN MAGANG
-- ============================================

CREATE TABLE laporan_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    jenis_laporan VARCHAR(30) NOT NULL,

    judul VARCHAR(200) NOT NULL,
    versi_terkini INTEGER NOT NULL DEFAULT 1,

    status VARCHAR(30) NOT NULL DEFAULT 'draft',

    diajukan_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT laporan_jenis_check
        CHECK (
            jenis_laporan IN (
                'laporan_akhir'
            )
        ),

    CONSTRAINT laporan_versi_check
        CHECK (versi_terkini > 0),

    CONSTRAINT laporan_status_check
        CHECK (
            status IN (
                'draft',
                'diajukan',
                'diperiksa_dosen',
                'perlu_revisi',
                'menunggu_verifikasi',
                'terverifikasi',
                'ditolak'
            )
        ),

    CONSTRAINT laporan_penempatan_jenis_unik
        UNIQUE (penempatan_id, jenis_laporan)
);


-- ============================================
-- VERSI / REVISI LAPORAN
-- ============================================

CREATE TABLE laporan_revisi (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    laporan_id BIGINT NOT NULL
        REFERENCES laporan_magang(id) ON DELETE RESTRICT,

    nomor_versi INTEGER NOT NULL,

    file_path VARCHAR(500) NOT NULL,
    ringkasan TEXT,

    catatan_revisi TEXT,

    diunggah_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    diunggah_pada TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT laporan_revisi_versi_check
        CHECK (nomor_versi > 0),

    CONSTRAINT laporan_revisi_unik
        UNIQUE (laporan_id, nomor_versi)
);


-- ============================================
-- PENILAIAN MAGANG
-- ============================================

CREATE TABLE penilaian_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    penilai_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE RESTRICT,

    peran_penilai VARCHAR(20) NOT NULL,

    nilai_kedisiplinan NUMERIC(5,2),
    nilai_komunikasi NUMERIC(5,2),
    nilai_kerja_sama NUMERIC(5,2),
    nilai_tanggung_jawab NUMERIC(5,2),
    nilai_keterampilan NUMERIC(5,2),

    nilai_akhir NUMERIC(5,2),

    catatan TEXT,

    status VARCHAR(20) NOT NULL DEFAULT 'draft',

    dinilai_pada TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT penilaian_peran_check
        CHECK (
            peran_penilai IN (
                'dosen',
                'mitra'
            )
        ),

    CONSTRAINT penilaian_status_check
        CHECK (
            status IN (
                'draft',
                'diajukan',
                'disetujui',
                'perlu_revisi'
            )
        ),

    CONSTRAINT penilaian_nilai_check
        CHECK (
            (nilai_kedisiplinan IS NULL OR nilai_kedisiplinan BETWEEN 0 AND 100)
            AND (nilai_komunikasi IS NULL OR nilai_komunikasi BETWEEN 0 AND 100)
            AND (nilai_kerja_sama IS NULL OR nilai_kerja_sama BETWEEN 0 AND 100)
            AND (nilai_tanggung_jawab IS NULL OR nilai_tanggung_jawab BETWEEN 0 AND 100)
            AND (nilai_keterampilan IS NULL OR nilai_keterampilan BETWEEN 0 AND 100)
            AND (nilai_akhir IS NULL OR nilai_akhir BETWEEN 0 AND 100)
        ),

    CONSTRAINT penilaian_penilai_unik
        UNIQUE (penempatan_id, penilai_id, peran_penilai)
);


-- ============================================
-- VERIFIKASI PENYELESAIAN MAGANG
-- ============================================

CREATE TABLE verifikasi_penyelesaian_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL UNIQUE
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    diverifikasi_oleh BIGINT NOT NULL
        REFERENCES users(id) ON DELETE RESTRICT,

    status VARCHAR(20) NOT NULL DEFAULT 'menunggu',

    catatan TEXT,

    diverifikasi_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT verifikasi_penyelesaian_status_check
        CHECK (
            status IN (
                'menunggu',
                'terverifikasi',
                'perlu_perbaikan',
                'ditolak'
            )
        )
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_laporan_penempatan
    ON laporan_magang(penempatan_id);

CREATE INDEX idx_laporan_status
    ON laporan_magang(status);

CREATE INDEX idx_laporan_revisi_laporan
    ON laporan_revisi(laporan_id, nomor_versi);

CREATE INDEX idx_penilaian_penempatan
    ON penilaian_magang(penempatan_id);

CREATE INDEX idx_penilaian_penilai
    ON penilaian_magang(penilai_id);

CREATE INDEX idx_penilaian_status
    ON penilaian_magang(status);

CREATE INDEX idx_verifikasi_penyelesaian_status
    ON verifikasi_penyelesaian_magang(status);


-- ============================================
-- PORTOFOLIO MAHASISWA
-- ============================================

CREATE TABLE portofolios (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mahasiswa_id BIGINT NOT NULL
        REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,

    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,

    jenis VARCHAR(30) NOT NULL,

    deskripsi TEXT NOT NULL,
    tanggal_perolehan DATE,

    penyelenggara VARCHAR(200),
    tautan VARCHAR(500),

    gambar_sampul VARCHAR(500),

    status_verifikasi VARCHAR(30) NOT NULL
        DEFAULT 'belum_diverifikasi',

    catatan_verifikasi TEXT,

    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    diverifikasi_pada TIMESTAMPTZ,

    status_publikasi VARCHAR(20) NOT NULL
        DEFAULT 'draft',

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT portofolio_jenis_check
        CHECK (
            jenis IN (
                'proyek',
                'sertifikat',
                'prestasi',
                'pengalaman',
                'karya'
            )
        ),

    CONSTRAINT portofolio_verifikasi_check
        CHECK (
            status_verifikasi IN (
                'belum_diverifikasi',
                'terverifikasi',
                'ditolak',
                'perlu_perbaikan'
            )
        ),

    CONSTRAINT portofolio_publikasi_check
        CHECK (
            status_publikasi IN (
                'draft',
                'publik',
                'arsip'
            )
        )
);


-- ============================================
-- BERKAS PENDUKUNG PORTOFOLIO
-- ============================================

CREATE TABLE berkas_portofolio (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    portofolio_id BIGINT NOT NULL
        REFERENCES portofolios(id) ON DELETE RESTRICT,

    nama_berkas VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    tipe_mime VARCHAR(100),
    ukuran_byte BIGINT,

    diunggah_pada TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT berkas_portofolio_ukuran_check
        CHECK (ukuran_byte IS NULL OR ukuran_byte >= 0)
);


-- ============================================
-- KETERAMPILAN MAHASISWA
-- ============================================

CREATE TABLE keterampilan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    nama VARCHAR(100) NOT NULL UNIQUE,
    kategori VARCHAR(50),

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- RELASI MAHASISWA DAN KETERAMPILAN
-- ============================================

CREATE TABLE mahasiswa_keterampilan (
    mahasiswa_id BIGINT NOT NULL
        REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,

    keterampilan_id BIGINT NOT NULL
        REFERENCES keterampilan(id) ON DELETE RESTRICT,

    tingkat VARCHAR(20),
    keterangan TEXT,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (mahasiswa_id, keterampilan_id),

    CONSTRAINT mahasiswa_keterampilan_tingkat_check
        CHECK (
            tingkat IS NULL
            OR tingkat IN (
                'pemula',
                'menengah',
                'mahir'
            )
        )
);


-- ============================================
-- RIWAYAT VERIFIKASI PORTOFOLIO
-- ============================================

CREATE TABLE riwayat_verifikasi_portofolio (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    portofolio_id BIGINT NOT NULL
        REFERENCES portofolios(id) ON DELETE RESTRICT,

    status_sebelumnya VARCHAR(30),
    status_baru VARCHAR(30) NOT NULL,

    catatan TEXT,

    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    waktu_verifikasi TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT riwayat_portofolio_status_check
        CHECK (
            status_baru IN (
                'belum_diverifikasi',
                'terverifikasi',
                'ditolak',
                'perlu_perbaikan'
            )
        )
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_portofolio_mahasiswa
    ON portofolios(mahasiswa_id);

CREATE INDEX idx_portofolio_jenis
    ON portofolios(jenis);

CREATE INDEX idx_portofolio_verifikasi
    ON portofolios(status_verifikasi);

CREATE INDEX idx_portofolio_publikasi
    ON portofolios(status_publikasi);

CREATE INDEX idx_berkas_portofolio
    ON berkas_portofolio(portofolio_id);

CREATE INDEX idx_mahasiswa_keterampilan
    ON mahasiswa_keterampilan(keterampilan_id);

CREATE INDEX idx_riwayat_verifikasi_portofolio
    ON riwayat_verifikasi_portofolio(
        portofolio_id,
        waktu_verifikasi
    );


-- ============================================
-- NOTIFIKASI DALAM APLIKASI
-- ============================================

CREATE TABLE notifikasi (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    user_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE CASCADE,

    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,

    jenis VARCHAR(50) NOT NULL,

    referensi_tabel VARCHAR(100),
    referensi_id BIGINT,

    tautan VARCHAR(500),

    sudah_dibaca BOOLEAN NOT NULL DEFAULT FALSE,
    dibaca_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT notifikasi_jenis_check
        CHECK (
            jenis IN (
                'pendaftaran',
                'persetujuan',
                'logbook',
                'laporan',
                'penilaian',
                'portofolio',
                'verifikasi',
                'sistem'
            )
        ),

    CONSTRAINT notifikasi_status_baca_check
        CHECK (
            (sudah_dibaca = FALSE AND dibaca_pada IS NULL)
            OR
            (sudah_dibaca = TRUE AND dibaca_pada IS NOT NULL)
        )
);


-- ============================================
-- RIWAYAT AKTIVITAS PENGGUNA
-- ============================================

CREATE TABLE aktivitas_pengguna (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    user_id BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    aksi VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,

    modul VARCHAR(100),

    referensi_tabel VARCHAR(100),
    referensi_id BIGINT,

    ip_address INET,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_notifikasi_user_waktu
    ON notifikasi(user_id, created_at DESC);

CREATE INDEX idx_notifikasi_belum_dibaca
    ON notifikasi(user_id, created_at DESC)
    WHERE sudah_dibaca = FALSE;

CREATE INDEX idx_aktivitas_user_waktu
    ON aktivitas_pengguna(user_id, created_at DESC);

CREATE INDEX idx_aktivitas_modul_waktu
    ON aktivitas_pengguna(modul, created_at DESC);

-- ============================================
-- TABEL SERTIFIKAT
-- ============================================

CREATE TABLE IF NOT EXISTS sertifikat (
    id BIGINT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    user_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE RESTRICT,

    nama VARCHAR(200) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    jenis VARCHAR(100),
    penerbit VARCHAR(200) NOT NULL,
    nomor_sertifikat VARCHAR(150),
    tanggal_terbit DATE,
    tanggal_kedaluwarsa DATE,

    deskripsi TEXT,
    tautan TEXT,
    file_path TEXT NOT NULL,

    status_verifikasi VARCHAR(30) NOT NULL
        DEFAULT 'belum_terverifikasi',
    catatan_verifikasi TEXT,
    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE RESTRICT,
    diverifikasi_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT sertifikat_status_verifikasi_check
        CHECK (
            status_verifikasi IN (
                'belum_terverifikasi',
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        ),

    CONSTRAINT sertifikat_tanggal_check
        CHECK (
            tanggal_kedaluwarsa IS NULL
            OR tanggal_terbit IS NULL
            OR tanggal_kedaluwarsa >= tanggal_terbit
        )
);

CREATE INDEX IF NOT EXISTS idx_sertifikat_user_id
    ON sertifikat(user_id);

CREATE INDEX IF NOT EXISTS idx_sertifikat_status_verifikasi
    ON sertifikat(status_verifikasi);

CREATE INDEX IF NOT EXISTS idx_sertifikat_tanggal_terbit
    ON sertifikat(tanggal_terbit);

-- ============================================
-- TABEL MITRA
-- ============================================

CREATE TABLE mitra (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    nama VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    kategori VARCHAR(50) NOT NULL,
    bidang_industri VARCHAR(100),
    deskripsi TEXT,

    email VARCHAR(150),
    telepon VARCHAR(30),
    website VARCHAR(255),
    logo_path TEXT,

    provinsi VARCHAR(100),
    kota VARCHAR(100),
    alamat TEXT NOT NULL,

    status_verifikasi VARCHAR(30) NOT NULL DEFAULT 'dalam_peninjauan'
        CHECK (
            status_verifikasi IN (
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        ),

    catatan_verifikasi TEXT,
    diverifikasi_oleh BIGINT REFERENCES users(id) ON DELETE RESTRICT,
    diverifikasi_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_mitra_status_verifikasi
    ON mitra(status_verifikasi);

CREATE INDEX idx_mitra_kategori
    ON mitra(kategori);

CREATE INDEX idx_mitra_kota
    ON mitra(kota);

-- ============================================
-- TABEL formasi_magang
-- ============================================

CREATE TABLE public.formasi_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mitra_id BIGINT NOT NULL
        REFERENCES public.mitra(id) ON DELETE RESTRICT,

    nama_posisi VARCHAR(150) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    deskripsi TEXT NOT NULL,
    kualifikasi TEXT,

    -- Kriteria mahasiswa yang dapat mendaftar
    program_studi TEXT[] NOT NULL DEFAULT '{}',
    keahlian TEXT[] NOT NULL DEFAULT '{}',

    kuota INTEGER NOT NULL CHECK (kuota > 0),

    lokasi VARCHAR(150) NOT NULL,
    durasi VARCHAR(100) NOT NULL,
    tahun_akademik VARCHAR(20) NOT NULL,
    periode_mulai DATE,
    periode_selesai DATE,

    status VARCHAR(30) NOT NULL DEFAULT 'tersedia'
        CHECK (
            status IN (
                'tersedia',
                'ditutup',
                'selesai'
            )
        ),

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_formasi_periode
        CHECK (
            periode_mulai IS NULL
            OR periode_selesai IS NULL
            OR periode_selesai >= periode_mulai
        )
);

CREATE INDEX idx_formasi_mitra
    ON public.formasi_magang(mitra_id);

CREATE INDEX idx_formasi_status
    ON public.formasi_magang(status);

CREATE INDEX idx_formasi_tahun_akademik
    ON public.formasi_magang(tahun_akademik);

-- ============================================
-- TABEL formasi_magang
-- ============================================

CREATE TABLE IF NOT EXISTS public.dokumen_pendaftaran_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    pendaftaran_id BIGINT NOT NULL REFERENCES public.pendaftaran_magang(id) ON DELETE RESTRICT,
    jenis_dokumen VARCHAR(40) NOT NULL,
    nama_asli VARCHAR(255) NOT NULL,
    path_file VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    ukuran_bytes BIGINT NOT NULL CHECK (ukuran_bytes > 0),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT dokumen_pendaftaran_jenis_check CHECK (jenis_dokumen IN ('pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua', 'bpjs', 'sktm_kip', 'proposal', 'sertifikat_kompetensi')),
    CONSTRAINT dokumen_pendaftaran_unique_jenis UNIQUE (pendaftaran_id, jenis_dokumen)
);

CREATE INDEX IF NOT EXISTS idx_dokumen_pendaftaran_magang
    ON public.dokumen_pendaftaran_magang(pendaftaran_id);


-- VALUE CATATAN MIGRATION

INSERT INTO schema_migrations (migration)
VALUES ('001_create_users_and_profiles.sql');

INSERT INTO schema_migrations (migration)
VALUES ('002_create_mitra_and_formasi.sql');

INSERT INTO schema_migrations (migration)
VALUES ('003_create_pendaftaran_magang.sql');

INSERT INTO schema_migrations (migration)
VALUES ('004_create_penempatan_magang.sql');

INSERT INTO schema_migrations (migration)
VALUES ('005_create_logbook_magang.sql');

INSERT INTO schema_migrations (migration)
VALUES ('006_create_laporan_dan_penilaian.sql');

INSERT INTO schema_migrations (migration)
VALUES ('007_create_portofolio_mahasiswa.sql');

INSERT INTO schema_migrations (migration)
VALUES ('008_create_notifikasi_dan_aktivitas.sql');

INSERT INTO schema_migrations (migration)
VALUES ('011_create_sertifikat.sql');

INSERT INTO schema_migrations (migration)
    VALUES ('012_create_mitra.sql');

INSERT INTO schema_migrations (migration)
VALUES ('013_create_formasi_magang.sql');

INSERT INTO public.schema_migrations (migration)
VALUES ('014_create_dokumen_pendaftaran_magang.sql')
ON CONFLICT (migration) DO NOTHING;