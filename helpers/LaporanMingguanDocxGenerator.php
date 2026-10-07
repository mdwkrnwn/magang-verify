<?php

declare(strict_types=1);

class LaporanMingguanDocxGenerator
{
    /**
     * Membuat DOCX baru dari template laporan mingguan
     * dan mengisi tabel kegiatan berdasarkan Logbook.
     *
     * @param string $templatePath Path template DOCX master
     * @param string $outputPath   Path DOCX hasil generate
     * @param array  $activities   Data kegiatan Logbook
     * @param array  $data         Data laporan
     */
    public static function generate(
        string $templatePath,
        string $outputPath,
        array $activities,
        array $data = []
    ): void {
        if (!class_exists('ZipArchive')) {
            throw new RuntimeException(
                'PHP ZipArchive belum tersedia. Aktifkan extension PHP ZIP terlebih dahulu.'
            );
        }

        if (!is_file($templatePath)) {
            throw new RuntimeException(
                'Template laporan mingguan tidak ditemukan.'
            );
        }

        $outputDirectory = dirname($outputPath);

        if (!is_dir($outputDirectory)) {
            if (!mkdir($outputDirectory, 0775, true) && !is_dir($outputDirectory)) {
                throw new RuntimeException(
                    'Folder output laporan tidak dapat dibuat.'
                );
            }
        }

        if (!copy($templatePath, $outputPath)) {
            throw new RuntimeException(
                'Template laporan gagal disalin.'
            );
        }

        $zip = new ZipArchive();

        if ($zip->open($outputPath) !== true) {
            throw new RuntimeException(
                'File DOCX hasil tidak dapat dibuka.'
            );
        }

        $documentXml = $zip->getFromName('word/document.xml');

        if ($documentXml === false) {
            $zip->close();

            throw new RuntimeException(
                'Struktur document.xml pada template tidak ditemukan.'
            );
        }

        /*
         * ------------------------------------------------------------
         * Isi informasi laporan
         * ------------------------------------------------------------
         */

        $week = (int) ($data['minggu_ke'] ?? 0);

        $documentXml = self::replaceText(
            $documentXml,
            'MINGGU KE- ______',
            'MINGGU KE- ' . $week
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Nama Mahasiswa :',
            (string) ($data['nama_mahasiswa'] ?? '')
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'NIM              :',
            (string) ($data['nim'] ?? '')
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Program Studi    :',
            (string) ($data['program_studi'] ?? '')
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Tempat Magang    :',
            (string) ($data['tempat_magang'] ?? '')
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Unit / Bagian    :',
            (string) ($data['unit_bagian'] ?? '')
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Dosen Pembimbing :',
            (string) ($data['dosen_pembimbing'] ?? '')
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Periode Magang   :',
            (string) ($data['periode_magang'] ?? '')
        );

        /*
         * ------------------------------------------------------------
         * Isi informasi laporan kedua
         * ------------------------------------------------------------
         */

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Minggu Ke-       :',
            (string) $week
        );

        $documentXml = self::replaceLabelValue(
            $documentXml,
            'Periode          :',
            (string) ($data['periode_minggu'] ?? '')
        );

        /*
         * ------------------------------------------------------------
         * Isi tabel kegiatan
         * ------------------------------------------------------------
         */

        $documentXml = self::fillActivityTable(
            $documentXml,
            $activities
        );

        /*
         * ------------------------------------------------------------
         * Simpan document.xml
         * ------------------------------------------------------------
         */

        if (!$zip->deleteName('word/document.xml')) {
            $zip->close();

            throw new RuntimeException(
                'document.xml lama tidak dapat diperbarui.'
            );
        }

        if (!$zip->addFromString('word/document.xml', $documentXml)) {
            $zip->close();

            throw new RuntimeException(
                'document.xml hasil tidak dapat ditulis.'
            );
        }

