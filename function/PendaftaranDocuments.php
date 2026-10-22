<?php

declare(strict_types=1);

function applicationDocumentKind(string $uploadField): string
{
    return match ($uploadField) {
        'bpjs' => 'bpjs_asuransi',
        'proposal' => 'proposal_magang',
        default => $uploadField,
    };
}
