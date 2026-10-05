BEGIN;

CREATE TABLE schema_migrations (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO schema_migrations (migration)
VALUES ('000_create_schema_migrations.sql');

COMMIT;