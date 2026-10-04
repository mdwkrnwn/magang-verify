
<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/app.php';
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($userId);
            return;
        }

        try {
            $profil = $this->profilModel->getByUserId($userId);

            if (!$profil) {
                http_response_code(404);
                exit('Profil mahasiswa tidak ditemukan.');
            }
        } catch (Throwable $e) {
            error_log(
                'Gagal mengambil profil mahasiswa: ' . $e->getMessage()
            );

            http_response_code(500);
            exit('Terjadi kesalahan saat mengambil data profil.');
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/profil/index.php';
    }

    private function update(int $userId): void
    {
        try {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            // Pastikan profil tersedia sebelum melakukan UPDATE.
            $profil = $this->profilModel->getByUserId($userId);

            if (!$profil) {
                $this->redirect('error', 'profile_not_found');
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
                        $this->updateBio($userId, (int) $profil['profil_id']);
                        break;
                    
                    case 'skills':
                        $this->updateSkills((int) $profil['profil_id']);
                        break;

                case 'photo':
                    $this->updatePhoto($userId);
                    break;

                case 'cv':
                    $this->updateCv($userId);
                    break;

                default:
                    $this->redirect('error', 'invalid_action');
            }
        } catch (Throwable $e) {
            error_log(
                'Gagal memperbarui profil mahasiswa: ' . $e->getMessage()
            );

            $this->redirect('error', 'save_failed');
        }
    }

    
private function updatePersonal(int $userId): void
{
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '' || mb_strlen($nama) > 150) {
        $this->redirect('error', 'invalid_name');
    }

    if (mb_strlen($alamat) > 5000) {
        $this->redirect('error', 'invalid_address');
    }

    if (mb_strlen($deskripsi) > 5000) {
        $this->redirect('error', 'invalid_description');
    }

    $this->profilModel->updatePersonal($userId, [
        'nama_lengkap' => $nama,
        'alamat' => $alamat,
        'deskripsi' => $deskripsi,
    ]);

    // Perbarui nama pada session agar header ikut berubah.
    $_SESSION['user']['name'] = $nama;

    $this->redirect('success', 'personal_updated');
}

    private function updateContact(int $userId): void
    {
        $email = trim($_POST['email'] ?? '');
        $noTelepon = trim($_POST['no_telepon'] ?? '');
        $github = trim($_POST['github_url'] ?? '');
        $linkedin = trim($_POST['linkedin_url'] ?? '');
        $portfolio = trim($_POST['portfolio_url'] ?? '');

        if (
            mb_strlen($email) > 255 ||
            ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
        ) {
            $this->redirect('error', 'invalid_email');
        }

        if (
            mb_strlen($noTelepon) > 30 ||
            ($noTelepon !== '' &&
                !preg_match('/^[0-9+\s().-]+$/', $noTelepon))
        ) {
            $this->redirect('error', 'invalid_phone');
        }

        foreach ([
            'github_url' => $github,
            'linkedin_url' => $linkedin,
            'portfolio_url' => $portfolio,
        ] as $field => $value) {
            if ($value !== '' && (mb_strlen($value) > 500 || !filter_var($value, FILTER_VALIDATE_URL))) {
                $this->redirect('error', 'invalid_social_link');
            }
        }

        $this->profilModel->updateContact($userId, [
            'email' => $email,
            'no_telepon' => $noTelepon,
            'github_url' => $github,
            'linkedin_url' => $linkedin,
            'portfolio_url' => $portfolio,
        ]);

        $this->redirect('success', 'contact_updated');
    }

    
