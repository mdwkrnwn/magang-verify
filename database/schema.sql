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
    cv_path VARCHAR(500),
    cv_nama_asli VARCHAR(255),
    cv_mime_type VARCHAR(100),
    cv_ukuran_bytes BIGINT,
    cv_updated_at TIMESTAMPTZ,
    github_url VARCHAR(500),
    linkedin_url VARCHAR(500),
    portfolio_url VARCHAR(500),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT profil_mahasiswa_cv_ukuran_check
        CHECK (
            cv_ukuran_bytes IS NULL
            OR (
                cv_ukuran_bytes > 0
                AND cv_ukuran_bytes <= 5242880
            )
        )
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
    CONSTRAINT dokumen_pendaftaran_magang_jenis_dokumen_check CHECK (jenis_dokumen IN ('pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua', 'bpjs_asuransi', 'sktm_kip', 'proposal_magang', 'sertifikat_kompetensi')),
    CONSTRAINT dokumen_pendaftaran_unique_jenis UNIQUE (pendaftaran_id, jenis_dokumen)
);

CREATE INDEX IF NOT EXISTS idx_dokumen_pendaftaran_magang
    ON public.dokumen_pendaftaran_magang(pendaftaran_id);


-- ============================================
-- PENGALAMAN MAHASISWA
-- ============================================

CREATE TABLE IF NOT EXISTS public.pengalaman (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    mahasiswa_id BIGINT NOT NULL REFERENCES public.profil_mahasiswa(id) ON DELETE RESTRICT,
    pendaftaran_id BIGINT UNIQUE REFERENCES public.pendaftaran_magang(id) ON DELETE SET NULL,
    jenis VARCHAR(30) NOT NULL DEFAULT 'lainnya',
    posisi VARCHAR(200) NOT NULL,
    instansi VARCHAR(200) NOT NULL,
    lokasi VARCHAR(200),
    deskripsi TEXT,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    status_publikasi VARCHAR(20) NOT NULL DEFAULT 'publik',
    is_otomatis BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pengalaman_jenis_check CHECK (jenis IN ('magang','pekerjaan','organisasi','freelance','proyek','lainnya')),
    CONSTRAINT pengalaman_publikasi_check CHECK (status_publikasi IN ('draft','publik','arsip')),
    CONSTRAINT pengalaman_tanggal_check CHECK (tanggal_selesai IS NULL OR tanggal_mulai IS NULL OR tanggal_selesai >= tanggal_mulai)
);

CREATE INDEX IF NOT EXISTS idx_pengalaman_mahasiswa ON public.pengalaman(mahasiswa_id);
CREATE INDEX IF NOT EXISTS idx_pengalaman_publikasi ON public.pengalaman(status_publikasi);


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
INSERT INTO public.schema_migrations (migration)
VALUES ('015_expand_jenis_dokumen_pendaftaran.sql')
ON CONFLICT (migration) DO NOTHING;

INSERT INTO public.schema_migrations (migration)
VALUES ('016_add_constraint_dokumen_pendaftaran_jenis_check.sql')
ON CONFLICT (migration) DO NOTHING;

INSERT INTO public.schema_migrations (migration)
VALUES ('017_add_cv_to_profil_mahasiswa.sql')
ON CONFLICT (migration) DO NOTHING;

INSERT INTO public.schema_migrations (migration)
VALUES ('018_add_social_links_and_pengalaman.sql')
ON CONFLICT (migration) DO NOTHING;

-- ============================================================
-- MIGRATION 019: OPTIMASI DASHBOARD MAHASISWA
-- ============================================================
CREATE INDEX IF NOT EXISTS idx_portofolios_mahasiswa_created
    ON public.portofolios(mahasiswa_id, created_at DESC);
CREATE INDEX IF NOT EXISTS idx_portofolios_mahasiswa_status
    ON public.portofolios(mahasiswa_id, status_publikasi, status_verifikasi);
CREATE INDEX IF NOT EXISTS idx_sertifikat_user_created
    ON public.sertifikat(user_id, created_at DESC);
CREATE INDEX IF NOT EXISTS idx_pengalaman_mahasiswa_created
    ON public.pengalaman(mahasiswa_id, created_at DESC);
CREATE INDEX IF NOT EXISTS idx_logbook_penempatan_created
    ON public.logbook_mingguan(penempatan_id, created_at DESC);
INSERT INTO public.schema_migrations (migration)
VALUES ('019_optimize_dashboard_mahasiswa.sql')
ON CONFLICT (migration) DO NOTHING;

-- ============================================================
-- MIGRATION 020: LOGBOOK V2
-- ============================================================
ALTER TABLE public.logbook_revisi
    ADD COLUMN IF NOT EXISTS snapshot_data JSONB,
    ADD COLUMN IF NOT EXISTS snapshot_hash CHAR(64);

