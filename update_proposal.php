<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Proposal;

// Get first proposal
$proposal = Proposal::first();

if ($proposal) {
    echo "Before Update:\n";
    echo "ID: {$proposal->id}\n";
    echo "Nama Kelompok: {$proposal->nama_kelompok}\n";
    echo "Status Dosen: {$proposal->status_dosen}\n";
    echo "Status Kaprodi: {$proposal->status_kaprodi}\n";
    echo "File Proposal: " . ($proposal->file_proposal ?? 'NULL') . "\n\n";
    
    // Update status kaprodi to approved
    $proposal->status_kaprodi = 'disetujui';
    $proposal->save();
    
    echo "After Update:\n";
    echo "Status Kaprodi: {$proposal->status_kaprodi}\n";
    echo "\nProposal siap untuk upload file!\n";
} else {
    echo "No proposals found.\n";
}