private function updateBio(int $userId, int $profilId): void
{
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $skillsText = trim($_POST['keahlian_input'] ?? '');

    if (mb_strlen($deskripsi) > 5000) {
        $this->redirect('error', 'invalid_description');
    }

    if (mb_strlen($skillsText) > 3000) {
        $this->redirect('error', 'invalid_skills');
    }

    $skills = $skillsText === ''
        ? []
        : array_map('trim', explode(',', $skillsText));

    $skills = array_values(array_unique(array_filter(
        $skills,
        static fn($skill) => $skill !== ''
    )));

    if (count($skills) > 30) {
        $this->redirect('error', 'too_many_skills');
    }

    foreach ($skills as $skill) {
        if (mb_strlen($skill) > 100) {
            $this->redirect('error', 'invalid_skill');
        }
    }

    // Simpan deskripsi profil.
    $this->profilModel->updateDescription($userId, $deskripsi);

    // Simpan daftar keahlian.
    $this->profilModel->updateSkills($profilId, $skills);

    $this->redirect('success', 'bio_updated');
}

    private function updateSkills(int $profilId): void
    {
        $skillsText = trim($_POST['keahlian_input'] ?? '');

        if (mb_strlen($skillsText) > 3000) {
            $this->redirect('error', 'invalid_skills');
        }

        $skills = $skillsText === ''
            ? []
            : array_map('trim', explode(',', $skillsText));

        $skills = array_values(array_unique(array_filter(
            $skills,
            static fn($skill) => $skill !== ''
        )));

        if (count($skills) > 30) {
            $this->redirect('error', 'too_many_skills');
        }

        foreach ($skills as $skill) {
            if (mb_strlen($skill) > 100) {
                $this->redirect('error', 'invalid_skill');
            }
        }

        $this->profilModel->updateSkills($profilId, $skills);

        $this->redirect('success', 'skills_updated');
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

        $projectRoot = dirname(__DIR__, 3);
        $uploadDir = $projectRoot . '/uploads/profil';

        if (
            !is_dir($uploadDir) &&
            !mkdir($uploadDir, 0755, true) &&
            !is_dir($uploadDir)
        ) {
            throw new RuntimeException(
                'Folder foto profil gagal dibuat.'
            );
        }

        $filename = bin2hex(random_bytes(16))
            . '.' . $allowedTypes[$mime];

        $destination = $uploadDir . '/' . $filename;
        $relativePath = 'uploads/profil/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->redirect('error', 'photo_upload_failed');
        }

        try {
            $oldProfil = $this->profilModel->getByUserId($userId);

            if (!$oldProfil) {
                throw new RuntimeException(
                    'Profil mahasiswa tidak ditemukan.'
                );
            }

            $this->profilModel->updatePhoto($userId, $relativePath);

            $oldPath = $oldProfil['foto_path'] ?? '';

            if (
                $oldPath !== '' &&
                str_starts_with($oldPath, 'uploads/profil/')
            ) {
                $oldFile = $projectRoot . '/' . $oldPath;

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


    private function updateCv(int $userId): void
    {
        if (
            !isset($_FILES['cv']) ||
            $_FILES['cv']['error'] !== UPLOAD_ERR_OK
        ) {
            $this->redirect('error', 'cv_upload_failed');
        }

        $file = $_FILES['cv'];
        $maxSize = 5 * 1024 * 1024;

        if ((int) $file['size'] <= 0 || (int) $file['size'] > $maxSize) {
            $this->redirect('error', 'cv_too_large');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if ($mime !== 'application/pdf') {
            $this->redirect('error', 'invalid_cv_type');
        }

        // Validasi tambahan agar file benar-benar diawali signature PDF.
        $handle = @fopen($file['tmp_name'], 'rb');
        $signature = $handle !== false
            ? fread($handle, 5)
            : false;

        if ($handle !== false) {
            fclose($handle);
        }

        if ($signature !== '%PDF-') {
            $this->redirect('error', 'invalid_cv_file');
        }

        $originalName = trim((string) ($file['name'] ?? 'CV.pdf'));
        $originalName = basename($originalName);

        // Simpan nama asli secara aman untuk ditampilkan/download.
        $originalName = preg_replace(
            '/[^\pL\pN._() -]+/u',
            '_',
            $originalName
        );

        if (!is_string($originalName) || $originalName === '') {
            $originalName = 'CV.pdf';
        }

        if (!str_ends_with(strtolower($originalName), '.pdf')) {
            $originalName .= '.pdf';
        }

        $originalName = mb_substr($originalName, 0, 255);

        $projectRoot = dirname(__DIR__, 3);
        $uploadDir = $projectRoot . '/uploads/cv';

        if (
            !is_dir($uploadDir) &&
            !mkdir($uploadDir, 0755, true) &&
            !is_dir($uploadDir)
        ) {
            throw new RuntimeException('Folder CV gagal dibuat.');
        }

        $filename = bin2hex(random_bytes(16)) . '.pdf';
        $destination = $uploadDir . '/' . $filename;
        $relativePath = 'uploads/cv/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->redirect('error', 'cv_upload_failed');
        }

        try {
            $oldProfil = $this->profilModel->getByUserId($userId);

            if (!$oldProfil) {
                throw new RuntimeException(
                    'Profil mahasiswa tidak ditemukan.'
                );
            }

            $this->profilModel->updateCv(
                $userId,
                $relativePath,
                $originalName,
                'application/pdf',
                (int) $file['size']
            );

            $oldPath = trim((string) ($oldProfil['cv_path'] ?? ''));

            if (
                $oldPath !== '' &&
                str_starts_with($oldPath, 'uploads/cv/')
            ) {
                $oldFile = $projectRoot . '/' . $oldPath;

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

        $this->redirect('success', 'cv_updated');
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