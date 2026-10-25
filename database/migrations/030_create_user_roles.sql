BEGIN;

-- User roles tambahan di luar users.role.
-- users.role dipertahankan sebagai role utama demi kompatibilitas.

CREATE TABLE user_roles (
    user_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE CASCADE,
    role VARCHAR(30) NOT NULL,
    ditetapkan_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,
    tanggal_penetapan TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT user_roles_pkey PRIMARY KEY (user_id, role),
    CONSTRAINT user_roles_role_check CHECK (
        role IN (
            'mahasiswa',
            'dosen',
            'koordinator_magang',
            'tendik',
            'mitra'
        )
    )
);

CREATE INDEX IF NOT EXISTS idx_user_roles_user
    ON user_roles(user_id);

CREATE INDEX IF NOT EXISTS idx_user_roles_role
    ON user_roles(role);

-- Rekam role utama yang sudah ada agar akun lama tetap memiliki
-- relasi yang konsisten dengan nilai users.role.
INSERT INTO user_roles (user_id, role)
SELECT id, role
FROM users
WHERE role IS NOT NULL
ON CONFLICT (user_id, role) DO NOTHING;

INSERT INTO public.schema_migrations (migration)
VALUES ('030_create_user_roles.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
