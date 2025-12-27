<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'type',
        'name',
        'start_date',
        'end_date',
        'is_active',
        'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Method untuk cek apakah jadwal sedang berlangsung
    public function isOngoing()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $now->greaterThanOrEqualTo($this->start_date) && 
               $now->lessThanOrEqualTo($this->end_date);
    }

    // Method untuk cek apakah jadwal sudah lewat
    public function isPast()
    {
        return Carbon::now()->greaterThan($this->end_date);
    }

    // Method untuk cek apakah jadwal belum dimulai
    public function isUpcoming()
    {
        return Carbon::now()->lessThan($this->start_date);
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope untuk jadwal yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk jadwal yang sedang berlangsung
    public function scopeOngoing($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    // Konstanta untuk tipe jadwal
    const TYPE_UPLOAD_PROPOSAL = 'upload_proposal';
    const TYPE_REVISI_1 = 'revisi_1';
    const TYPE_REVISI_2 = 'revisi_2';
    const TYPE_REVISI_3 = 'revisi_3';

    public static function getTypes()
    {
        return [
            self::TYPE_UPLOAD_PROPOSAL => 'Upload Proposal',
            self::TYPE_REVISI_1 => 'Revisi Tahap 1',
            self::TYPE_REVISI_2 => 'Revisi Tahap 2',
            self::TYPE_REVISI_3 => 'Revisi Tahap 3',
        ];
    }
}

