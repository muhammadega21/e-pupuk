<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';
    protected $primaryKey = 'produksi_id';
    protected $fillable = ['barang_id', 'tanggal_produksi', 'jumlah_karung', 'note'];
    public function barang()
    {
        return $this->belongsTo(Pupuk::class, 'barang_id');
    }
}
