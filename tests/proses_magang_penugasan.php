<?php

declare(strict_types=1);

$files = [
    'migration' => file_get_contents(__DIR__ . '/../database/migrations/032_create_proses_magang_and_penugasan.sql'),
    'processModel' => file_get_contents(__DIR__ . '/../models/PenugasanPembimbing.php'),
    'coordinatorAssignmentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/koordinatorMagang/PenetapanDosenController.php'),
    'staffAssignmentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/tendik/PenetapanDosenController.php'),
    'auth' => file_get_contents(__DIR__ . '/../middleware/AuthMiddleware.php'),
    'coordinatorSidebar' => file_get_contents(__DIR__ . '/../components/dashboard/koordinatorMagang/Sidebar.php'),
    'coordinatorAssignmentPage' => file_get_contents(__DIR__ . '/../pages/dashboard/koordinatorMagang/penetapan-dosen/index.php'),
    'staffAssignmentPage' => file_get_contents(__DIR__ . '/../pages/dashboard/tendik/penetapan-dosen/index.php'),
    'routes' => file_get_contents(__DIR__ . '/../routes/web.php'),
    'lifecycle' => file_get_contents(__DIR__ . '/../models/PendaftaranMagang.php'),
    'partnerFormasiModel' => file_get_contents(__DIR__ . '/../models/FormasiMitra.php'),
    'applicationController' => file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/LamarController.php'),
    'partnerDocumentAccess' => file_get_contents(__DIR__ . '/../models/PersetujuanPengajuan.php'),
    'lecturerApplicationModel' => file_get_contents(__DIR__ . '/../models/PersetujuanPengajuan.php'),
    'partnerApplicationModel' => file_get_contents(__DIR__ . '/../models/PengajuanMitra.php'),
    'placementModel' => file_get_contents(__DIR__ . '/../models/PenempatanMagangCoordinator.php'),
    'design' => file_get_contents(__DIR__ . '/../Dokumentasi/developer/DESAIN_WORKFLOW_PROSES_MAGANG.md'),
];

foreach ($files as $name => $source) {
    if ($source === false) throw new RuntimeException("Unable to read {$name} source.");
}

function assertContains(string $source, string $needle, string $label): void
{
    if (!str_contains($source, $needle)) throw new RuntimeException("{$label}: missing {$needle}");
}

function assertNotContains(string $source, string $needle, string $label): void
{
    if (str_contains($source, $needle)) throw new RuntimeException("{$label}: forbidden {$needle}");
}

