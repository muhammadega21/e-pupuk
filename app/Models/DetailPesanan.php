<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'detailPesanan_id';
    protected $fillable = ['pesanan_id', 'barang_id', 'qty_karung', 'subtotal'];
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class, 'barang_id');
    }
}
