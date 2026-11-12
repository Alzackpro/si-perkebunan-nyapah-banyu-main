<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    // Accessor to map username to name
    public function getNameAttribute()
    {
        return $this->username;
    }

    // Accessor to lowercase role
    public function getRoleAttribute($value)
    {
        return strtolower($value);
    }

    // Helper untuk mengecek role
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isPengurus(): bool
    {
        return $this->role === 'pengurus';
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user');
    }
}
