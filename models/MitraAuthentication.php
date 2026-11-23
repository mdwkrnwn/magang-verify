<?php

declare(strict_types=1);

final class MitraAuthentication
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findActiveByCode(string $companyCode): ?array
    {
        $companyCode = trim($companyCode);
        if ($companyCode === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            'SELECT id, nama_perusahaan, is_active
             FROM mitra
             WHERE kode_perusahaan = :kode_perusahaan
               AND is_active = TRUE
             LIMIT 1'
        );
        $statement->execute(['kode_perusahaan' => $companyCode]);
        $mitra = $statement->fetch(PDO::FETCH_ASSOC);

        return $mitra ?: null;
    }
}
