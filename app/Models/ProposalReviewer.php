<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalReviewer extends Model
{
    protected $table = 'proposal_reviewer';

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'status',
        'score',
        'comments',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime'
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
