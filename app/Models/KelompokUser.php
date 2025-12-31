<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokUser extends Model
{
    protected $table = 'kelompok_user';

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'posisi',
        'nama',
        'nim',
        'program_studi',
    ];

    public $timestamps = true;
}
