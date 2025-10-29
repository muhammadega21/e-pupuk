<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'pesanan_id';
    protected $fillable = ['data_user_id', 'created_by', 'handled_by', 'tanggal_transaksi', 'order_no', 'channel', 'order_type', 'payment_status', 'fulfillment_status', 'total_karung', 'total_bayar'];
    
    public function user_data()
    {
        return $this->belongsTo(UserData::class, 'data_user_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
    
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
    
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }
    
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'pesanan_id');
    }
}
