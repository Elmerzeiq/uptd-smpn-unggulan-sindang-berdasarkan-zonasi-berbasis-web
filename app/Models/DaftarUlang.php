<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarUlang extends Model
{
    use HasFactory;

    protected $table = 'daftar_ulangs';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'nomor_daftar_ulang',
        'kartu_lolos_seleksi',
        'bukti_pembayaran',
        'total_biaya',
        'status_pembayaran',
        'status_daftar_ulang',
        'catatan_verifikasi',
        'tanggal_pembayaran',
        'tanggal_verifikasi_berkas',
        'tanggal_verifikasi_pembayaran',
        'verified_by',
        'hadir_daftar_ulang',
        'waktu_kehadiran'
    ];

    protected $casts = [
        'total_biaya' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
        'tanggal_verifikasi_berkas' => 'datetime',
        'tanggal_verifikasi_pembayaran' => 'datetime',
        'waktu_kehadiran' => 'datetime',
        'hadir_daftar_ulang' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalDaftarUlang::class, 'jadwal_id');
    }

    // Add this for backward compatibility with existing views
    public function jadwalDaftarUlang()
    {
        return $this->belongsTo(JadwalDaftarUlang::class, 'jadwal_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function detailBiaya()
    {
        return $this->hasMany(DetailBiayaSiswa::class, 'daftar_ulang_id');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'belum_daftar' => 'secondary',
            'menunggu_verifikasi_berkas' => 'warning text-dark',
            'berkas_diverifikasi' => 'info text-dark',
            'menunggu_verifikasi_pembayaran' => 'primary',
            'daftar_ulang_selesai' => 'success',
            'ditolak' => 'danger'
        ];

        $statusText = config('ppdb.status_daftar_ulang.' . $this->status_daftar_ulang, 'Unknown');
        return '<span class="badge bg-' . ($badges[$this->status_daftar_ulang] ?? 'dark') . '">' . $statusText . '</span>';
    }

    public function getPembayaranBadgeAttribute()
    {
        $badges = [
            'belum_bayar' => 'secondary',
            'menunggu_verifikasi' => 'warning text-dark',
            'sudah_lunas' => 'success',
            'ditolak' => 'danger'
        ];

        $statusText = config('ppdb.status_pembayaran.' . $this->status_pembayaran, 'Unknown');
        return '<span class="badge bg-' . ($badges[$this->status_pembayaran] ?? 'dark') . '">' . $statusText . '</span>';
    }

    public function getStatusTextAttribute()
    {
        return config('ppdb.status_daftar_ulang.' . $this->status_daftar_ulang, ucfirst(str_replace('_', ' ', $this->status_daftar_ulang)));
    }

    public function getStatusPembayaranTextAttribute()
    {
        return config('ppdb.status_pembayaran.' . $this->status_pembayaran, ucfirst(str_replace('_', ' ', $this->status_pembayaran)));
    }

    // Static methods
    public static function generateNomorDaftarUlang(): string
    {
        $prefix = 'DU' . date('Y');
        $lastEntry = self::where('nomor_daftar_ulang', 'like', $prefix . '%')->latest('id')->first();
        $lastNumber = $lastEntry ? ((int)substr($lastEntry->nomor_daftar_ulang, strlen($prefix))) : 0;
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