assertContains($files['migration'], 'CREATE TABLE proses_magang (', 'process table exists');
assertContains($files['migration'], 'CREATE TABLE penugasan_pembimbing (', 'assignment table exists');
assertContains($files['migration'], 'ALTER TABLE pendaftaran_magang', 'application snapshot columns exist');
assertContains($files['migration'], 'dosen_pembimbing_snapshot_id', 'application snapshot advisor exists');
assertContains($files['migration'], 'COALESCE(legacy.ditetapkan_oleh, pr.dibuat_oleh) IS NOT NULL', 'backfill does not invent a history actor');
assertContains($files['migration'], 'legacy.placement_count = legacy.assigned_placement_count', 'backfill skips mixed assigned and unassigned placements');
assertContains($files['migration'], 'legacy.actor_count = 1', 'backfill requires one consistent history actor');
assertContains($files['migration'], 'uq_proses_magang_mahasiswa_aktif', 'one active process per student is enforced');
assertContains($files['migration'], 'uq_penugasan_pembimbing_proses_aktif', 'one active assignment per process is enforced');
assertContains($files['migration'], 'VALUES (\'031_create_proses_magang_and_penugasan.sql\')', 'migration ledger entry exists');
assertContains($files['processModel'], 'public function createProcess(', 'process creation exists');
assertContains($files['processModel'], 'public function assign(', 'assignment creation exists');
assertContains($files['processModel'], 'public function replace(', 'assignment replacement exists');
assertContains($files['processModel'], "u.role IN ('dosen', 'koordinator_magang')", 'coordinator-primary Dosen profiles remain eligible advisor options');
assertContains($files['processModel'], 'trim($reason)', 'replacement reason is validated');
assertContains($files['processModel'], 'mb_strlen($reason) > 5000', 'reason length is limited');
assertContains($files['processModel'], "SET status = 'diganti'", 'prior assignment is preserved as replaced');
assertContains($files['processModel'], "WHERE id = :id AND status = 'aktif'", 'assignment update is conditional on active state');
assertContains($files['coordinatorAssignmentController'], "AuthMiddleware::handleAny(['tendik', 'koordinator_magang'])", 'coordinator route accepts both manager roles');
assertContains($files['staffAssignmentController'], "AuthMiddleware::handleAny(['tendik', 'koordinator_magang'])", 'staff route accepts both manager roles');
assertContains($files['auth'], 'public static function handleAny(array $requiredRoles)', 'multi-role authorization helper exists');
assertContains($files['coordinatorAssignmentPage'], 'name="proses_magang_id"', 'coordinator form posts process identifier');
assertContains($files['staffAssignmentPage'], 'name="proses_magang_id"', 'staff form posts process identifier');
assertContains($files['coordinatorAssignmentPage'], 'name="mahasiswa_id"', 'coordinator can select students for a process');
assertContains($files['staffAssignmentPage'], 'name="mahasiswa_id"', 'staff can select students for a process');
assertContains($files['routes'], "'/dashboard/tendik/penetapan-dosen' =>", 'staff assignment route exists');
assertContains($files['routes'], "'/dashboard/koordinator-magang/penetapan-dosen' =>", 'coordinator assignment route remains registered');
assertNotContains($files['lifecycle'], 'FROM proses_magang pr', 'application creation does not require a process or supervisor');
assertNotContains($files['lifecycle'], 'dosen_pembimbing_snapshot_id', 'application creation does not require or set an advisor snapshot');
assertContains($files['lifecycle'], "'menunggu', 'menunggu', 'menunggu_respons_mitra'", 'new application enters partner review without lecturer approval');
assertContains($files['lifecycle'], "f.status='dibuka'", 'application still requires an open position');
assertNotContains($files['lifecycle'], 'm.status_verifikasi', 'application creation does not query the obsolete partner verification column');
assertContains($files['lifecycle'], "m.is_active=TRUE", 'application still requires an active partner');
assertNotContains($files['partnerFormasiModel'], 'status_verifikasi', 'partner form management does not query the obsolete verification column');
assertContains($files['partnerFormasiModel'], "\$company['is_active']", 'partner form management still requires an active partner');
assertContains($files['lifecycle'], '(int)$formasi[\'jumlah_diterima\'] >= (int)$formasi[\'jumlah_kuota\']', 'application still enforces the position quota');
assertContains($files['lifecycle'], 'hasActiveApplication($mahasiswaId)', 'application still enforces one active application per student');
assertContains($files['applicationController'], 'verifyCsrfToken()', 'application submission still requires CSRF validation');
assertContains($files['applicationController'], '$this->validPdf($field, $label, $errors, true)', 'required application documents remain validated');
assertContains($files['applicationController'], '$this->validPdf($field, $label, $errors, false)', 'optional application documents remain validated');
assertNotContains($files['applicationController'], "\$_POST['dosen_id']", 'students cannot choose an advisor during submission');
assertContains($files['processModel'], "p.status_pendaftaran = 'diterima'", 'advisor eligibility requires partner acceptance');
assertContains($files['processModel'], 'Hanya pengajuan yang telah diterima Mitra', 'process creation rejects applicants not accepted by partner');
assertContains($files['processModel'], 'Dosen hanya dapat ditetapkan setelah pengajuan diterima Mitra.', 'assignment mutation rejects applicants not accepted by partner');
assertContains($files['partnerApplicationModel'], "(p.status_persetujuan_dosen = 'menunggu'", 'partner queue includes new applications with no assigned advisor');
assertContains($files['partnerApplicationModel'], "(status_persetujuan_dosen = 'menunggu'", 'partner response supports new applications without a supervisor');
assertContains($files['lecturerApplicationModel'], 'dosen_pembimbing_snapshot_id', 'lecturer queue is assignment-aware');
assertContains($files['lecturerApplicationModel'], 'Pengajuan ini bukan berada pada antrean Dosen Anda.', 'lecturer decision is owner-scoped');
assertContains($files['lecturerApplicationModel'], 'assigned_u.id = :pending_user_1', 'lecturer statistics are owner-scoped');
assertContains($files['lecturerApplicationModel'], 'LEFT JOIN profil_dosen assigned_pd', 'legacy partner document access keeps snapshot join optional');
assertContains($files['partnerApplicationModel'], 'dosen_pembimbing_snapshot_id IS NOT NULL', 'partner queue preserves support for legacy approved applications');
assertContains($files['partnerDocumentAccess'], 'p.dosen_pembimbing_snapshot_id IS NULL', 'partner document access supports new applications without an advisor');
assertContains($files['placementModel'], 'p.dosen_pembimbing_snapshot_id', 'placement derives advisor from snapshot');
assertNotContains($files['placementModel'], 'VALUES (:application_id, NULL, :start_date', 'placement no longer starts without advisor snapshot');
assertNotContains($files['lifecycle'], "\$_POST['dosen_id']", 'application creation never accepts advisor selection');
assertContains($files['design'], 'Satu `proses_magang` aktif maksimal', 'design records active-process constraint');
assertContains($files['design'], 'setelah Mitra menerima pengajuan', 'design states the post-acceptance assignment stage');

fwrite(STDOUT, "Process-based advisor workflow static checks passed.\n");
