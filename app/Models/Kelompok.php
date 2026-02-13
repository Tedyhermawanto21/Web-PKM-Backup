<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'catatan_kaprodi',
        'file_proposal'
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

    public function kelompokAnggota(): HasMany
    {
        return $this->hasMany(KelompokAnggota::class);
    }

    /**
     * Get all anggota from both kelompok_user pivot (registered users)
     * and kelompok_anggota table (free-form entries), merged into a uniform collection.
     * Each item has: nama, nim, program_studi, posisi.
     */
    public function getAllAnggota()
    {
        // From kelompok_user pivot (registered users)
        $pivotAnggota = $this->anggota->map(function ($user) {
            return (object) [
                'nama' => $user->name,
                'nim' => $user->nim ?? '-',
                'program_studi' => $user->program_studi ?? '-',
                'posisi' => $user->pivot->posisi ?? 'anggota',
            ];
        });

        // From kelompok_anggota table (free-form entries)
        $freeAnggota = $this->kelompokAnggota->map(function ($row) {
            return (object) [
                'nama' => $row->nama,
                'nim' => $row->nim ?? '-',
                'program_studi' => $row->program_studi ?? '-',
                'posisi' => $row->posisi ?? 'anggota',
            ];
        });

        // Merge, deduplicate by NIM, sort ketua first
        $merged = $pivotAnggota->merge($freeAnggota);

        // Deduplicate by NIM (keep first occurrence)
        $seen = [];
        $unique = $merged->filter(function ($item) use (&$seen) {
            if ($item->nim && $item->nim !== '-' && in_array($item->nim, $seen)) {
                return false;
            }
            if ($item->nim && $item->nim !== '-') {
                $seen[] = $item->nim;
            }
            return true;
        });

        // Sort: ketua first, then anggota
        return $unique->sortBy(function ($item) {
            return $item->posisi === 'ketua' ? 0 : 1;
        })->values();
    }
}
