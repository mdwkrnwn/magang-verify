<?php

declare(strict_types=1);

final class PengajuanDecision
{
    public static function normalizeNote(string $decision, ?string $note): ?string
    {
        if (!in_array($decision, ['disetujui', 'ditolak'], true)) {
            throw new InvalidArgumentException('Keputusan persetujuan dosen tidak valid.');
        }

        $note = trim($note ?? '');
        if ($decision === 'ditolak' && $note === '') {
            throw new InvalidArgumentException('Alasan penolakan wajib diisi.');
        }
        if (mb_strlen($note) > 5000) {
            throw new InvalidArgumentException('Catatan dosen maksimal 5.000 karakter.');
        }

        return $note === '' ? null : $note;
    }

    public static function bindUpdate(
        PDOStatement $statement,
        string $decision,
        int $dosenId,
        ?string $note,
        string $nextStatus,
        int $applicationId
    ): void {
        $note = self::normalizeNote($decision, $note);

        $statement->bindValue(':decision', $decision, PDO::PARAM_STR);
        $statement->bindValue(':dosen_id', $dosenId, PDO::PARAM_INT);
        $statement->bindValue(':note', $note, $note === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':next_status', $nextStatus, PDO::PARAM_STR);
        $statement->bindValue(':terminal', $decision === 'ditolak', PDO::PARAM_BOOL);
        $statement->bindValue(':id', $applicationId, PDO::PARAM_INT);
    }
}
