<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;

class User extends AuthenticatableUser
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nama', 'nip', 'no_hp', 'no_rekening', 'alamat', 'password', 'nomor_anggota', // tambahkan 'nomor_anggota' ke fillable
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->nomor_anggota = static::generateNomorAnggota();
        });
    }

    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class);
    }

    protected static function generateNomorAnggota()
{
    $latestUser = static::orderBy('created_at', 'desc')->first();

    if (!$latestUser) {
        return 'ANG-1'; // Jika belum ada user lain, nomor anggota dimulai dari 1
    }

    $latestNomorAnggota = $latestUser->nomor_anggota;

    // Periksa apakah $latestNomorAnggota adalah string yang valid sebelum melakukan explode
    if (!is_string($latestNomorAnggota)) {
        return 'ANG-1'; // Kembalikan nomor anggota default jika $latestNomorAnggota bukan string
    }

    $exploded = explode('-', $latestNomorAnggota);

    // Periksa apakah array hasil explode memiliki elemen dengan kunci 1
    if (!isset($exploded[1])) {
        return 'ANG-1'; // Kembalikan nomor anggota default jika elemen array dengan kunci 1 tidak ditemukan
    }

    $latestNumber = (int) $exploded[1];
    $newNumber = $latestNumber + 1;

    return 'ANG-' . $newNumber; // Format nomor anggota sesuai kebutuhan
}

}
