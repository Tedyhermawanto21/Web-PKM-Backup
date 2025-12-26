<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'program_studi',
        'no_hp',
        'jenis_kelamin',
        'nidn',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function kelompoks(): BelongsToMany
    {
        return $this->belongsToMany(Kelompok::class, 'kelompok_user')
                    ->withPivot('posisi')
                    ->withTimestamps();
    }

    public function kelompokAsKetua(): HasMany
    {
        return $this->hasMany(Kelompok::class, 'ketua_id');
    }

    public function kelompokAsDosen(): HasMany
    {
        return $this->hasMany(Kelompok::class, 'dosen_pembimbing_id');
    }

    public function proposalsAsKetua(): HasMany
    {
        return $this->hasMany(Proposal::class, 'ketua_id');
    }

    public function proposalsAsDosen(): HasMany
    {
        return $this->hasMany(Proposal::class, 'dosen_pembimbing_id');
    }

    // Helper methods
    public function isMahasiswa(): bool
    {
        return $this->role->name === 'mahasiswa';
    }

    public function isDosen(): bool
    {
        return $this->role->name === 'dosen';
    }

    public function isKaprodi(): bool
    {
        return $this->role->name === 'kaprodi';
    }

    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }
}
