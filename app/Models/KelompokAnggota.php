<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokAnggota extends Model
{
    protected $table = 'kelompok_anggota';

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'nama',
        'nim',
        'program_studi',
        'posisi',
    ];

    public $timestamps = true;
}
