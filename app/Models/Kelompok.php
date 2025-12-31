<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelompok extends Model
{
    protected $fillable = [
        'nama_kelompok',
        'judul_pkm',
        'jenis_pkm',
        'deskripsi',
        'ketua_id',
        'dosen_pembimbing_id',
        'status',
        'status_kaprodi',
        'catatan_kaprodi'
    ];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelompok_user')
                    ->withPivot('posisi')
                    ->withTimestamps();
    }
}
