<?php

declare(strict_types=1);

$files = [
    'migration' => file_get_contents(__DIR__ . '/../database/migrations/031_create_proses_magang_and_penugasan.sql'),
    'processModel' => file_get_contents(__DIR__ . '/../models/PenugasanPembimbing.php'),
    'coordinatorAssignmentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/koordinatorMagang/PenetapanDosenController.php'),
    'staffAssignmentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/tendik/PenetapanDosenController.php'),
    'auth' => file_get_contents(__DIR__ . '/../middleware/AuthMiddleware.php'),
    'coordinatorSidebar' => file_get_contents(__DIR__ . '/../components/dashboard/koordinatorMagang/Sidebar.php'),
    'staffSidebar' => file_get_contents(__DIR__ . '/../components/dashboard/tendik/Sidebar.php'),
    'coordinatorAssignmentPage' => file_get_contents(__DIR__ . '/../pages/dashboard/koordinatorMagang/penetapan-dosen/index.php'),
    'staffAssignmentPage' => file_get_contents(__DIR__ . '/../pages/dashboard/tendik/penetapan-dosen/index.php'),
    'routes' => file_get_contents(__DIR__ . '/../routes/web.php'),
    'lifecycle' => file_get_contents(__DIR__ . '/../models/PendaftaranMagang.php'),
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
assertContains($files['staffSidebar'], "url('/dashboard/tendik/penetapan-dosen')", 'staff sidebar exposes assignment');
assertContains($files['routes'], "'/dashboard/tendik/penetapan-dosen' =>", 'staff assignment route exists');
assertContains($files['routes'], "'/dashboard/koordinator-magang/penetapan-dosen' =>", 'coordinator assignment route remains registered');
assertContains($files['lifecycle'], 'FROM proses_magang pr', 'application creation validates active process');
assertContains($files['lifecycle'], 'penugasan_pembimbing_id', 'application creation stores assignment reference');
assertContains($files['lifecycle'], 'dosen_pembimbing_snapshot_id', 'application creation stores advisor snapshot');
assertContains($files['lifecycle'], "'menunggu', 'menunggu', 'menunggu_persetujuan_dosen'", 'application status machine remains unchanged');
assertContains($files['lecturerApplicationModel'], 'dosen_pembimbing_snapshot_id', 'lecturer queue is assignment-aware');
assertContains($files['lecturerApplicationModel'], 'Pengajuan ini bukan berada pada antrean Dosen Anda.', 'lecturer decision is owner-scoped');
assertContains($files['lecturerApplicationModel'], 'assigned_u.id = :pending_user_1', 'lecturer statistics are owner-scoped');
assertContains($files['lecturerApplicationModel'], 'LEFT JOIN profil_dosen assigned_pd', 'legacy partner document access keeps snapshot join optional');
assertContains($files['partnerApplicationModel'], 'dosen_pembimbing_snapshot_id IS NOT NULL', 'partner queue requires advisor snapshot');
assertContains($files['placementModel'], 'p.dosen_pembimbing_snapshot_id', 'placement derives advisor from snapshot');
assertNotContains($files['placementModel'], 'VALUES (:application_id, NULL, :start_date', 'placement no longer starts without advisor snapshot');
assertNotContains($files['lifecycle'], "\$_POST['dosen_id']", 'application creation never accepts advisor selection');
assertContains($files['design'], 'Satu `proses_magang` aktif maksimal', 'design records active-process constraint');

fwrite(STDOUT, "Process-based advisor workflow static checks passed.\n");
