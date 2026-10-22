<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/PengajuanDecision.php';

function expectSameValue(mixed $expected, mixed $actual, string $case): void
{
    if ($expected !== $actual) {
        throw new RuntimeException('Pengajuan decision regression failed: ' . $case);
    }
}

function expectInvalidNote(string $decision, ?string $note, string $case): void
{
    try {
        PengajuanDecision::normalizeNote($decision, $note);
    } catch (InvalidArgumentException) {
        return;
    }
    throw new RuntimeException('Expected invalid note rejection: ' . $case);
}

final class DecisionBindingStatement extends PDOStatement
{
    public array $bindings = [];

    public function __construct()
    {
    }

    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR): bool
    {
        $this->bindings[(string) $param] = ['value' => $value, 'type' => $type];
        return true;
    }
}

expectSameValue(null, PengajuanDecision::normalizeNote('disetujui', null), 'approval allows absent note');
expectSameValue('Catatan', PengajuanDecision::normalizeNote('disetujui', ' Catatan '), 'approval accepts a note');
expectInvalidNote('ditolak', null, 'rejection requires a note');
expectInvalidNote('ditolak', '   ', 'rejection requires non-whitespace note');
expectSameValue('Alasan jelas', PengajuanDecision::normalizeNote('ditolak', ' Alasan jelas '), 'rejection accepts a trimmed reason');
expectInvalidNote('ditolak', str_repeat('a', 5001), 'note limit is enforced');

$statement = new DecisionBindingStatement();
PengajuanDecision::bindUpdate(
    $statement,
    decision: 'disetujui',
    dosenId: 17,
    note: null,
    nextStatus: 'menunggu_respons_mitra',
    applicationId: 29
);
expectSameValue(PDO::PARAM_BOOL, $statement->bindings[':terminal']['type'], 'terminal uses a boolean PDO binding');
expectSameValue(false, $statement->bindings[':terminal']['value'], 'approval binds terminal false rather than empty text');
expectSameValue(PDO::PARAM_NULL, $statement->bindings[':note']['type'], 'empty approval note is stored as SQL NULL');
expectSameValue(29, $statement->bindings[':id']['value'], 'application id remains bound to its own placeholder');

$statement = new DecisionBindingStatement();
PengajuanDecision::bindUpdate(
    $statement,
    decision: 'ditolak',
    dosenId: 17,
    note: 'Alasan jelas',
    nextStatus: 'ditolak_dosen',
    applicationId: 29
);
expectSameValue(true, $statement->bindings[':terminal']['value'], 'rejection binds terminal true');
expectSameValue(PDO::PARAM_STR, $statement->bindings[':note']['type'], 'non-empty note uses string PDO binding');

fwrite(STDOUT, "Isolated Dosen decision validation and PDO binding tests passed.\n");
