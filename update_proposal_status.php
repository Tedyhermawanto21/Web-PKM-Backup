<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking proposals...\n";

$proposals = DB::table('proposals')->get();

if ($proposals->isEmpty()) {
    echo "No proposals found in database.\n";
} else {
    echo "Found " . $proposals->count() . " proposals:\n\n";
    
    foreach ($proposals as $proposal) {
        echo "ID: {$proposal->id}\n";
        echo "Kelompok: {$proposal->nama_kelompok}\n";
        echo "Status: {$proposal->status}\n";
        echo "Status Dosen: {$proposal->status_dosen}\n";
        echo "Status Kaprodi: {$proposal->status_kaprodi}\n";
        echo "Dosen Pembimbing ID: {$proposal->dosen_pembimbing_id}\n";
        echo "---\n";
    }
    
    // Update proposal yang statusnya disetujui menjadi status_dosen disetujui juga
    echo "\nUpdating proposals where status='disetujui'...\n";
    $updated = DB::table('proposals')
        ->where('status', 'disetujui')
        ->update(['status_dosen' => 'disetujui']);
    
    echo "Updated {$updated} proposal(s).\n";
}
