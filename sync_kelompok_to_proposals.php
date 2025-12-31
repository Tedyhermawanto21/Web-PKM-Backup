<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Kelompok;
use App\Models\Proposal;

echo "Syncing approved Kelompok -> Proposals\n";

$kelompoks = Kelompok::where(function($q){
    $q->where('status_kaprodi', 'disetujui')->orWhere('status', 'approved');
})->get();

if ($kelompoks->isEmpty()) {
    echo "No approved kelompoks found.\n";
    exit;
}

$updatedCount = 0;

foreach ($kelompoks as $k) {
    // Match proposals by ketua_id and dosen-approved; nama may differ so avoid strict name match
    $proposals = Proposal::where('ketua_id', $k->ketua_id)
        ->where('status_dosen', 'disetujui')
        ->get();

    foreach ($proposals as $p) {
        $p->status_kaprodi = 'disetujui';
        $p->save();
        $updatedCount++;
        echo "Updated proposal id={$p->id} (kelompok={$p->nama_kelompok})\n";
    }
}

echo "Done. Total proposals updated: {$updatedCount}\n";
