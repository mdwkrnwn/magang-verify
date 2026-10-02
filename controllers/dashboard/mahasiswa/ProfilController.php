
<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/ProfilMahasiswa.php';

class ProfilController
{
    private ProfilMahasiswa $profilModel;

    public function __construct()
    {
        global $pdo;

        $this->profilModel = new ProfilMahasiswa($pdo);
    }

    public function index($params = [])
    {
        AuthMiddleware::handle('mahasiswa');

        $userId = (int) $_SESSION['user']['id'];

        // Proses penyimpanan ketika form dikirim.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($userId);
            return;
        }

        // Ambil profil untuk ditampilkan.
        try {
            $profil = $this->profilModel->getOrCreateByUserId($userId);
        } catch (Throwable $e) {
            error_log('Gagal mengambil profil mahasiswa: ' . $e->getMessage());

            http_response_code(500);
            exit('Terjadi kesalahan saat mengambil data profil.');
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/profil/index.php';
    }

    /**
     * Menangani pembaruan profil.
     */
    private function update(int $userId): void
    {
        // Pastikan profil tersedia sebelum melakukan UPDATE.
        try {
            $this->profilModel->getOrCreateByUserId($userId);

            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            $action = $_POST['action'] ?? '';

            switch ($action) {
                case 'personal':
                    $this->updatePersonal($userId);
                    break;

                case 'contact':
                    $this->updateContact($userId);
                    break;

                case 'bio':
                    $this->updateBio($userId);
                    break;

                case 'photo':
                    $this->updatePhoto($userId);
                    break;

                default:
                    $this->redirect('error', 'invalid_action');
            }
        } catch (Throwable $e) {
            error_log('Gagal memperbarui profil mahasiswa: ' . $e->getMessage());

            $this->redirect('error', 'save_failed');
        }
    }

    private function updatePersonal(int $userId): void
    {
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $jenisKelamin = $_POST['jenis_kelamin'] ?? '';
        $tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');

        if ($nama === '' || mb_strlen($nama) > 150) {
            $this->redirect('error', 'invalid_name');
        }

        if (
            $jenisKelamin !== '' &&
            !in_array($jenisKelamin, ['Laki-laki', 'Perempuan'], true)
        ) {
            $this->redirect('error', 'invalid_gender');
        }

        if ($tanggalLahir !== '') {
            $date = DateTime::createFromFormat('!Y-m-d', $tanggalLahir);

            if (
                !$date ||
                $date->format('Y-m-d') !== $tanggalLahir ||
                $tanggalLahir > date('Y-m-d')
            ) {
                $this->redirect('error', 'invalid_birth_date');
            }
        }

        $this->profilModel->updatePersonal($userId, [
            'nama_lengkap' => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'tanggal_lahir' => $tanggalLahir,
            'alamat' => $alamat,
        ]);

        $this->redirect('success', 'personal_updated');
    }

    private function updateContact(int $userId): void
    {
        $email = trim($_POST['email'] ?? '');
        $noHp = trim($_POST['no_hp'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');

        if (
            mb_strlen($email) > 254 ||
            ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
        ) {
            $this->redirect('error', 'invalid_email');
        }

        if (
            mb_strlen($noHp) > 20 ||
            ($noHp !== '' && !preg_match('/^[0-9+\s().-]+$/', $noHp))
        ) {
            $this->redirect('error', 'invalid_phone');
        }

        if (mb_strlen($instagram) > 100) {
            $this->redirect('error', 'invalid_instagram');
        }

        $this->profilModel->updateContact($userId, [
            'email' => $email,
            'no_hp' => $noHp,
            'instagram' => $instagram,
        ]);

        $this->redirect('success', 'contact_updated');
    }

    private function updateBio(int $userId): void
    {
        $bio = trim($_POST['bio'] ?? '');
        $keahlianText = trim($_POST['keahlian_input'] ?? '');

        if (mb_strlen($bio) > 5000 || mb_strlen($keahlianText) > 500) {
            $this->redirect('error', 'invalid_bio');
        }

        // Ubah input teks yang dipisahkan koma menjadi array.
        $keahlianInput = $keahlianText === ''
            ? []
            : array_map('trim', explode(',', $keahlianText));

        // Hilangkan keahlian kosong.
        $keahlianInput = array_values(array_filter(
            $keahlianInput,
            static fn($item) => $item !== ''
        ));

        if (count($keahlianInput) > 30) {
            $this->redirect('error', 'too_many_skills');
        }

        $keahlian = [];

        foreach ($keahlianInput as $item) {
            if (mb_strlen($item) > 100) {
                $this->redirect('error', 'invalid_skill');
            }

            $keahlian[] = $item;
        }

        $this->profilModel->updateBio($userId, $bio, $keahlian);

        $this->redirect('success', 'bio_updated');
    }

    private function updatePhoto(int $userId): void
    {
        if (
            !isset($_FILES['foto_profil']) ||
            $_FILES['foto_profil']['error'] !== UPLOAD_ERR_OK
        ) {
            $this->redirect('error', 'photo_upload_failed');
        }

        $file = $_FILES['foto_profil'];

        // Maksimal 2 MB.
        if ($file['size'] > 2 * 1024 * 1024) {
            $this->redirect('error', 'photo_too_large');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedTypes[$mime])) {
            $this->redirect('error', 'invalid_photo_type');
        }

        if (@getimagesize($file['tmp_name']) === false) {
            $this->redirect('error', 'invalid_photo');
        }

        $uploadDir = dirname(__DIR__, 3) . '/uploads/profil';

        if (
            !is_dir($uploadDir) &&
            !mkdir($uploadDir, 0755, true) &&
            !is_dir($uploadDir)
        ) {
            throw new RuntimeException('Folder foto profil gagal dibuat.');
        }

        $filename = bin2hex(random_bytes(16))
            . '.' . $allowedTypes[$mime];

        $destination = $uploadDir . '/' . $filename;
        $relativePath = 'uploads/profil/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->redirect('error', 'photo_upload_failed');
        }

        try {
            $oldProfil = $this->profilModel->getOrCreateByUserId($userId);

            $this->profilModel->updatePhoto($userId, $relativePath);

            // Hapus foto lama hanya jika berada di folder upload profil.
            $oldPath = $oldProfil['foto_profil'] ?? '';

            if (
                $oldPath !== '' &&
                str_starts_with($oldPath, 'uploads/profil/')
            ) {
                $oldFile = dirname(__DIR__, 3) . '/' . $oldPath;

                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }
        } catch (Throwable $e) {
            if (is_file($destination)) {
                @unlink($destination);
            }

            throw $e;
        }

        $this->redirect('success', 'photo_updated');
    }

    private function redirect(string $type, string $message): void
    {
        header(
            'Location: ' . APP_URL
                . '/dashboard/mahasiswa/profil?'
                . $type . '=' . rawurlencode($message),
            true,
            303
        );

        exit;
    }
}
