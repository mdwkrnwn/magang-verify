<?php

declare(strict_types=1);

$files = [
    'migration' => file_get_contents(__DIR__ . '/../database/migrations/031_create_proses_magang_and_penugasan.sql'),
    'fixture' => file_get_contents(__DIR__ . '/../database/testing/031_fixture_pre_migration.sql'),
    'assertions' => file_get_contents(__DIR__ . '/../database/testing/031_post_migration_assertions.sql'),
    'usersAndProfiles' => file_get_contents(__DIR__ . '/../database/migrations/001_create_users_and_profiles.sql'),
    'applications' => file_get_contents(__DIR__ . '/../database/migrations/003_create_pendaftaran_magang.sql'),
    'placements' => file_get_contents(__DIR__ . '/../database/migrations/004_create_penempatan_magang.sql'),
];

foreach ($files as $name => $source) {
    if ($source === false) throw new RuntimeException("Unable to read {$name} source.");
}

function assertContains(string $source, string $needle, string $label): void
{
    if (!str_contains($source, $needle)) throw new RuntimeException("{$label}: missing {$needle}");
}

assertContains($files['migration'], 'BEGIN;', 'migration opens a transaction');
assertContains($files['migration'], 'COMMIT;', 'migration closes a transaction');
assertContains($files['migration'], 'ON DELETE RESTRICT', 'new FKs do not cascade-delete history');
assertContains($files['migration'], 'proses_magang_status_check', 'process status check exists');
assertContains($files['migration'], 'penugasan_pembimbing_status_check', 'assignment status check exists');
assertContains($files['migration'], 'uq_proses_magang_mahasiswa_aktif', 'one active process index exists');
assertContains($files['migration'], 'uq_penugasan_pembimbing_proses_aktif', 'one active assignment index exists');
assertContains($files['migration'], 'dosen_pembimbing_snapshot_id BIGINT REFERENCES profil_dosen(id)', 'snapshot FK matches lecturer profile type');
assertContains($files['fixture'], 'RUN ONLY inside a newly created, approved disposable PostgreSQL database', 'fixture is review-only');
assertContains($files['assertions'], 'ambiguous students received assignments', 'ambiguous backfill is asserted');
assertContains($files['assertions'], 'application snapshot changed after replacement', 'snapshot preservation is asserted');
assertContains($files['migration'], 'INSERT INTO proses_magang (mahasiswa_id, status)', 'process backfill exists');
assertContains($files['migration'], 'INSERT INTO penugasan_pembimbing', 'assignment backfill exists');

// The fixture is intentionally synthetic and must not contain project account names.
foreach (['Citra', 'magang_verify', 'Supabase', 'DB_PASSWORD'] as $forbidden) {
    if (stripos($files['fixture'], $forbidden) !== false) {
        throw new RuntimeException("fixture contains forbidden project/credential marker: {$forbidden}");
    }
}

fwrite(STDOUT, "Migration 031 fixture and procedure static checks passed.\n");
