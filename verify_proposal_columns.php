<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== VERIFIKASI KOLOM PROPOSALS TABLE ===\n\n";

try {
    $columns = [
        'file_proposal',
        'status_admin', 
        'catatan_admin'
    ];
    
    foreach ($columns as $column) {
        $exists = Schema::hasColumn('proposals', $column);
        $status = $exists ? '✅' : '❌';
        echo "{$status} Kolom '{$column}': " . ($exists ? 'ADA' : 'TIDAK ADA') . "\n";
    }
    
    echo "\n=== TEST QUERY ===\n";
    
    // Test query yang sebelumnya error
    $proposals = \App\Models\Proposal::select('id', 'nama_kelompok', 'file_proposal', 'status_admin')
        ->whereNotNull('file_proposal')
        ->limit(5)
        ->get();
    
    echo "✅ Query file_proposal berhasil! Jumlah proposal dengan file: {$proposals->count()}\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}