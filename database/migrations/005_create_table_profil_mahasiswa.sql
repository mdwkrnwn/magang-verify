CREATE TABLE IF NOT EXISTS profil_mahasiswa (
    id BIGSERIAL PRIMARY KEY,

    user_id BIGINT NOT NULL UNIQUE,

    jenis_kelamin VARCHAR(20),
    tanggal_lahir DATE,
    alamat TEXT,

    no_hp VARCHAR(20),
    instagram VARCHAR(100),

    program_studi VARCHAR(100),
    semester VARCHAR(50),

    bio TEXT,
    foto_profil VARCHAR(255),

    keahlian TEXT[] NOT NULL DEFAULT '{}',

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT profil_mahasiswa_user_fk
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT profil_mahasiswa_jenis_kelamin_check
        CHECK (
            jenis_kelamin IS NULL
            OR jenis_kelamin IN ('Laki-laki', 'Perempuan')
        )
);