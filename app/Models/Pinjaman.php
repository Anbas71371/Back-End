<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal', 'besar_pinjaman', 'jasa', 'total'];
    protected $table = 'pinjaman';

    // Event creating digunakan untuk menghitung total sebelum data disimpan
    public static function boot()
    {
        parent::boot();
    
        self::creating(function ($model) {
            $total = $model->besar_pinjaman + $model->jasa;
            $model->total = $total;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