CREATE TABLE IF NOT EXISTS public.logbook_harian (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    logbook_id BIGINT NOT NULL REFERENCES public.logbook_mingguan(id) ON DELETE RESTRICT,
    tanggal DATE NOT NULL,
    jam_masuk TIME,
    jam_pulang TIME,
    kegiatan TEXT NOT NULL,
    status_kehadiran VARCHAR(20) NOT NULL DEFAULT 'hadir',
    alasan_ketidakhadiran TEXT,
    bukti_path VARCHAR(500),
    status_validasi VARCHAR(20) NOT NULL DEFAULT 'tidak_perlu',
    catatan_validasi TEXT,
    divalidasi_oleh BIGINT REFERENCES public.users(id) ON DELETE SET NULL,
    divalidasi_pada TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT logbook_harian_unik UNIQUE (logbook_id, tanggal),
    CONSTRAINT logbook_harian_kehadiran_check CHECK (status_kehadiran IN ('hadir', 'tidak_hadir')),
    CONSTRAINT logbook_harian_validasi_check CHECK (status_validasi IN ('tidak_perlu', 'menunggu', 'disetujui', 'ditolak')),
    CONSTRAINT logbook_harian_alasan_check CHECK (status_kehadiran = 'hadir' OR NULLIF(BTRIM(alasan_ketidakhadiran), '') IS NOT NULL),
    CONSTRAINT logbook_harian_validasi_consistency_check CHECK ((status_kehadiran = 'hadir' AND status_validasi = 'tidak_perlu') OR status_kehadiran = 'tidak_hadir'),
    CONSTRAINT logbook_harian_jam_check CHECK (jam_pulang IS NULL OR jam_masuk IS NULL OR jam_pulang >= jam_masuk)
);
CREATE INDEX IF NOT EXISTS idx_logbook_harian_logbook_tanggal
    ON public.logbook_harian(logbook_id, tanggal);
CREATE INDEX IF NOT EXISTS idx_logbook_harian_validasi
    ON public.logbook_harian(status_validasi);
CREATE INDEX IF NOT EXISTS idx_logbook_harian_validasi_oleh
    ON public.logbook_harian(divalidasi_oleh);

ALTER TABLE public.logbook_tanda_tangan
    ADD COLUMN IF NOT EXISTS signature_path VARCHAR(500),
    ADD COLUMN IF NOT EXISTS signature_hash CHAR(64),
    ADD COLUMN IF NOT EXISTS snapshot_hash CHAR(64),
    ADD COLUMN IF NOT EXISTS metadata JSONB;
CREATE INDEX IF NOT EXISTS idx_logbook_ttd_tahap_snapshot
    ON public.logbook_tanda_tangan(revisi_id, tahap, snapshot_hash);
INSERT INTO public.schema_migrations (migration)
VALUES ('020_rebuild_logbook_workflow.sql')
ON CONFLICT (migration) DO NOTHING;
-- Migration 021 dan 022 berisi trigger hardening dan dijalankan secara incremental.


-- ============================================================
-- MIGRATION 021: LOGBOOK V2 - HARDENING
-- ============================================================
-- ============================================================
-- LOGBOOK V2 - HARDENING
-- Menjaga aturan bisnis penting di level database sehingga
-- request langsung/di luar UI tidak dapat melewati workflow.
-- ============================================================

CREATE OR REPLACE FUNCTION public.enforce_logbook_harian_rules()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_mulai DATE;
    v_selesai DATE;
    v_status VARCHAR(30);
BEGIN
    SELECT tanggal_mulai, tanggal_selesai, status
      INTO v_mulai, v_selesai, v_status
      FROM public.logbook_mingguan
     WHERE id = NEW.logbook_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Minggu logbook tidak ditemukan.';
    END IF;

    IF NEW.tanggal < v_mulai OR NEW.tanggal > v_selesai THEN
        RAISE EXCEPTION 'Tanggal logbook harian harus berada di dalam periode minggu.';
    END IF;

    IF NEW.tanggal > CURRENT_DATE THEN
        RAISE EXCEPTION 'Logbook harian tidak boleh dibuat untuk tanggal masa depan.';
    END IF;

    IF v_status IN ('menunggu_dosen', 'disetujui') THEN
        RAISE EXCEPTION 'Logbook terkunci karena mitra sudah menandatangani.';
    END IF;

    RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_logbook_harian_rules
    ON public.logbook_harian;

CREATE TRIGGER trg_logbook_harian_rules
BEFORE INSERT OR UPDATE ON public.logbook_harian
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_harian_rules();


