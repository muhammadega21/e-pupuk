<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'pesanan_id';
    protected $fillable = ['customer_id', 'created_by', 'handled_by', 'order_no', 'channel', 'order_type', 'payment_status', 'fulfillment_status', 'total_karung', 'total_bayar'];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
