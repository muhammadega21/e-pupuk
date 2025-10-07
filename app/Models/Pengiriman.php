<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'pengiriman_id';
    protected $fillable = ['pesanan_id', 'nama_penerima', 'telepon', 'alamat', 'ongkir', 'tgl_kirim', 'tgl_terima', 'status'];
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
