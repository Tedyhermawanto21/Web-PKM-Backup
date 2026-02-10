<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== VERIFIKASI ROUTE DETAIL BIMBINGAN ===\n\n";

try {
    // Cek apakah route ada
    $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function($route) {
        return $route->getName();
    })->filter(function($name) {
        return str_contains($name ?: '', 'dosen.bimbingan');
    });
    
    echo "✅ Route yang tersedia:\n";
    foreach($routes as $route) {
        if($route) {
            echo "- {$route}\n";
        }
    }
    
    // Cek kelompok bimbingan yang ada
    $kelompoks = \App\Models\Kelompok::where('status', 'approved')->with(['ketua'])->get();
    
    echo "\n=== KELOMPOK BIMBINGAN TERSEDIA ===\n";
    foreach($kelompoks as $k) {
        echo "ID: {$k->id} - {$k->nama_kelompok} (Ketua: {$k->ketua->name})\n";
        $detailUrl = route('dosen.bimbingan_mahasiswa.show', $k->id);
        echo "URL Detail: {$detailUrl}\n\n";
    }
    
    echo "=== INFO ===\n";
    echo "Login sebagai dosen@uhamka.ac.id untuk testing.\n";
    echo "Klik tombol 'Detail' di halaman Bimbingan Mahasiswa untuk melihat detail kelompok.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}