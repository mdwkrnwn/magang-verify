BEGIN;

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
    foto_path VARCHAR(500),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

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

INSERT INTO schema_migrations (migration)
VALUES ('001_create_users_and_profiles.sql');

COMMIT;