        $zip->close();
    }

    private static function replaceText(
        string $xml,
        string $search,
        string $replacement
    ): string {
        return str_replace(
            self::escapeXml($search),
            self::escapeXml($replacement),
            $xml
        );
    }

    private static function replaceLabelValue(
        string $xml,
        string $label,
        string $value
    ): string {
        $labelXml = self::escapeXml($label);

        $pattern = '/'
            . preg_quote($labelXml, '/')
            . '(<\/w:t>.*?)'
            . '(<w:r\/>|<w:r>.*?<\/w:r>)'
            . '/s';

        $replacementXml =
            $labelXml .
            '$1' .
            '<w:r>'
            . '<w:rPr>'
            . '<w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>'
            . '<w:sz w:val="22"/>'
            . '</w:rPr>'
            . '<w:t xml:space="preserve">'
            . self::escapeXml(' ' . $value)
            . '</w:t>'
            . '</w:r>';

        return preg_replace(
            $pattern,
            $replacementXml,
            $xml,
            1
        ) ?? $xml;
    }

    private static function fillActivityTable(
    string $xml,
    array $activities
): string {
    if (!preg_match(
        '/<w:tbl>.*?<\/w:tbl>/s',
        $xml,
        $tableMatch,
        PREG_OFFSET_CAPTURE
    )) {
        throw new RuntimeException(
            'Tabel kegiatan pada template tidak ditemukan.'
        );
    }

    $tableXml = $tableMatch[0][0];

    preg_match_all(
        '/<w:tr>.*?<\/w:tr>/s',
        $tableXml,
        $rowMatches
    );

    $rows = $rowMatches[0];

    if (count($rows) < 2) {
        throw new RuntimeException(
            'Struktur tabel kegiatan tidak sesuai template.'
        );
    }

    preg_match(
        '/<w:tblPr>.*?<\/w:tblPr>/s',
        $tableXml,
        $tblPr
    );

    preg_match(
        '/<w:tblGrid>.*?<\/w:tblGrid>/s',
        $tableXml,
        $tblGrid
    );

    $headerRow = $rows[0];
    $templateRow = $rows[1];

    $newTable =
        '<w:tbl>'
        . ($tblPr[0] ?? '')
        . ($tblGrid[0] ?? '')
        . $headerRow;

    for ($i = 0; $i < 7; $i++) {
        $activity = $activities[$i] ?? [];

        $date = self::formatDate(
            (string) ($activity['tanggal'] ?? '')
        );

        $activityText = (string) (
            $activity['kegiatan'] ?? ''
        );

        $newTable .= self::buildActivityRow(
            $templateRow,
            $i + 1,
            $date,
            $activityText
        );
    }

    $newTable .= '</w:tbl>';

    return substr_replace(
        $xml,
        $newTable,
        $tableMatch[0][1],
        strlen($tableXml)
    );
}

    private static function buildActivityRow(
        string $templateRow,
        int $number,
        string $date,
        string $activity
    ): string {
        preg_match_all(
            '/<w:tc>.*?<\/w:tc>/s',
            $templateRow,
            $cells
        );

        if (count($cells[0]) < 4) {
            return $templateRow;
        }

        $values = [
            (string) $number,
            $date,
            $activity,
            '',
        ];

        $newCells = '';

        foreach ($cells[0] as $index => $cell) {
            $value = $values[$index] ?? '';

            /*
             * Pertahankan tcPr.
             */
            preg_match(
                '/<w:tcPr>.*?<\/w:tcPr>/s',
                $cell,
                $tcPr
            );

            $cellProperties = $tcPr[0] ?? '';

            $alignment = $index >= 2
                ? 'left'
                : 'center';

            $newCells .=
                '<w:tc>'
                . $cellProperties
                . '<w:p>'
                . '<w:pPr>'
                . '<w:jc w:val="' . $alignment . '"/>'
                . '</w:pPr>'
                . '<w:r>'
                . '<w:rPr>'
                . '<w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>'
                . '<w:b w:val="0"/>'
                . '<w:sz w:val="20"/>'
                . '</w:rPr>'
                . '<w:t xml:space="preserve">'
                . self::escapeXml($value)
                . '</w:t>'
                . '</w:r>'
                . '</w:p>'
                . '</w:tc>';
        }

        return '<w:tr>' . $newCells . '</w:tr>';
    }

    private static function formatDate(string $date): string
    {
        if ($date === '') {
            return '';
        }

        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return $date;
        }

        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        return date('d', $timestamp)
            . ' '
            . $months[(int) date('n', $timestamp)]
            . ' '
            . date('Y', $timestamp);
    }

    private static function escapeXml(string $value): string
    {
        return htmlspecialchars(
            $value,
            ENT_XML1 | ENT_QUOTES,
            'UTF-8'
        );
    }
}