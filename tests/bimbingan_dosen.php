<?php

declare(strict_types=1);

$files = [
    'assignmentModel' => file_get_contents(__DIR__ . '/../models/PenempatanMagangCoordinator.php'),
    'assignmentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/koordinatorMagang/PenempatanController.php'),
    'studentModel' => file_get_contents(__DIR__ . '/../models/PermintaanPembimbing.php'),
    'studentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/PermintaanPembimbingController.php'),
    'studentPage' => file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/pembimbing/index.php'),
    'lecturerPage' => file_get_contents(__DIR__ . '/../pages/dashboard/dosen/permintaan-pembimbing/index.php'),
    'lecturerModel' => file_get_contents(__DIR__ . '/../models/PermintaanPembimbing.php'),
    'routes' => file_get_contents(__DIR__ . '/../routes/web.php'),
    'coordinatorPage' => file_get_contents(__DIR__ . '/../pages/dashboard/koordinatorMagang/penempatan/index.php'),
    'coordinatorSidebar' => file_get_contents(__DIR__ . '/../components/dashboard/koordinatorMagang/Sidebar.php'),
    'assignmentController' => file_get_contents(__DIR__ . '/../controllers/dashboard/koordinatorMagang/PenetapanDosenController.php'),
    'assignmentPage' => file_get_contents(__DIR__ . '/../pages/dashboard/koordinatorMagang/penetapan-dosen/index.php'),
    'applicationModel' => file_get_contents(__DIR__ . '/../models/PendaftaranMagang.php'),
    'studentApplicationPage' => file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/pengajuan/detail.php'),
];

foreach ($files as $name => $source) {
    if ($source === false) {
        throw new RuntimeException("Unable to read {$name} source.");
    }
}

function assertContains(string $source, string $needle, string $label): void
{
    if (!str_contains($source, $needle)) {
        throw new RuntimeException("{$label}: missing {$needle}");
    }
}

function assertNotContains(string $source, string $needle, string $label): void
{
    if (str_contains($source, $needle)) {
        throw new RuntimeException("{$label}: forbidden {$needle}");
    }
}

assertContains($files['assignmentModel'], 'public function getPlacementsForAssignment()', 'legacy placement queue remains available');
assertContains($files['assignmentModel'], 'public function assignAdvisor(', 'legacy placement mutation remains available');
assertContains($files['assignmentModel'], 'Penetapan pembimbing melalui penempatan sudah tidak tersedia', 'legacy placement mutation is fail-closed');
assertContains($files['assignmentPage'], 'name="dosen_id"', 'dedicated page posts only the coordinator-selected advisor');
assertContains($files['studentModel'], "WHERE penempatan_id = :placement_id AND status_permintaan = 'menunggu'", 'student request is placement-scoped');
assertContains($files['studentModel'], 'pm.dosen_pembimbing_id', 'student request derives assigned advisor');
assertContains($files['studentModel'], "(int) \$placement['dosen_pembimbing_id']", 'student request stores assigned advisor only');
assertNotContains($files['studentController'], "\$_POST['dosen_id']", 'student controller never accepts advisor selection');
assertNotContains($files['studentPage'], 'name="dosen_id"', 'student UI never exposes advisor selection');
assertContains($files['studentPage'], "\$placement['dosen_pembimbing_id'] !== null", 'student request form requires an assigned advisor');
assertContains($files['studentPage'], 'name="alasan_pengajuan"', 'student can submit request reason');
assertContains($files['lecturerModel'], 'public function getHistoryForDosen(', 'lecturer history exists');
assertContains($files['lecturerModel'], 'WHERE dosen.id = :user_id', 'lecturer history is owner-scoped');
assertContains($files['lecturerPage'], 'Riwayat Permintaan', 'lecturer history is rendered');
assertContains($files['lecturerModel'], "currentAdvisor['dosen_pembimbing_id']", 'lecturer decision verifies current assignment');
assertNotContains($files['lecturerModel'], 'UPDATE penempatan_magang SET dosen_pembimbing_id', 'lecturer decision cannot reassign the advisor');
assertContains($files['assignmentController'], "AuthMiddleware::handleAny(['tendik', 'koordinator_magang'])", 'dedicated assignment requires approved manager role');
assertContains($files['assignmentController'], 'PenugasanPembimbing', 'dedicated assignment uses process assignment model');
assertContains($files['assignmentController'], "url('/dashboard/koordinator-magang/penetapan-dosen?success=1')", 'assignment success remains on the dedicated page');
assertContains($files['assignmentPage'], 'name="proses_magang_id"', 'dedicated page posts the process identifier');
assertContains($files['assignmentPage'], 'name="dosen_id"', 'dedicated page posts only the coordinator-selected advisor');
assertContains($files['assignmentPage'], 'Satu mahasiswa hanya dapat memiliki satu proses magang aktif', 'dedicated assignment page uses process eligibility');
assertContains($files['coordinatorSidebar'], 'Penetapan Dosen Pembimbing', 'coordinator sidebar exposes dedicated assignment');
assertContains($files['coordinatorSidebar'], "'/dashboard/koordinator-magang/penetapan-dosen'", 'sidebar links to the dedicated assignment route');
assertNotContains($files['coordinatorPage'], 'name="dosen_id"', 'placement page no longer mixes placement creation with advisor selection');
assertContains($files['applicationModel'], "'menunggu', 'menunggu', 'menunggu_persetujuan_dosen'", 'application creation starts at the documented lecturer-review status');
assertContains($files['studentApplicationPage'], 'StatusPengajuan.php', 'student application status continues to use the application timeline');
assertContains($files['routes'], "'/dashboard/koordinator-magang/penempatan' =>", 'coordinator placement route remains registered');

fwrite(STDOUT, "Bimbingan Dosen static workflow checks passed.\n");
