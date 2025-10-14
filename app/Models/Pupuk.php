<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pupuk extends Model
{
    protected $table = 'pupuk';
    protected $primaryKey = 'barang_id';
    protected $fillable = ['nama', 'jenis', 'berat', 'harga', 'stok', 'status', 'slug'];
    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'barang_id');
    }
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'barang_id');
    }
}
