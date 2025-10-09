<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class KepalaSekolah extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'kepala_sekolahs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'email',
        'password',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'no_whatsapp',
        'pendidikan_terakhir',
        'jurusan',
        'universitas',
        'tahun_lulus',
        'tanggal_mulai_tugas',
        'sk_pengangkatan',
        'role',
        'email_verified_at',
        'status_aktif',
        'foto_profil',
        'created_at',
        'updated_at',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'tanggal_mulai_tugas' => 'date',
        'tahun_lulus' => 'integer',
        'status_aktif' => 'boolean',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Scope a query to only include active kepala sekolah.
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope a query to only include kepala sekolah role.
     */
    public function scopeKepalaSekolah($query)
    {
        return $query->where('role', 'kepala_sekolah');
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->nama_lengkap;
    }

    /**
     * Check if kepala sekolah is active
     */
    public function isActive()
    {
        return $this->status_aktif;
    }
    /**
     * Quick fix - Check if kepala sekolah has unread comments
     */
    public function hasUnreadComments()
    {
        return false; // Always return false for now
    }

    /**
     * Quick fix - Get unread comments count
     */
    public function getUnreadCommentsCountAttribute()
    {
        return 0; // Always return 0 for now
    }

    /**
     * Quick fix - Get unread comments (empty collection)
     */
    public function unreadComments()
    {
        return collect([]); // Return empty collection
    }

    /**
     * Quick fix - Get all comments (empty collection)
     */
    public function comments()
    {
        return collect([]); // Return empty collection
    }

    /**
     * Quick fix - Mark comments as read (do nothing)
     */
    public function markCommentsAsRead()
    {
        return true; // Always return true
    }
}
