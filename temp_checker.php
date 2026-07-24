<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::with(['nomorInduk', 'role'])->limit(15)->get();

$out = "";
foreach($users as $user) {
    $nim = $user->nomorInduk ? $user->nomorInduk->value : 'KOSONG';
    $role = $user->role ? $user->role->name : 'N/A';
    $out .= "Email: {$user->email} | NIM/NIDN: {$nim} | Role: {$role} \n";
}
file_put_contents('output.txt', $out);
