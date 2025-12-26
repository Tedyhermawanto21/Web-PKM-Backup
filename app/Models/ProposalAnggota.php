<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalAnggota extends Model
{
    protected $table = 'proposal_anggota';

    protected $fillable = [
        'proposal_id',
        'nama',
        'nim',
        'program_studi',
        'posisi'
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
