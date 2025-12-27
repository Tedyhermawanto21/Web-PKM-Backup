<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Proposal;

echo "=== Setup Test Data ===\n\n";

// Proposal 1: Ready for upload (approved by both, no file)
$proposal1 = Proposal::find(2);
if ($proposal1) {
    $proposal1->update([
        'status_dosen' => 'disetujui',
        'status_kaprodi' => 'disetujui',
        'file_proposal' => null,
        'status_admin' => 'menunggu',
        'catatan_admin' => null
    ]);
    echo "✓ Proposal ID 2: Ready for upload\n";
}

// Proposal 2: Uploaded and waiting admin review
$proposal2 = Proposal::find(1);
if ($proposal2) {
    $proposal2->update([
        'status_dosen' => 'disetujui',
        'status_kaprodi' => 'disetujui',
        'file_proposal' => 'proposals/test_file.pdf',
        'status_admin' => 'menunggu',
        'catatan_admin' => null
    ]);
    echo "✓ Proposal ID 1: Waiting admin review\n";
}

// Create proposal 3 if it exists: Rejected by admin
$proposal3 = Proposal::find(3);
if ($proposal3) {
    $proposal3->update([
        'status_dosen' => 'disetujui',
        'status_kaprodi' => 'disetujui',
        'file_proposal' => 'proposals/test_file_rejected.pdf',
        'status_admin' => 'ditolak',
        'catatan_admin' => 'Format proposal tidak sesuai. Mohon perbaiki struktur penulisan dan upload ulang.'
    ]);
    echo "✓ Proposal ID 3: Rejected by admin (can re-upload)\n";
}

echo "\n=== Test Data Ready! ===\n";
echo "- Visit: http://localhost/pkm-center-test/mahasiswa/upload\n";
echo "- You should see 2 proposals in the index\n";
echo "- Click 'Upload Proposal PKM' to see proposal ready for upload\n";
echo "- Click edit on rejected proposal to re-upload\n";
