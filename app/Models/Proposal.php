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
        'catatan_penolakan',
        'status_dosen',
        'status_kaprodi',
        'catatan_dosen',
        'catatan_kaprodi',
        'file_proposal',
        'status_admin',
        'catatan_admin',
        'revision_stage',
        'revision_notes'
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

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'proposal_reviewer', 'proposal_id', 'reviewer_id')
                    ->withPivot(['status', 'score', 'comments', 'created_at', 'updated_at']);
    }
}
