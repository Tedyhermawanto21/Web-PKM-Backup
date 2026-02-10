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
    // Jika jadwal aktif (is_active=true), maka dianggap ongoing
    public function isOngoing()
    {
        return $this->is_active;
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
    // Jika jadwal aktif (is_active=true), maka dianggap ongoing meskipun tanggalnya sudah lewat
    // Ini memberikan fleksibilitas kepada admin untuk membuka jadwal kapan saja
    public function scopeOngoing($query)
    {
        return $query->where('is_active', true);
    }

    // Konstanta untuk tipe jadwal
    const TYPE_PENGAJUAN_KELOMPOK = 'pengajuan_kelompok';
    const TYPE_UPLOAD_PROPOSAL = 'upload_proposal';
    const TYPE_REVISI_1 = 'revisi_1';
    const TYPE_REVISI_2 = 'revisi_2';
    const TYPE_REVISI_3 = 'revisi_3';

    public static function getTypes()
    {
        return [
            self::TYPE_PENGAJUAN_KELOMPOK => 'Pengajuan Kelompok PKM',
            self::TYPE_UPLOAD_PROPOSAL => 'Upload Proposal',
            self::TYPE_REVISI_1 => 'Revisi Tahap 1',
            self::TYPE_REVISI_2 => 'Revisi Tahap 2',
            self::TYPE_REVISI_3 => 'Revisi Tahap 3',
        ];
    }
}

