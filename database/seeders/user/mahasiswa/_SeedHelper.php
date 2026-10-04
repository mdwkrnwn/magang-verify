<?php
declare(strict_types=1);

/**
 * Helper untuk seeder native PHP VerifyMagang.
 * Jalankan seeder dari root project:
 *   php database/seeders/01_SeedUsersMahasiswa.php
 */
require_once __DIR__ . '/../../../../config/database.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    throw new RuntimeException('Koneksi PDO tidak tersedia dari config/database.php.');
}

function seedDb(): PDO
{
    global $pdo;
    return $pdo;
}

function seedColumns(string $table): array
{
    $stmt = seedDb()->prepare("
        SELECT column_name
        FROM information_schema.columns
        WHERE table_schema = 'public' AND table_name = :table
    ");
    $stmt->execute(['table' => $table]);
    return array_fill_keys($stmt->fetchAll(PDO::FETCH_COLUMN), true);
}

function seedInsert(string $table, array $data): int
{
    global $pdo;

    $columns = array_keys($data);

    $placeholders = array_map(
        fn($column) => ':' . $column,
        $columns
    );

    $sql = sprintf(
        'INSERT INTO %s (%s) VALUES (%s) RETURNING id',
        $table,
        implode(', ', $columns),
        implode(', ', $placeholders)
    );

    $stmt = $pdo->prepare($sql);

    foreach ($data as $column => $value) {
        if (is_bool($value)) {
            $stmt->bindValue(
                ':' . $column,
                $value,
                PDO::PARAM_BOOL
            );
        } elseif ($value === null) {
            $stmt->bindValue(
                ':' . $column,
                null,
                PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':' . $column,
                $value,
                PDO::PARAM_STR
            );
        }
    }

    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

function seedUserId(string $loginId): int
{
    $stmt = seedDb()->prepare('SELECT id FROM users WHERE login_id = :login_id LIMIT 1');
    $stmt->execute(['login_id' => $loginId]);
    $id = $stmt->fetchColumn();

    if ($id === false) {
        throw new RuntimeException("User {$loginId} belum ada. Jalankan seeder user terlebih dahulu.");
    }

    return (int) $id;
}

function seedMahasiswaId(string $nim): int
{
    $stmt = seedDb()->prepare('SELECT id FROM profil_mahasiswa WHERE nim = :nim LIMIT 1');
    $stmt->execute(['nim' => $nim]);
    $id = $stmt->fetchColumn();

    if ($id === false) {
        throw new RuntimeException("Mahasiswa NIM {$nim} belum ada. Jalankan seeder profil terlebih dahulu.");
    }

    return (int) $id;
}

function seedSlug(string $value): string
{
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'seed-data';
}

function seedExists(string $sql, array $params = []): bool
{
    $stmt = seedDb()->prepare($sql);
    $stmt->execute($params);
    return (bool) $stmt->fetchColumn();
}

function seedLog(string $message): void
{
    echo "[SEED] {$message}" . PHP_EOL;
}
?>
