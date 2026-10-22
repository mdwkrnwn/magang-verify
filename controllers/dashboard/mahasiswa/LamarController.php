<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../function/PendaftaranDocuments.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/FormasiMagang.php';
require_once __DIR__ . '/../../../models/PendaftaranMagang.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';

class LamarController
{
    public function index($params = [])
    {
        AuthMiddleware::handle('mahasiswa');
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $slug = (string)($params['slug'] ?? '');
        $formasiModel = new FormasiMagang();
        $pendaftaranModel = new PendaftaranMagang();
        $aktivitasModel = new AktivitasPengguna();
        $formasi = $formasiModel->getBySlug($slug);
        if (!$formasi) $this->notFound();
        $formasiTersedia = ($formasi['status'] ?? '') === 'tersedia'
            && ($formasi['status_formasi'] ?? '') === 'dibuka';
        $profil = $pendaftaranModel->getProfilMahasiswa($userId);
        if (!$profil || trim((string)($profil['program_studi'] ?? '')) === '') {
            http_response_code(422);
            exit('Profil mahasiswa belum lengkap atau tidak ditemukan. Lengkapi data program studi pada profil terlebih dahulu.');
        }
        $mahasiswa = ['nama' => $profil['nama'], 'nim' => $profil['nim'], 'prodi' => $profil['program_studi'] ?: '-', 'jurusan' => '-'];
        $nama = $mahasiswa['nama']; $nim = $mahasiswa['nim']; $prodi = $mahasiswa['prodi'];
        $email = trim((string)($profil['email'] ?? '')) ?: '-';
        $noTelepon = trim((string)($profil['no_telepon'] ?? '')) ?: '-';
        $hasActiveApplication = $pendaftaranModel->hasActiveApplication((int)$profil['mahasiswa_id']);
        $existingApplication = $hasActiveApplication || !$formasiTersedia;
        $errors = [];
        if (!$formasiTersedia) {
            http_response_code(409);
            $errors['umum'] = 'Formasi ini sudah tidak menerima pendaftaran. Silakan pilih formasi lain.';
        } elseif ($hasActiveApplication) {
            $errors['umum'] = 'Anda masih memiliki pengajuan magang aktif. Selesaikan atau batalkan pengajuan tersebut sebelum mengajukan formasi lain.';
        }
        $success = false;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403); exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
            }
            if ($existingApplication) {
                $errors['umum'] = !$formasiTersedia
                    ? 'Formasi ini sudah tidak menerima pendaftaran. Silakan pilih formasi lain.'
                    : 'Anda masih memiliki pengajuan magang aktif. Selesaikan atau batalkan pengajuan tersebut sebelum mengajukan formasi lain.';
            }
            $requiredDocuments = [
                'pakta_integritas' => 'Pakta Integritas',
                'daftar_riwayat_hidup' => 'Daftar Riwayat Hidup',
                'khs' => 'KHS / Cetak Siakad',
                'ktp' => 'KTP',
                'ktm' => 'KTM',
                'surat_izin_orang_tua' => 'Surat Izin Orang Tua',
            ];
            $optionalDocuments = [
                'bpjs' => 'Kartu BPJS / Asuransi',
                'sktm_kip' => 'SKTM / KIP Kuliah',
                'proposal' => 'Proposal Magang',
                'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
            ];

            foreach ($requiredDocuments as $field => $label) {
                $this->validPdf($field, $label, $errors, true);
            }
            foreach ($optionalDocuments as $field => $label) {
                $this->validPdf($field, $label, $errors, false);
            }
            if (!isset($_POST['pernyataan']) || $_POST['pernyataan'] !== '1') $errors['pernyataan'] = 'Pernyataan wajib disetujui.';
            if (!$errors) {
                $stored = [];
                try {
                    foreach (array_merge($requiredDocuments, $optionalDocuments) as $field => $label) {
                        if (empty($_FILES[$field]) || (int)($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                            continue;
                        }
                        $stored[applicationDocumentKind($field)] = $this->storePdf($_FILES[$field]);
                    }
                    $applicationId = $pendaftaranModel->create((int)$profil['mahasiswa_id'], (int)$formasi['id'], $stored);
                    $aktivitasModel->log(
                        (int) $_SESSION['user']['id'],
                        'Pengajuan magang dikirim',
                        'Pengajuan "' . ($formasi['judul'] ?? 'formasi magang') . '" berhasil dikirim.',
                        'pengajuan',
                        'pendaftaran_magang',
                        $applicationId
                    );
                    header('Location: ' . url('/dashboard/mahasiswa/pengajuan/detail/' . $applicationId)); exit;
                } catch (Throwable $e) {
                    foreach ($stored as $doc) if (!empty($doc['absolute_path']) && is_file($doc['absolute_path'])) @unlink($doc['absolute_path']);
                    if ($e instanceof DomainException) $errors['umum'] = $e->getMessage();
                    else { error_log('Gagal menyimpan pengajuan magang: ' . $e->getMessage()); $errors['umum'] = 'Pengajuan gagal disimpan. Pastikan migration dokumen sudah diterapkan, lalu coba lagi.'; }
                }
            }
        }
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/lamar/index.php';
    }

    private function validPdf(string $field, string $label, array &$errors, bool $required): bool
    {
        $file = $_FILES[$field] ?? [];
        $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE && !$required) return true;
        if ($error !== UPLOAD_ERR_OK) { $errors[$field] = $required ? "$label wajib diunggah dalam format PDF." : "$label gagal diunggah. Silakan pilih file PDF yang valid."; return false; }
        $tmp = (string) ($file['tmp_name'] ?? '');
        $size = (int) ($file['size'] ?? 0);
        if ($size < 1 || $size > 5 * 1024 * 1024) { $errors[$field] = "$label harus berukuran 1 byte hingga 5 MB."; return false; }
        if ($tmp === '' || !is_uploaded_file($tmp)) { $errors[$field] = "$label bukan file unggahan yang valid."; return false; }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        $signature = file_get_contents($tmp, false, null, 0, 5);
        if ($mime !== 'application/pdf' || strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION)) !== 'pdf' || $signature !== '%PDF-') { $errors[$field] = "$label harus berupa file PDF yang valid."; return false; }
        return true;
    }

    private function storePdf(array $file): array
    {
        $relativeDir = 'storage/pendaftaran';
        $absoluteDir = dirname(__DIR__, 3) . '/' . $relativeDir;
        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0750, true) && !is_dir($absoluteDir)) throw new RuntimeException('Folder penyimpanan dokumen tidak dapat dibuat.');
        $filename = bin2hex(random_bytes(24)) . '.pdf';
        $destination = $absoluteDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) throw new RuntimeException('Dokumen gagal disimpan.');
        return ['nama_asli' => basename($file['name']), 'path_file' => $relativeDir . '/' . $filename, 'mime_type' => 'application/pdf', 'ukuran_bytes' => (int)$file['size'], 'absolute_path' => $destination];
    }

    private function notFound(): never
    {
        http_response_code(404); require __DIR__ . '/../../../pages/errors/404.php'; exit;
    }
}
