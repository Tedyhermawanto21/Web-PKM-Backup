<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomorInduk extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'value',
        'type',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
