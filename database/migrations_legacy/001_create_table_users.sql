
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    login_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    password VARCHAR(255),
    role VARCHAR(30) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

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