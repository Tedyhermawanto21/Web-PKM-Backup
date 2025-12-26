<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    protected $fillable = [
        'judul_kelompok',
        'nama_kelompok',
        'skema',
        'ketua_id',
        'dosen_pembimbing_id',
        'status',
        'catatan_penolakan'
    ];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(ProposalAnggota::class);
    }
}
