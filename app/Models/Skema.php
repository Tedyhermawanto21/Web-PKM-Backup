<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $fillable = ['nama', 'label', 'warna', 'panduan'];

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'skema', 'nama');
    }
}