CREATE OR REPLACE FUNCTION public.enforce_logbook_signature_workflow()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_status VARCHAR(30);
    v_current_version INTEGER;
    v_revision_version INTEGER;
    v_mahasiswa_user_id BIGINT;
    v_mitra_user_id BIGINT;
    v_dosen_user_id BIGINT;
BEGIN
    SELECT
        lm.status,
        lm.versi_terkini,
        lr.nomor_versi,
        prof.user_id,
        m.user_id,
        pd.user_id
      INTO
        v_status,
        v_current_version,
        v_revision_version,
        v_mahasiswa_user_id,
        v_mitra_user_id,
        v_dosen_user_id
      FROM public.logbook_revisi lr
      INNER JOIN public.logbook_mingguan lm
              ON lm.id = lr.logbook_id
      INNER JOIN public.penempatan_magang pm
              ON pm.id = lm.penempatan_id
      INNER JOIN public.pendaftaran_magang p
              ON p.id = pm.pendaftaran_id
      INNER JOIN public.profil_mahasiswa prof
              ON prof.id = p.mahasiswa_id
      INNER JOIN public.formasi_magang f
              ON f.id = p.formasi_id
      INNER JOIN public.mitra m
              ON m.id = f.mitra_id
      LEFT JOIN public.profil_dosen pd
             ON pd.id = pm.dosen_pembimbing_id
     WHERE lr.id = NEW.revisi_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Versi logbook untuk tanda tangan tidak ditemukan.';
    END IF;

    IF v_revision_version <> v_current_version THEN
        RAISE EXCEPTION 'Tanda tangan harus diberikan pada versi logbook terbaru.';
    END IF;

    IF NEW.tahap = 'mahasiswa' THEN
        IF NEW.penanda_tangan_id <> v_mahasiswa_user_id THEN
            RAISE EXCEPTION 'Penanda tangan mahasiswa tidak sesuai dengan pemilik logbook.';
        END IF;
        IF v_status NOT IN ('draft', 'perlu_revisi') THEN
            RAISE EXCEPTION 'Logbook belum berada pada tahap tanda tangan mahasiswa.';
        END IF;

    ELSIF NEW.tahap = 'mitra' THEN
        IF NEW.penanda_tangan_id <> v_mitra_user_id THEN
            RAISE EXCEPTION 'Penanda tangan mitra tidak sesuai dengan mitra penempatan.';
        END IF;
        IF v_status <> 'menunggu_mitra' THEN
            RAISE EXCEPTION 'Logbook belum berada pada tahap tanda tangan mitra.';
        END IF;

    ELSIF NEW.tahap = 'dosen' THEN
        IF v_dosen_user_id IS NULL OR NEW.penanda_tangan_id <> v_dosen_user_id THEN
            RAISE EXCEPTION 'Penanda tangan dosen tidak sesuai dengan dosen pembimbing penempatan.';
        END IF;
        IF v_status <> 'menunggu_dosen' THEN
            RAISE EXCEPTION 'Logbook belum berada pada tahap tanda tangan dosen.';
        END IF;
    END IF;

    RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_logbook_signature_workflow
    ON public.logbook_tanda_tangan;

CREATE TRIGGER trg_logbook_signature_workflow
BEFORE INSERT ON public.logbook_tanda_tangan
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_signature_workflow();


-- Tanda tangan adalah bukti audit. Setelah dibuat, record tidak boleh
-- diubah atau dihapus dari workflow aplikasi.
CREATE OR REPLACE FUNCTION public.prevent_logbook_signature_mutation()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    RAISE EXCEPTION 'Riwayat tanda tangan logbook tidak boleh diubah atau dihapus.';
END;
$$;

DROP TRIGGER IF EXISTS trg_logbook_signature_immutable_update
    ON public.logbook_tanda_tangan;

CREATE TRIGGER trg_logbook_signature_immutable_update
BEFORE UPDATE OR DELETE ON public.logbook_tanda_tangan
FOR EACH ROW
EXECUTE FUNCTION public.prevent_logbook_signature_mutation();


INSERT INTO public.schema_migrations (migration)
VALUES ('021_harden_logbook_workflow.sql')
ON CONFLICT (migration) DO NOTHING;


-- ============================================================
-- MIGRATION 022: LOGBOOK V2 - STRUCTURE HARDENING
-- ============================================================
-- ============================================================
-- LOGBOOK V2 - STRUCTURE HARDENING
-- Menjaga periode minggu dan histori versi agar tidak dapat
-- diubah lewat request/database yang melewati controller.
-- ============================================================

CREATE OR REPLACE FUNCTION public.enforce_logbook_week_schedule()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_penempatan_mulai DATE;
    v_penempatan_selesai DATE;
    v_expected_week SMALLINT;
    v_expected_start DATE;
    v_expected_end DATE;
    v_previous_end DATE;
    v_has_previous BOOLEAN;
