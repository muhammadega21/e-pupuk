<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pupuk extends Model
{
    protected $table = 'pupuk';
    protected $primaryKey = 'pupuk_id';
    protected $fillable = ['nama', 'jenis', 'berat', 'harga', 'stok', 'status', 'slug', 'gambar'];

    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'pupuk_id');
    }
    
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pupuk_id');
    }
}
