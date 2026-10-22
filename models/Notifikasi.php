<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Notifikasi
{
    public function getForUser(int $userId, int $limit = 50): array
    {
        if ($userId < 1) {
            throw new InvalidArgumentException('ID pengguna tidak valid.');
        }
        $limit = max(1, min($limit, 100));

        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT id, judul, pesan, jenis, tautan, sudah_dibaca, created_at
             FROM notifikasi
             WHERE user_id = :user_id
             ORDER BY created_at DESC, id DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnread(int $userId): int
    {
        if ($userId < 1) {
            throw new InvalidArgumentException('ID pengguna tidak valid.');
        }
        global $pdo;
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM notifikasi WHERE user_id = :user_id AND sudah_dibaca = FALSE');
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    public function markRead(int $userId, int $notificationId): bool
    {
        if ($userId < 1 || $notificationId < 1) {
            throw new InvalidArgumentException('Notifikasi tidak valid.');
        }
        global $pdo;
        $stmt = $pdo->prepare(
            'UPDATE notifikasi
             SET sudah_dibaca = TRUE, dibaca_pada = CURRENT_TIMESTAMP
             WHERE id = :id AND user_id = :user_id AND sudah_dibaca = FALSE'
        );
        $stmt->execute(['id' => $notificationId, 'user_id' => $userId]);
        return $stmt->rowCount() === 1;
    }

    public function markAllRead(int $userId): int
    {
        if ($userId < 1) {
            throw new InvalidArgumentException('ID pengguna tidak valid.');
        }
        global $pdo;
        $stmt = $pdo->prepare(
            'UPDATE notifikasi
             SET sudah_dibaca = TRUE, dibaca_pada = CURRENT_TIMESTAMP
             WHERE user_id = :user_id AND sudah_dibaca = FALSE'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->rowCount();
    }
}