BEGIN
    SELECT tanggal_mulai, tanggal_selesai
      INTO v_penempatan_mulai, v_penempatan_selesai
      FROM public.penempatan_magang
     WHERE id = NEW.penempatan_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Penempatan magang tidak ditemukan.';
    END IF;

    SELECT EXISTS (
        SELECT 1
        FROM public.logbook_mingguan
        WHERE penempatan_id = NEW.penempatan_id
          AND id <> COALESCE(NEW.id, 0)
    ) INTO v_has_previous;

    IF v_has_previous THEN
        SELECT minggu_ke, tanggal_selesai
          INTO v_expected_week, v_previous_end
          FROM public.logbook_mingguan
         WHERE penempatan_id = NEW.penempatan_id
           AND id <> COALESCE(NEW.id, 0)
         ORDER BY minggu_ke DESC
         LIMIT 1;

        v_expected_week := v_expected_week + 1;
        v_expected_start := v_previous_end + 1;
        v_expected_end := LEAST(v_expected_start + 6, v_penempatan_selesai);
    ELSE
        v_expected_week := 1;
        v_expected_start := v_penempatan_mulai;
        v_expected_end := LEAST(
            v_expected_start + ((7 - EXTRACT(DOW FROM v_expected_start)::INTEGER) % 7),
            v_penempatan_selesai
        );
    END IF;

    IF TG_OP = 'INSERT' THEN
        IF NEW.minggu_ke <> v_expected_week
           OR NEW.tanggal_mulai <> v_expected_start
           OR NEW.tanggal_selesai <> v_expected_end THEN
            RAISE EXCEPTION
                'Periode Minggu % tidak sesuai dengan jadwal penempatan. Periode yang benar: % sampai %.',
                v_expected_week, v_expected_start, v_expected_end;
        END IF;

        IF NEW.tanggal_mulai > CURRENT_DATE THEN
            RAISE EXCEPTION 'Minggu logbook masa depan tidak dapat dibuat.';
        END IF;
    ELSE
        IF NEW.penempatan_id <> OLD.penempatan_id
           OR NEW.minggu_ke <> OLD.minggu_ke
           OR NEW.tanggal_mulai <> OLD.tanggal_mulai
           OR NEW.tanggal_selesai <> OLD.tanggal_selesai THEN
            RAISE EXCEPTION 'Identitas dan periode minggu logbook tidak dapat diubah.';
        END IF;
    END IF;

    RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_logbook_week_schedule
    ON public.logbook_mingguan;

CREATE TRIGGER trg_logbook_week_schedule
BEFORE INSERT OR UPDATE ON public.logbook_mingguan
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_week_schedule();


-- Tidak ada fitur hapus harian pada workflow aplikasi. Karena itu,
-- hapus langsung ke database juga tidak boleh digunakan untuk
-- mengubah dokumen yang sudah disahkan mitra.
CREATE OR REPLACE FUNCTION public.prevent_logbook_harian_delete_after_partner_signature()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_status VARCHAR(30);
BEGIN
    SELECT status
      INTO v_status
      FROM public.logbook_mingguan
     WHERE id = OLD.logbook_id;

    IF v_status IN ('menunggu_dosen', 'disetujui') THEN
        RAISE EXCEPTION 'Logbook harian terkunci karena mitra sudah menandatangani.';
    END IF;

    RETURN OLD;
END;
$$;

DROP TRIGGER IF EXISTS trg_logbook_harian_delete_lock
    ON public.logbook_harian;

CREATE TRIGGER trg_logbook_harian_delete_lock
BEFORE DELETE ON public.logbook_harian
FOR EACH ROW
EXECUTE FUNCTION public.prevent_logbook_harian_delete_after_partner_signature();


-- Snapshot yang sudah ditandatangani adalah bagian dari bukti audit.
-- Snapshot tidak boleh diubah/hapus setelah memiliki tanda tangan.
CREATE OR REPLACE FUNCTION public.prevent_signed_logbook_revision_mutation()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM public.logbook_tanda_tangan
        WHERE revisi_id = OLD.id
    ) THEN
        RAISE EXCEPTION 'Versi logbook yang sudah ditandatangani tidak boleh diubah atau dihapus.';
    END IF;

    IF TG_OP = 'DELETE' THEN
        RETURN OLD;
    END IF;

    RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_signed_logbook_revision_mutation
    ON public.logbook_revisi;

CREATE TRIGGER trg_signed_logbook_revision_mutation
BEFORE UPDATE OR DELETE ON public.logbook_revisi
FOR EACH ROW
EXECUTE FUNCTION public.prevent_signed_logbook_revision_mutation();


INSERT INTO public.schema_migrations (migration)
VALUES ('022_harden_logbook_structure.sql')
ON CONFLICT (migration) DO NOTHING;
